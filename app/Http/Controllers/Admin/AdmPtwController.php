<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\ItemMaster;
use App\Models\ItemVarian;
use App\Models\Vendor;
use App\Models\Po;
use App\Models\PoChild;
use App\Models\Ptw;
use App\Models\PtwChild;
use App\Models\Satuan;
use App\Models\User;
use App\Models\Category;
use App\Models\Project;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Services\FirebaseNotificationService;
use App\Services\PtwDownloladService;

class AdmPtwController extends Controller
{
    public function index(Request $request)
    {
        $tahun      = date("Y");
        $bulan      = date("m");
        $data       = Ptw::with('child');
        $project    = Project::all();
        $data_po    = Po::with('child')->get();

        if ($request->ajax()) {
            // filter daterange
            if ($request->range) {
                $range      = $request->range;
                $dates      = explode(' to ', $range);
                $startDate  = trim($dates[0]);
                if (count($dates) > 1 && !empty($dates[1])) {
                    $endDate = trim($dates[1]);
                    $data = $data->whereBetween('ptw_date', [
                        $startDate,
                        $endDate
                    ]);
                } else {
                    $data = $data->whereDate('po_date', $startDate);
                }
            } else {
                $data->whereMonth('ptw_date', $bulan)->whereYear('ptw_date', $tahun);
            }
            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('ptw.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('ptw.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('ptw_number', function ($row) {
                    return '<code>' . $row->ptw_number . '</code>';
                })
                ->addColumn('ptw_date', function ($row) {
                    return tglIndo4($row->ptw_date);
                })
                ->addColumn('project', function ($row) {
                    return $row->project->name;
                })
                ->addColumn('prf_number', function ($row) {
                    return tglIndo4($row->ptw_date);
                })
                ->addColumn('po_number', function ($row) {
                    return tglIndo4($row->ptw_date);
                })
                ->rawColumns(['action', 'updated_at', 'ptw_number', 'ptw_date', 'project', 'prf_number', 'po_number'])
                ->make(true);
        }
        return view('pages.ptw.index', compact('project', 'data_po'));
    }

    public function store(Request $request, FirebaseNotificationService $firebase)
    {
        $input          = $request->all();
        $poData         = $request->input('po', []);
        try {
            DB::beginTransaction();
            $ptw_master             = Ptw::create([
                'ptw_number'        => $input['ptw_no'],
                'project_id'        => $input['project_id'],
                'ptw_date'          => $input['ptw_date'],
                'ptw_status'        => "Pending",
                'note'              => $input['notes'],
            ]);
            DB::commit();
            foreach ($poData as $po) {
                $idPo           = $po['id_po'];
                $purchaseOrder  = Po::find($idPo);
                foreach ($purchaseOrder->child as $itemVarian) {
                    PtwChild::create([
                        'ptw_id'            => $ptw_master->id,
                        'po_id'             => $idPo,
                        'item_varian_id'    => $itemVarian->item_varian_id,
                        'prf_jum'           => 0,
                        'note'              => NULL,
                    ]);
                }
            }
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function detail(int $id)
    {
        $data           = Ptw::with('child')->where('id', $id)->first();
        return view('pages.ptw.detail', compact('data'));
    }

    public function edit(int $id)
    {
        $vendor             = Vendor::all();
        $entitas            = Entitas::all();
        $items              = ItemMaster::all();
        $satuan             = Satuan::all();
        $lokasi             = Auth::user()->loc_id;
        $data               = Po::with('child')->where('id', $id)->first();
        $dataVarian         = $data->child->pluck('item_varian_id')->toArray();

        $variants           = ItemVarian::whereIn('id', $dataVarian)->with('itemMaster')->get();
        $groupedVariants    = $variants->groupBy('item_master_id');
        // Ambil master yang terlibat
        $itemMasterIds      = ItemVarian::whereIn('id', $dataVarian)->pluck('item_master_id')->unique();
        $itemMasters        = ItemMaster::with('varian')->whereIn('id', $itemMasterIds)->get();
        $qtyData            = $data->child->keyBy('item_varian_id');

        return view('pages.po.edit', compact('data', 'vendor', 'entitas', 'items', 'itemMasters', 'qtyData', 'satuan'));
    }

    public function update(Request $request, int $id)
    {
        $data   = Po::where('id', $id)->first();
        $input  = $request->all();

        if ($input['dir_approval'] == "yes") {
            $director       = Entitas::select('director_id')->where('id', $input['entitas_id'])->first();
            $director_id    = $director->director_id;
        } else {
            $director_id    = NULL;
        }
        if ($input['disc_tipe'] == 'rupiah') {
            $disc_rp    = hapusTitikAngka($input['disc']);
            $disc_pr    = NULL;
        } else {
            $disc_rp    = NULL;
            $disc_pr    = hapusTitikAngka($input['disc']);
        }

        try {
            DB::beginTransaction();
            $data->po_no             = $input['po_no'];
            $data->prf_number        = $input['prf_no'];
            $data->po_date           = $input['po_date'];
            $data->entitas_id        = $input['entitas_id'];
            $data->vendor_id         = $input['vendor_id'];
            $data->tax               = $input['tax'];
            $data->ppn               = $input['ppn'];
            $data->disc              = $disc_rp;
            $data->disc_perc         = $disc_pr;
            $data->dp                = hapusTitikAngka($input['dp']);
            $data->notes             = $input['notes'];
            $data->director_id       = $director_id;
            $data->save();
            DB::commit();

            foreach ($request->items as $item) {
                if ($item['qty'] > 0) {
                    PoChild::updateOrCreate(
                        [
                            'po_id'             => $id,
                            'item_varian_id'    => $item['item_varian_id'],
                        ],
                        [
                            'qty'               => $item['qty'],
                            'satuan_id'         => $item['satuan'],
                            'unit_price'        => hapusTitikAngka($item['nilai_variant']),
                            'pph'               => $item['pph_variant']
                        ]
                    );
                } else {
                    PoChild::where('po_id', $id)
                        ->where('item_varian_id', $item['item_varian_id'])->delete();
                }
            }
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $data = Ptw::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function downloadPtw(int $id, PoDownloladService $poService)
    {
        $data           = Po::select('po_no')->where('id', $id)->first();
        $namaPo         = str_replace('/', '_', $data->po_no);
        $pdf            = $poService->generatePdf($id);
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Purchase_Order_' . $namaPo . '_' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
