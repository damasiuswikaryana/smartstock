<?php

namespace App\Http\Controllers;

use App\Models\ItemMaster;
use App\Models\StockInMaster;
use App\Models\StockOutMaster;
use App\Models\StockTransferMaster;
use App\Models\StockMutation;
use App\Models\Project;
use App\Models\Stock;
use App\Models\Satuan;
use App\Models\Category;
use App\Models\Entitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function getItembyCategory(int $id)
    {
        $items = ItemMaster::where('category_id', $id)->get();

        return response()->json([
            'success'   => true,
            'items'     => $items
        ]);
    }

    public function getVariants(int $id)
    {
        $item       = ItemMaster::with('varian')->findOrFail($id);
        $satuans    = Satuan::all();

        return response()->json([
            'success'   => true,
            'variants'  => $item->varian,
            'satuans'   => $satuans,
        ]);
    }

    public function getVariantStocks(int $id, int $gudang_id, int $entitas_id)
    {
        $entitas    = Entitas::where('entitas_name', 'Global')->orWhere('id', $entitas_id)->get();
        $item       = ItemMaster::with([
            'varian' => function ($q) use ($gudang_id, $entitas_id) {
                $q->with([
                    'stock' => function ($q) use ($gudang_id, $entitas_id) {
                        $q->where('lokasi_id', $gudang_id)
                            ->where('entitas_id', $entitas_id);
                    }
                ]);
            }
        ])->findOrFail($id);

        $variants = $item->varian->map(function ($variant) {
            return [
                'id'            => $variant->id,
                'sku_varian'    => $variant->sku_varian,
                'name_varian'   => $variant->name_varian,
                'stok'          => $variant->stock->sum('jumlah'),
            ];
        });

        return response()->json([
            'success'   => true,
            'variants'  => $variants,
            'entitas'   => $entitas,
        ]);
    }

    public function getStockbyEntityItem(int $id, int $gudang_id, int $entitas_id)
    {
        $stock = Stock::select('jumlah')
            ->where('item_varian_id', $id)
            ->where('lokasi_id', $gudang_id)
            ->where('entitas_id', $entitas_id)
            ->first();
        if ($stock == null) {
            $stock_jum = 0;
        } else {
            $stock_jum = $stock->jumlah;
        }
        return response()->json([
            'success'   => true,
            'stock'     => $stock_jum,
        ]);
    }

    public function getStockIn(Request $request)
    {
        $lokasi         = $request->warehouse_id;
        $bulan          = date("m");
        $tahun          = date("Y");

        $stockIn        = StockInMaster::with('child')
            ->where('werehouse_id', $lokasi)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count('id');

        return response()->json([
            'stockIn' => number_format($stockIn)
        ]);
    }

    public function getStockOut(Request $request)
    {
        $lokasi         = $request->warehouse_id;
        $bulan          = date("m");
        $tahun          = date("Y");

        $stockIn        = StockOutMaster::with('child')
            ->where('werehouse_id', $lokasi)
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count('id');

        return response()->json([
            'stockOut' => number_format($stockIn)
        ]);
    }

    public function getStockTrf(Request $request)
    {
        $lokasi         = $request->warehouse_id;
        $bulan          = date("m");
        $tahun          = date("Y");

        $stockIn        = StockTransferMaster::with('child')
            ->where(function ($query) use ($lokasi) {
                $query->where('werehouse_source_id', $lokasi)
                    ->orWhere('werehouse_target_id', $lokasi);
            })
            ->whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count('id');

        return response()->json([
            'stockTrf' => number_format($stockIn)
        ]);
    }

    public function getClients(Request $request)
    {
        $entitas            = $request->entitas_id;
        if ($entitas === 'all') {
            $clients        = Project::query()->count('id');
        } else {
            $clients        = Project::where('entitas_id', $entitas)->count('id');
        }

        return response()->json([
            'clients' => number_format($clients)
        ]);
    }

    public function getContractFullfillment()
    {
        $projects           = Project::with('items')->get();
        $stockSummary       = StockMutation::query()
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
                    ) AS reality_qty,
                    SUM(
                        CASE
                            WHEN (stock_mutations.tipe = 'Keluar' AND stock_mutations.source_id = pekerjaan.werehouse_id)
                            THEN stock_mutations.jumlah
                            ELSE 0
                        END
                    ) AS reality_qty_out")
            ->groupBy('stock_mutations.pekerjaan_id')
            ->get()
            ->keyBy('pekerjaan_id');


        $totalProject       = $projects->count();
        $completedProject   = $projects->filter(function ($project) use ($stockSummary) {
            $totalQty       = $project->items->sum('req_qty');
            $completedQty   = $stockSummary->get($project->id)?->reality_qty_out ?? 0;
            return $totalQty > 0 && $completedQty >= $totalQty;
        })->count();
        $percentage         = $totalProject > 0 ? round(($completedProject / $totalProject) * 100) : 0;

        return response()->json([
            'percentage'        => $percentage,
            'totalProject'      => $totalProject,
            'completedProject'  => $completedProject,
        ]);
    }

    public function topItems(Request $request)
    {
        $lokasi = $request->lokasi_id;
        $data   = Stock::join('item_varian', 'stocks.item_varian_id', '=', 'item_varian.id')
            ->select(
                'item_varian.name_varian',
                'item_varian.sku_varian',
                DB::raw('SUM(stocks.jumlah) as total_qty')
            )
            ->where('stocks.lokasi_id', $lokasi)
            ->groupBy(
                'item_varian.id',
                'item_varian.name_varian',
                'item_varian.sku_varian'
            )
            ->orderByDesc('total_qty')
            ->limit(5)
            ->get();

        return response()->json([
            'categories'    => $data->pluck('name_varian'),
            'series'        => $data->pluck('total_qty'),
            'items'         => $data,
        ]);
    }

    public function categories()
    {
        $data = Category::query()
            ->leftJoin('item_master', 'categories.id', '=', 'item_master.category_id')
            ->select(
                'categories.id',
                'categories.title',
                DB::raw('COUNT(item_master.id) as total_item')
            )
            ->groupBy('categories.id', 'categories.title')
            ->orderBy('categories.title')
            ->get();

        return response()->json([
            'categories' => $data->pluck('title'),
            'series'     => $data->pluck('total_item'),
            'items'      => $data,
        ]);
    }
}
