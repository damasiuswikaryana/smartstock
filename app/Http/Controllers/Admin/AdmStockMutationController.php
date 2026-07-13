<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockMutation;
use App\Models\StockInMasterPhoto;
use App\Models\StockInChild;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class AdmStockMutationController extends Controller
{
    public function index(Request $request)
    {
        $lokasi     = Auth::user()->loc_id;
        $data       = StockMutation::where('source_id', $lokasi)->orWhere('target_id', $lokasi)->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalDetail" data-bs-placement="top" title="Detail" href="' . route('stockMutation.detail', $row->id) . '" class="avtar avtar-s btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('source_type', function ($row) {
                    if ($row->source_type == 'External') {
                        return '<span class="badge bg-light-secondary">External</span>';
                    } elseif ($row->source_type == 'Central') {
                        return '<span class="badge bg-light-primary">Central</span>';
                    } else {
                        return '<span class="badge bg-light-danger">Cabang</span>';
                    }
                })
                ->addColumn('target_type', function ($row) {
                    if ($row->target_type == 'External') {
                        return '<span class="badge bg-light-secondary">External</span>';
                    } elseif ($row->target_type == 'Central') {
                        return '<span class="badge bg-light-primary">Central</span>';
                    } else {
                        return '<span class="badge bg-light-danger">Cabang</span>';
                    }
                })
                ->addColumn('item', function ($row) {
                    return $row->item_varian->name_varian;
                })
                ->addColumn('tipe', function ($row) {
                    if ($row->tipe == 'Masuk') {
                        return '<span class="badge bg-light-success" style="color:#036342;"><i class="ti ti-arrow-bar-to-right"></i> Masuk</span>';
                    } elseif ($row->tipe == 'Keluar') {
                        return '<span class="badge bg-light-danger"><i class="ti ti-arrow-bar-left"></i> Keluar</span>';
                    } elseif ($row->tipe == 'Transfer') {
                        return '<span class="badge bg-light-primary"><i class="ti ti-arrows-right-left"></i> Transfer</span>';
                    } else {
                        return '<span class="badge bg-light-secondary"><i class="ti ti-circle-x"></i> Broken</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'source_type', 'target_type', 'item', 'tipe'])
                ->make(true);
        }
        return view('pages.stock.mutation.index');
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
