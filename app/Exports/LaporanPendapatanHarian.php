<?php

namespace App\Exports;

use App\Models\Outlet;
use App\Models\Gerobak;
use App\Models\GerobakStock;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\TransactionOutlet;
use App\Models\Pengeluaran;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

use App\Exports\Sheets\PendapatanOutletSheetHarian;
use App\Exports\Sheets\PendapatanGerobakSheetHarian;
use App\Exports\Sheets\PengeluaranSheetHarian;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LaporanPendapatanHarian implements WithMultipleSheets
{
    use Exportable;
    
    protected $id_outlet;
    protected $awal;
    protected $akhir;
    public function __construct(int $id_outlet, string $tanggal)
    {
        $this->id_outlet    = $id_outlet;
        $this->tanggal      = $tanggal;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();

        return [
            new PendapatanOutletSheetHarian($data),
            new PendapatanGerobakSheetHarian($data),
            new PengeluaranSheetHarian($data),
        ];
    }
    
    protected function getData()
    {
        $idOutlet                   = $this->id_outlet;
        $tanggal                    = $this->tanggal;
        
        $tglNow                     = $tanggal;
        $data_outlet                = Outlet::where('id', $idOutlet)->first();
        $gerobak_outlet             = Gerobak::where('loc_id', $idOutlet)->pluck('id')->toArray();
        // Pengeluaran
        $data_pengeluaran_all       = Pengeluaran::where('pengeluaran_date', $tglNow)->get();
        $data_pengeluaran_outlet    = Pengeluaran::where('pengeluaran_tipe', 'Outlet')
                                        ->where('pengeluaran_date', $tglNow)
                                        ->where('pengeluaran_status', 'Approved')
                                        ->where('id_outlet', $idOutlet)->get();
        $data_pengeluaran_gerobak   = Pengeluaran::where('pengeluaran_tipe', 'Gerobak')
                                        ->where('pengeluaran_date', $tglNow)
                                        ->where('pengeluaran_status', 'Approved')
                                        ->whereIn('id_gerobak', $gerobak_outlet)->get();
        $total_pengeluaran          = $data_pengeluaran_outlet->sum('pengeluaran_harga') + $data_pengeluaran_gerobak->sum('pengeluaran_harga');
        // Transaction Gerobak
        $data_gerobak               = Gerobak::all();
        $data_stock                 = GerobakStock::where('stock_date', $tglNow)->get()->groupBy('gerobak_id');
        $data_trans                 = Transaction::with('transaction_produk.product')
                                        ->where('trans_date', $tglNow)
                                        ->where('trans_tipe', 'Gerobak')
                                        ->get()
                                        ->groupBy('gerobak_id');
        $data_outlet->gerobak       = $data_gerobak->where('loc_id', $data_outlet->id)->values();
        $total_gerobak_transaksi    = 0;
        $trans_gerobak_cash         = 0;
        $trans_gerobak_qris         = 0;
        $data_product_master        = Product::induk()->orderBy('nama')->pluck('id')->toArray();
        
        foreach ($data_outlet->gerobak as $gerobak) {
            $gerobak->trans_master  = Transaction::where('trans_date', $tglNow)
                                        ->where('trans_tipe', 'Gerobak')
                                        ->where('gerobak_id', $gerobak->id)
                                        ->first();
            $gerobak->trans         = collect();
            $gerobak->trans_nominal = collect();
            $gerobak->stock         = collect();
            if ($data_trans->has($gerobak->id)) {
                foreach ($data_trans[$gerobak->id] as $trans) {
                    foreach ($trans->transaction_produk->whereIn('product_id', $data_product_master) as $item) {
                        $gerobak->trans->push($item);
                        $prod_id                = $item->product_id;
                        $data_product_varian    = Product::where('product_master_id', $prod_id)->orderBy('nama')->get();
                        
                        foreach ($data_product_varian as $varian) {
                            $dummyVarian = (object)[
                                'product_id'    => $varian->id,
                                'product'       => $varian,
                                'qty'           => 0,
                                'product_harga' => 0,
                                'row_type'      => 'varian',
                            ];
                            $transVarian = $trans->transaction_produk->firstWhere('product_id', $varian->id);
                            if ($transVarian) {
                                $dummyVarian->qty           = $transVarian->product_sales;
                                $dummyVarian->product_harga = $transVarian->product_harga;
                            }
                            $gerobak->trans->push($dummyVarian);
                        }
                    }
                    foreach ($trans->transaction_nominal as $item) {
                        if ($item->metode_bayar == "Cash" && $trans->trans_status == "Approved") {
                            $trans_gerobak_cash = $trans_gerobak_cash + $item->transaction_amount;
                        }
                        elseif ($item->metode_bayar == "Qris" && $trans->trans_status == "Approved") {
                            $trans_gerobak_qris = $trans_gerobak_qris + $item->transaction_amount;
                        }
                        if ($trans->trans_status == "Approved") {
                            $total_gerobak_transaksi = $total_gerobak_transaksi + $item->transaction_amount;
                        }
                        
                        $gerobak->trans_nominal->push($item);
                    }
                    
                }
            }
            if ($data_stock->has($gerobak->id)) {
                $stockPerProduct = collect();
            
                foreach ($data_stock[$gerobak->id] as $stock) {
                    if (!isset($stockPerProduct[$stock->product_id])) {
                        $stockPerProduct[$stock->product_id] = collect();
                    }
                    $stockPerProduct[$stock->product_id]->push($stock);
                }
            
                $gerobak->stock = $stockPerProduct;
            } else {
                $gerobak->stock = collect();
            }
        }
        $total_gerobak_transaksi = $total_gerobak_transaksi - $data_pengeluaran_gerobak->sum('pengeluaran_harga');
        
        // Transaction Outlet
        $data_trans_outlet      = Transaction::with('transaction_outlet.product')
                                    ->where('outlet_id', $idOutlet)
                                    ->where('trans_date', $tglNow)
                                    ->where('trans_tipe', 'Outlet')
                                    ->get();
        $total_out_transaksi    = 0;
        $trans_out_cash         = 0;
        $trans_out_qris         = 0;
        foreach ($data_trans_outlet as $d) {
            $produk = $d->transaction_outlet;
            $total  = 0;
            foreach ($produk as $p) {
                $subtotal   = $p->qty * $p->product_harga;
                $total      = $total + $subtotal;
            }
            if ($d->trans_outlet_metode == "Cash") {
                $trans_out_cash = $trans_out_cash + $total;
            } elseif ($d->trans_outlet_metode == "Qris") {
                $trans_out_qris = $trans_out_qris + $total;
            } else {
                
            }
            $total_out_transaksi = $total_out_transaksi + $total;
        }
        $total_out_transaksi = $total_out_transaksi - $data_pengeluaran_outlet->sum('pengeluaran_harga');
        $total_harian_outlet = $total_out_transaksi + $total_gerobak_transaksi;
        
        return [
            'tanggal'                   => $tglNow,
            'data_outlet'               => $data_outlet,
            'data_trans_outlet'         => $data_trans_outlet,
            'data_pengeluaran_all'      => $data_pengeluaran_all,
            'total_out_transaksi'       => $total_out_transaksi,
            'trans_out_cash'            => $trans_out_cash,
            'trans_out_qris'            => $trans_out_qris,
            'total_gerobak_transaksi'   => $total_gerobak_transaksi,
            'trans_gerobak_cash'        => $trans_gerobak_cash,
            'trans_gerobak_qris'        => $trans_gerobak_qris,
            'total_harian_outlet'       => $total_harian_outlet,
            'data_pengeluaran_outlet'   => $data_pengeluaran_outlet,
            'data_pengeluaran_gerobak'  => $data_pengeluaran_gerobak,
            'total_pengeluaran'         => $total_pengeluaran,
        ];
    }
}
