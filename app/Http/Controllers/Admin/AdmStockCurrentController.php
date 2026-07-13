<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Stock;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdmStockCurrentController extends Controller
{
    public function index(Request $request)
    {
        $lokasi     = Auth::user()->loc_id;
        $data       = Stock::where('lokasi_id', $lokasi)->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('last_update', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('item', function ($row) {
                    return $row->item_varian->itemMaster->nama;
                })
                ->addColumn('variant', function ($row) {
                    return $row->item_varian->kode_varian;
                })
                ->addColumn('werehouse', function ($row) {
                    return $row->lokasi->nama;
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
        return view('pages.stock.current.index');
    }
}
