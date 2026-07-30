<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMutation;
use App\Models\Outlet;
use App\Models\Category;
use App\Models\Entitas;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdmStockMutationController extends Controller
{
    public function index(Request $request)
    {
        $allCategory    = Category::all();
        $allEntitas     = Entitas::all();

        $lokasi     = Auth::user()->loc_id;

        if (
            Auth::user()->roles[0]->name == "masteradmin"
            || Auth::user()->roles[0]->name == "pengadaan"
            || Auth::user()->roles[0]->name == "gudang"
        ) {
            $data       = StockMutation::query();
            $allGudang  = Outlet::all();
        } else {
            $data       = StockMutation::where('source_id', $lokasi)->orWhere('target_id', $lokasi);
            $allGudang  = Outlet::where('id', $lokasi)->orWhere('id', 1)->get();
        }

        if ($request->ajax()) {
            // filter source
            if ($request->source) {
                $gudang_id  = $request->source;
                if ($gudang_id != "External") {
                    $data       = $data->where('source_id', $gudang_id);
                } else {
                    $data       = $data->where('source_type', $gudang_id);
                }
            }
            // filter target
            if ($request->target) {
                $gudang_id  = $request->target;
                if ($gudang_id != "External") {
                    $data       = $data->where('target_id', $gudang_id);
                } else {
                    $data       = $data->where('target_type', $gudang_id);
                }
            }
            // filter category
            if ($request->category) {
                $cat_id     = $request->category;
                $data->whereHas('item_varian.itemMaster', function ($q) use ($cat_id) {
                    $q->where('category_id', $cat_id);
                });
            }
            // filter entitas
            if ($request->tipe) {
                $tipe       = $request->tipe;
                $data       = $data->where('tipe', $tipe);
            }

            // get data
            $data = $data->get();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-placement="top" title="Detail" href="' . route('stockMutation.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('source_type', function ($row) {
                    if ($row->source_type == 'External') {
                        return '<p class="text-dark mb-0">External</p>';
                    } else {
                        return '<p class="text-dark mb-0">' . $row->gudangAsal->nama . '</p>';
                    }
                })
                ->addColumn('target_type', function ($row) {
                    if ($row->target_type == 'External') {
                        return '<p class="text-dark mb-0">External</p>';
                    } else {
                        return '<p class="text-dark mb-0">' . $row->gudangTarget->nama . '</p>';
                    }
                })
                ->addColumn('item', function ($row) {
                    return '<div class="d-flex flex-column">
                        <p class="fw-bold mb-0">' . $row->item_varian->name_varian . '</p>
                    </div>';
                })
                ->addColumn('sku', function ($row) {
                    return $row->item_varian->sku_varian;
                })
                ->addColumn('tipe', function ($row) {
                    if ($row->tipe == 'Masuk') {
                        return '<p class="text-green mb-0"><i class="ti ti-arrow-bar-to-right"></i> Masuk</p>';
                    } elseif ($row->tipe == 'Keluar') {
                        return '<p class="text-danger mb-0"><i class="ti ti-arrow-bar-left"></i> Keluar</p>';
                    } elseif ($row->tipe == 'Transfer') {
                        return '<p class="text-primary mb-0"><i class="ti ti-arrows-right-left"></i> Transfer</p>';
                    } else {
                        return '<p class="text-dark mb-0"><i class="ti ti-circle-x"></i> Broken</p>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'source_type', 'target_type', 'item', 'sku', 'tipe'])
                ->make(true);
        }
        return view('pages.stock.mutation.index', compact('allGudang', 'allCategory', 'allEntitas'));
    }

    public function detail(int $id)
    {
        $data   = StockMutation::where('id', $id)->first();
        try {
            return view('pages.stock.mutation.detail', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }
}
