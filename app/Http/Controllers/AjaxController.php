<?php

namespace App\Http\Controllers;

use App\Models\ItemMaster;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class AjaxController extends Controller
{
    public function getVariants(int $id)
    {
        $item = ItemMaster::with('varian')->findOrFail($id);

        return response()->json([
            'success' => true,
            'variants' => $item->varian
        ]);
    }

    public function getVariantStocks(int $id, int $gudang_id)
    {
        $item = ItemMaster::with([
            'varian' => function ($q) use ($gudang_id) {
                $q->with([
                    'stock' => function ($q) use ($gudang_id) {
                        $q->where('lokasi_id', $gudang_id);
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
            'variants'  => $variants
        ]);
    }
}
