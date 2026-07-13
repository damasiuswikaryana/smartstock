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
}
