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

use App\Exports\Sheets\PendapatanOutletSheetBulanan;
use App\Exports\Sheets\PendapatanGerobakSheetBulanan;
use App\Exports\Sheets\PengeluaranSheetBulanan;

use Carbon\Carbon;
use Carbon\CarbonPeriod;

class LaporanPendapatanBulanan implements WithMultipleSheets
{
    use Exportable;
    
    protected $id_outlet;
    protected $awal;
    protected $akhir;
    public function __construct(int $id_outlet, string $awal, string $akhir)
    {
        $this->id_outlet    = $id_outlet;
        $this->awal         = $awal;
        $this->akhir        = $akhir;
    }
    
    public function sheets(): array
    {
        $data = $this->getData();

        return [
            new PendapatanOutletSheetBulanan($data),
            new PendapatanGerobakSheetBulanan($data),
            new PengeluaranSheetBulanan($data),
        ];
    }
    
    protected function getData()
    {
        $idOutlet                   = $this->id_outlet;
        $awal                       = $this->awal;
        $akhir                      = $this->akhir;
        $bulanTahun                 = Carbon::parse($awal)->format('F Y');
        
        // PENGELUARAN
        $data_pengeluaran_all       = Pengeluaran::whereBetween('pengeluaran_date', [$awal, $akhir])->get();
        $gerobak_outlet             = Gerobak::where('loc_id', $idOutlet)->pluck('id')->toArray();
        $data_outlet                = Outlet::find($idOutlet);
        $data_pengeluaran_outlet    = Pengeluaran::where('pengeluaran_tipe', 'Outlet')
                                    ->whereBetween('pengeluaran_date', [$awal, $akhir])
                                    ->where('pengeluaran_status', 'Approved')
                                    ->where('id_outlet', $idOutlet)->get();
        $data_pengeluaran_gerobak   = Pengeluaran::where('pengeluaran_tipe', 'Gerobak')
                                    ->whereBetween('pengeluaran_date', [$awal, $akhir])
                                    ->where('pengeluaran_status', 'Approved')
                                    ->whereIn('id_gerobak', $gerobak_outlet)->get();
        $total_pengeluaran          = $data_pengeluaran_outlet->sum('pengeluaran_harga') + $data_pengeluaran_gerobak->sum('pengeluaran_harga');
        
        // TRANS OUTLET
        $data_trans_outlet          = Transaction::with('transaction_outlet.product')
                                    ->where('outlet_id', $idOutlet)
                                    ->whereBetween('trans_date', [$awal, $akhir])
                                    ->where('trans_tipe', 'Outlet')->get();
        $trans_out_cash             = 0;
        $trans_out_qris             = 0;
        $total_out_transaksi        = 0;
        foreach ($data_trans_outlet as $d) {
            $total = 0;
            foreach ($d->transaction_outlet as $p) {
                $total += $p->qty * $p->product_harga;
            }
            if ($d->trans_outlet_metode == "Cash") {
                $trans_out_cash += $total;
            } elseif ($d->trans_outlet_metode == "Qris") {
                $trans_out_qris += $total;
            }
            $total_out_transaksi += $total;
        }
        $total_out_transaksi        -= $data_pengeluaran_outlet->sum('pengeluaran_harga');
        
        // TRANS GEROBAK
        $data_gerobak               = Gerobak::where('loc_id', $idOutlet)->get();
        $data_trans_gerobak         = Transaction::with(['transaction_produk.product', 'transaction_nominal'])
                                    ->whereBetween('trans_date', [$awal, $akhir])
                                    ->where('trans_tipe', 'Gerobak')->get();
        $data_stock                 = GerobakStock::whereBetween('stock_date', [$awal, $akhir])->get();
        foreach ($data_gerobak as $g) {
            $g->trans = collect([]);
            foreach ($data_trans_gerobak as $tg) {
                $tg->stock = collect([]);
                if ($tg->gerobak_id == $g->id) {
                    $g->trans->push($tg);
                }
                foreach ($data_stock as $ds) {
                    if ($tg->trans_date == $ds->stock_date && $g->id == $ds->gerobak_id) {
                        $tg->stock->push($ds);
                    }
                }
            }
        }
        $trans_gerobak_cash         = 0;
        $trans_gerobak_qris         = 0;
        foreach ($data_gerobak as $row) {
            $trans = $row->trans->where('trans_status', 'Approved');
            foreach ($trans as $t) {
                $trans_nom = $t->transaction_nominal;
                $trans_gerobak_cash += $trans_nom->where('metode_bayar', 'Cash')->sum('transaction_amount');
                $trans_gerobak_qris += $trans_nom->where('metode_bayar', 'Qris')->sum('transaction_amount');
            }
        }
        $total_gerobak_transaksi    = ($trans_gerobak_cash + $trans_gerobak_qris) - $data_pengeluaran_gerobak->sum('pengeluaran_harga');

        return [
            'awal'                      => $awal,
            'akhir'                     => $akhir,
            'bulan_tahun'               => $bulanTahun,
            'data_outlet'               => $data_outlet,
            'data_gerobak'              => $data_gerobak,
            'data_pengeluaran_all'      => $data_pengeluaran_all,
            'data_trans_outlet'         => $data_trans_outlet,
            'trans_out_cash'            => $trans_out_cash,
            'trans_out_qris'            => $trans_out_qris,
            'total_out_transaksi'       => $total_out_transaksi,
            'data_gerobak'              => $data_gerobak,
            'trans_gerobak_cash'        => $trans_gerobak_cash,
            'trans_gerobak_qris'        => $trans_gerobak_qris,
            'total_gerobak_transaksi'   => $total_gerobak_transaksi,
            'data_pengeluaran_outlet'   => $data_pengeluaran_outlet,
            'data_pengeluaran_gerobak'  => $data_pengeluaran_gerobak,
            'total_pengeluaran'         => $total_pengeluaran,
        ];
    }
}
