<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Project;
use App\Models\ProjectItems;
use App\Models\Category;
use App\Models\StockInChild;
use App\Models\StockMutation;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;
use DB;

class AdmFullfillmentController extends Controller
{
    public function index()
    {
        $pekerjaan      = Project::with('items')->get();
        $stockSummary   = StockMutation::query()
            ->join('item_varian', 'item_varian.id', '=', 'stock_mutations.item_id')
            ->join('pekerjaan', 'pekerjaan.id', '=', 'stock_mutations.pekerjaan_id')
            ->selectRaw("
                stock_mutations.pekerjaan_id,
                SUM(
                    CASE
                        WHEN (stock_mutations.tipe = 'Masuk' AND stock_mutations.target_id = pekerjaan.werehouse_id)
                        OR (stock_mutations.tipe = 'Transfer' AND stock_mutations.target_id = pekerjaan.werehouse_id)
                        THEN stock_mutations.jumlah
                        ELSE 0
                    END
                ) AS reality_qty
            ")
            ->groupBy('stock_mutations.pekerjaan_id')
            ->get()
            ->keyBy('pekerjaan_id');

        return view('pages.fullfillment.index', compact('pekerjaan', 'stockSummary'));
    }

    public function add(int $id)
    {
        $category   = Category::all();
        $data       = Project::with('items')->where('id', $id)->first();

        $totalQty   = $data->items->sum('req_qty');
        $grandTotalContract = $data->items->sum(function ($item) {
            return $item->req_qty * $item->req_nominal;
        });
        $grandTotalCompany = $data->items->sum(function ($item) {
            return $item->req_qty * $item->req_nominal_company;
        });


        return view('pages.fullfillment.add', compact('data', 'category', 'totalQty', 'grandTotalContract', 'grandTotalCompany'));
    }

    public function storeItem(Request $request, int $id)
    {
        $input      = $request->all();
        try {
            DB::beginTransaction();
            $stock_master = ProjectItems::create([
                'pekerjaan_id'          => $id,
                'item_master_id'        => $input['item_master_id'],
                'req_qty'               => $input['qty'],
                'req_nominal'           => hapusTitikAngka($input['harga']),
                'req_nominal_company'   => hapusTitikAngka($input['harga_company']),
            ]);
            DB::commit();
            return redirect()->back()->with('success', '1 item added');
        } catch (\Throwable $th) {
            DB::rollback();
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function detail(int $id)
    {
        $category       = Category::all();
        $data           = Project::with('items')->where('id', $id)->first();
        $gudang_koneksi = $data->werehouse_id;

        $stockSummary   = StockMutation::query()
            ->join('item_varian', 'item_varian.id', '=', 'stock_mutations.item_id')
            ->selectRaw(
                "
                        item_varian.item_master_id,
                        SUM(
                            CASE
                                WHEN stock_mutations.tipe = 'Masuk' AND stock_mutations.target_id = ?
                                THEN stock_mutations.jumlah
                                WHEN stock_mutations.tipe = 'Transfer' AND stock_mutations.target_id = ?
                                THEN stock_mutations.jumlah
                                ELSE 0
                            END
                        ) AS reality_qty,
                        SUM(
                            CASE
                                WHEN (stock_mutations.tipe = 'Masuk' AND stock_mutations.target_id = ?)
                                OR (stock_mutations.tipe = 'Transfer' AND stock_mutations.target_id = ?)
                                THEN stock_mutations.jumlah * item_varian.nilai
                                ELSE 0
                            END
                        ) AS purchase_cost,
                        SUM(
                            CASE
                                WHEN (stock_mutations.tipe = 'Keluar' AND stock_mutations.source_id = ?)
                                OR (stock_mutations.tipe = 'Transfer' AND stock_mutations.source_id = ?)
                                THEN stock_mutations.jumlah
                                ELSE 0
                            END
                        ) as qty_out
                    ",
                [
                    $gudang_koneksi,
                    $gudang_koneksi,
                    $gudang_koneksi,
                    $gudang_koneksi,
                    $gudang_koneksi,
                    $gudang_koneksi,
                ]
            )
            ->where('pekerjaan_id', $id)
            // ->where(function ($query) use ($gudang_koneksi) {
            //     $query->where(function ($q) use ($gudang_koneksi) {
            //         $q->where('tipe', 'Masuk')->where('target_id', $gudang_koneksi);
            //     })->orWhere(function ($q) use ($gudang_koneksi) {
            //         $q->where('tipe', 'Transfer')->where('target_id', $gudang_koneksi);
            //     });
            // })
            ->groupBy('item_varian.item_master_id')
            ->get()
            ->keyBy('item_master_id');

        $totalQty   = $data->items->sum('req_qty');
        $grandTotal = $data->items->sum(function ($item) {
            return $item->req_qty * $item->req_nominal;
        });

        $data_in        = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Masuk')
            ->where('target_id', $gudang_koneksi)
            ->get();

        $data_out        = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Keluar')
            ->where('source_id', $gudang_koneksi)
            ->get();

        $data_trf       = StockMutation::where('pekerjaan_id', $id)
            ->where('tipe', 'Transfer')
            ->where(function ($query) use ($gudang_koneksi) {
                $query->where('source_id', $gudang_koneksi)
                    ->orWhere('target_id', $gudang_koneksi);
            })
            ->get();

        return view('pages.fullfillment.detail', compact('data', 'category', 'totalQty', 'grandTotal', 'stockSummary', 'data_in', 'data_out', 'data_trf'));
    }
}
