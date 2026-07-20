<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;
use App\Models\Outlet;
use App\Models\Category;
use App\Models\Entitas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdmStockCurrentController extends Controller
{
    public function index(Request $request)
    {
        $allGudang      = Outlet::all();
        $allCategory    = Category::all();
        $allEntitas     = Entitas::all();

        $lokasi         = Auth::user()->loc_id;
        // jika yang login adalah masteradmin
        if (Auth::user()->roles[0]->name == "masteradmin") {
            $data       = Stock::query();
        } else {
            $data       = Stock::where('lokasi_id', $lokasi)->get();
        }

        if ($request->ajax()) {
            // filter werehouse
            if ($request->gudang) {
                $gudang_id  = $request->gudang;
                $data       = $data->where('lokasi_id', $gudang_id);
            }
            // filter category
            if ($request->category) {
                $cat_id     = $request->category;
                $data->whereHas('item_varian.itemMaster', function ($q) use ($cat_id) {
                    $q->where('category_id', $cat_id);
                });
            }
            // filter entitas
            if ($request->entitas) {
                $entitas_id = $request->entitas;
                $data       = $data->where('entitas_id', $entitas_id);
            }

            // get data
            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('last_update', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('item', function ($row) {
                    return '<p class="mb-0 fw-bold">' . $row->item_varian->itemMaster->nama . '</p><p class="text-muted mb-0">[' . $row->item_varian->sku_varian . ']</p>';
                })
                ->addColumn('variant', function ($row) {
                    return '<code>' . $row->item_varian->kode_varian . '</code>';
                })
                ->addColumn('werehouse', function ($row) {
                    return '<p class="mb-0 fw-bold">' . $row->lokasi->nama . '</p><p class="text-muted mb-0"><small>' . $row->entitas->entitas_name . '</small></p>';
                })
                ->addColumn('entity', function ($row) {
                    return $row->entitas->entitas_name;
                })
                ->addColumn('category', function ($row) {
                    return $row->item_varian->itemMaster->category->title;
                })
                ->addColumn('qty', function ($row) {
                    return $row->jumlah;
                })
                ->rawColumns(['action', 'last_update', 'item', 'variant', 'werehouse', 'entity', 'category', 'qty'])
                ->make(true);
        }
        return view('pages.stock.current.index', compact('allGudang', 'allCategory', 'allEntitas'));
    }
}
