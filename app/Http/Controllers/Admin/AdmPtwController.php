<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Po;
use App\Models\PoChild;
use App\Models\Ptw;
use App\Models\PtwChild;
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
            // kirim notif ke gudang untuk di cek
            $targetToken    = User::role('gudang')->select('device_token')->first();
            $dataNumber     = $ptw_master->ptw_number;
            $idRequestData  = $ptw_master->id;
            $firebase->send(
                $targetToken->device_token,
                'PROCUREMENT TO WAREHOUSE',
                '[PTW] - ' . $dataNumber . ' telah diterbitkan oleh team pengadaaan. Lihat pada dashboard Smartwarehouse.',
                ['url' => '/ptw/' . $idRequestData . '/detail']
            );

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function detail(int $id)
    {
        $po             = Po::all();
        $data           = Ptw::with(['child.poMaster', 'child.varian'])->where('id', $id)->first();

        foreach ($data->child as $child) {
            $child->qty_po = PoChild::where('po_id', $child->po_id)
                ->where('item_varian_id', $child->item_varian_id)
                ->value('qty');
        }
        return view('pages.ptw.detail', compact('data', 'po'));
    }

    public function edit(int $id)
    {
        $project    = Project::all();
        $data       = Ptw::with('child')->where('id', $id)->first();

        return view('pages.ptw.edit', compact('data', 'project'));
    }

    public function update(Request $request, int $id)
    {
        $data   = Ptw::where('id', $id)->first();
        $input  = $request->all();

        try {
            DB::beginTransaction();
            $data->ptw_number      = $input['ptw_number'];
            $data->ptw_date        = $input['ptw_date'];
            $data->project_id      = $input['project_id'];
            $data->note            = $input['notes'];
            $data->ptw_status      = $input['status'];
            $data->save();
            DB::commit();

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function addPo(Request $request, int $id)
    {
        $input              = $request->all();
        try {
            $poId           = $input['po_id'];
            $purchaseOrder  = Po::find($poId);
            foreach ($purchaseOrder->child as $itemVarian) {
                PtwChild::create([
                    'ptw_id'            => $id,
                    'po_id'             => $poId,
                    'item_varian_id'    => $itemVarian->item_varian_id,
                    'prf_jum'           => 0,
                    'note'              => NULL,
                ]);
            }
            return redirect()->route('ptw.detail', $id)->with('success', 'PO successfuly added');
        } catch (\Throwable $th) {
            return redirect()->route('ptw.detail', $id)->with('error',  $th->getMessage());
        }
    }

    public function updateItem(Request $request)
    {
        try {
            foreach ($request->items as $item) {
                PtwChild::where('id', $item['child_id'])
                    ->update([
                        'prf_jum' => $item['prf_jum'],
                        'note'    => $item['note'],
                    ]);
            }
            return response()->json([
                'success' => true,
                'message' => 'Data berhasil disimpan.'
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'success' => false,
                'message' => $th->getMessage()
            ], 500);
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

    public function destroyPo(int $id)
    {
        try {
            $data = PtwChild::where('po_id', $id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function downloadPtw(int $id, PtwDownloladService $ptwService)
    {
        $data           = Ptw::select('ptw_number')->where('id', $id)->first();
        $namaPtw        = str_replace('/', '_', $data->ptw_number);
        $pdf            = $ptwService->generatePdf($id);
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Procurement_to_Warehouse_' . $namaPtw . '_' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
