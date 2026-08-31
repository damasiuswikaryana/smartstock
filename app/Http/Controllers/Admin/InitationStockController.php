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

                    // masukin ke stock mutasi, agar dapat ditrack
                    $namaGudang = namaLokasi($input['lokasi_id']);
                    $tipe       = 'Initiation';
                    $pekerjaan  = NULL;
                    $source     = 'External';
                    $source_id  = NULL;
                    if ($input['lokasi_id'] == 1) {
                        $target = "Central";
                    } else {
                        $target = "Cabang";
                    }
                    $target_id  = $input['lokasi_id'];
                    $keterangan = 'Inisiasi awal stok masuk dari external ke ' . $namaGudang;
                    $entitas    = $input['entitas_id'];
                    storeMutation(
                        $tipe,
                        $pekerjaan,
                        $source,
                        $source_id,
                        $target,
                        $target_id,
                        $variant['id_variant'],
                        $variant['qty'],
                        $keterangan,
                        $entitas
                    );
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
