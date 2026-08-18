<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\Outlet;
use App\Models\Stock;
use App\Models\ItemMaster;
use App\Models\Category;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InitationStockController extends Controller
{
    public function index()
    {
        $entitas    = Entitas::all();
        $lokasi     = Outlet::all();
        $items      = ItemMaster::all();
        $categories = Category::all();
        return view('pages.stock.initation.index', compact('lokasi', 'entitas', 'items', 'categories'));
    }

    public function store(Request $request)
    {
        $input  = $request->all();
        try {
            DB::beginTransaction();
            foreach ($request->item as $item) {
                foreach ($item['variants'] as $variant) {

                    if (empty($variant['qty']) || $variant['qty'] <= 0) {
                        continue;
                    }

                    $cekStok = Stock::where('item_varian_id', $variant['id_variant'])
                        ->where('lokasi_id', $input['lokasi_id'])
                        ->where('entitas_id', $input['entitas_id'])
                        ->lockForUpdate()
                        ->first();

                    if ($cekStok) {
                        $cekStok->increment('jumlah', $variant['qty']);
                    } else {
                        Stock::create([
                            'item_varian_id' => $variant['id_variant'],
                            'lokasi_id'      => $input['lokasi_id'],
                            'entitas_id'     => $input['entitas_id'],
                            'jumlah'         => $variant['qty'],
                        ]);
                    }
                }
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
