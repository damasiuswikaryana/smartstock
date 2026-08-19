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
use Barryvdh\DomPDF\Facade\Pdf;

use App\Services\StockReportService;

class AdmStockCurrentController extends Controller
{
    public function index(Request $request)
    {
        $allCategory    = Category::all();
        $allEntitas     = Entitas::all();
        $lokasi         = Auth::user()->loc_id;
        // jika yang login adalah roles berikut
        if (
            Auth::user()->roles[0]->name == "masteradmin"
            || Auth::user()->roles[0]->name == "pengadaan"
            || Auth::user()->roles[0]->name == "gudang"
        ) {
            $data       = Stock::query();
            $allGudang  = Outlet::all();
        } else {
            $data       = Stock::where('lokasi_id', $lokasi);
            $allGudang  = Outlet::where('id', $lokasi)->get();
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

            // cek duplikasi data
            $duplicateKeys = $data
                ->groupBy(function ($row) {
                    return $row->item_varian_id . '-' . $row->lokasi_id . '-' . $row->entitas_id;
                })
                ->filter(function ($group) {
                    return $group->count() > 1;
                })
                ->keys();

            $data->each(function ($row) use ($duplicateKeys) {
                $key = $row->item_varian_id . '-' . $row->lokasi_id . '-' . $row->entitas_id;
                $row->is_duplicate = $duplicateKeys->contains($key);
            });

            return DataTables::of($data)
                ->addIndexColumn()
                ->setRowClass(function ($row) {
                    return $row->is_duplicate ? 'class_duplicate' : '';
                })
                ->addColumn('last_update', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('sku', function ($row) {
                    return $row->item_varian->sku_varian;
                })
                ->addColumn('item', function ($row) {
                    $badge = '';
                    if ($row->is_duplicate) {
                        $badge = '<span class="badge bg-light-danger">
                            <i class="ph-duotone ph-warning-octagon ms-2"></i> Duplicate</span>';
                    }
                    return '<div class="d-flex align-items-center">
                        <p class="fw-bold mb-0">' . $row->item_varian->name_varian . '</p>
                        ' . $badge . '
                    </div>';
                })
                ->addColumn('variant', function ($row) {
                    return '<code>' . $row->item_varian->kode_varian . '</code>';
                })
                ->addColumn('werehouse', function ($row) {
                    return '<div class="d-flex flex-column">
                        <p class="fw-bold mb-0">' . $row->lokasi->nama . '</p>
                        <p class="text-muted mb-0">' . $row->entitas->entitas_name . '</p>
                    </div>';
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
                ->rawColumns(['action', 'last_update', 'item', 'werehouse', 'sku', 'entity', 'category', 'qty'])
                ->make(true);
        }
        return view('pages.stock.current.index', compact('allGudang', 'allCategory', 'allEntitas'));
    }

    public function downloadReport($whid, $cat, $entitas, StockReportService $reportService)
    {
        // dd($stocks);
        // $customPaper    = [0, 0, 220, 425];
        $pdf    = $reportService->generatePdf($whid, $cat, $entitas);
        if ($whid != "all") {
            $werehouse = namaLokasi($whid);
        } else {
            $werehouse = "Semua Gudang";
        }
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Stock Report - ' . $werehouse . ' - ' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
