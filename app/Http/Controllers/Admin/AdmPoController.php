<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\ItemMaster;
use App\Models\ItemVarian;
use App\Models\Vendor;
use App\Models\Po;
use App\Models\PoChild;
use App\Models\Satuan;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Services\FirebaseNotificationService;
use App\Services\PoDownloladService;

class AdmPoController extends Controller
{
    public function index(Request $request)
    {
        $tahun      = date("Y");
        $bulan      = date("m");
        $vendor     = Vendor::all();
        $entitas    = Entitas::all();
        $items      = ItemMaster::all();
        $lokasi     = Auth::user()->loc_id;
        $data       = Po::with('child')->whereMonth('po_date', $bulan)->whereYear('po_date', $tahun);

        if ($request->ajax()) {
            // filter werehouse
            if ($request->status) {
                $status     = $request->status;
                if ($status == 'pending') {
                    $data       = $data->where('po_status', "Pending");
                } elseif ($status == 'checked') {
                    $data       = $data->where('checked_date', '!=', NULL);
                } elseif ($status == "approved") {
                    $data       = $data->where('po_status', "Approved");
                }
            }
            // filter daterange
            if ($request->range) {
                $range      = $request->range;
                $dates      = explode(' to ', $range);
                $startDate  = trim($dates[0]);
                if (count($dates) > 1 && !empty($dates[1])) {
                    $endDate = trim($dates[1]);
                    $data = $data->whereBetween('po_date', [
                        $startDate,
                        $endDate
                    ]);
                } else {
                    $data = $data->whereDate('po_date', $startDate);
                }
            }
            $data = $data->get();
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->po_status == "Pending") {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('po.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('po.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                    } else {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('po.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                    }
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('po_number', function ($row) {
                    return '<code>' . $row->po_no . '</code>';
                })
                ->addColumn('po_date', function ($row) {
                    return tglIndo4($row->po_date);
                })
                ->addColumn('entitas', function ($row) {
                    return $row->entitas->entitas_name;
                })
                ->addColumn('vendor', function ($row) {
                    return $row->vendor->nama;
                })
                ->addColumn('items', function ($row) {
                    return $row->child->count();
                })

                ->addColumn('po_status', function ($row) {
                    if ($row->po_status == "Pending") {
                        return '<span class="badge bg-light-dark f-14">Pending</span>';
                    } else {
                        return '<span class="badge bg-light-success text-green f-14">Approval</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'po_number', 'po_date', 'entitas', 'vendor', 'items', 'po_status'])
                ->make(true);
        }
        return view('pages.po.index', compact('vendor', 'items', 'entitas'));
    }

    public function store(Request $request)
    {
        $input      = $request->all();
        $keuangan   = User::role('keuangan')->first();
        $director   = Entitas::select('director_id')->where('id', $input['entitas_id'])->first();
        try {
            DB::beginTransaction();
            $po_master              = Po::create([
                'po_no'             => $input['po_no'],
                'po_date'           => $input['po_date'],
                'po_status'         => "Pending",
                'entitas_id'        => $input['entitas_id'],
                'vendor_id'         => $input['vendor_id'],
                'tax'               => $input['tax'],
                'disc'              => hapusTitikAngka($input['disc']),
                'dp'                => hapusTitikAngka($input['dp']),
                'created_by'        => Auth::user()->id,
                'checked_by'        => $keuangan->id,
                'checked_date'      => NULL,
                'director_id'       => $director->director_id,
                'director_date'     => NULL,
                'notes'             => $input['notes'],
            ]);
            DB::commit();

            foreach ($request->item as $item) {
                foreach ($item['variants'] as $variant) {
                    if (!empty($variant['qty']) && $variant['qty'] > 0) {
                        PoChild::create([
                            'po_id'             => $po_master->id,
                            'item_varian_id'    => $variant['id_variant'],
                            'qty'               => $variant['qty'],
                            'satuan_id'         => $variant['satuan'],
                            'unit_price'        => $variant['nilai_variant'],
                        ]);
                    }
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
        $data           = Po::with('child')->where('id', $id)->first();
        $subtotal       = $data->child->sum(function ($child) {
            return $child->unit_price * $child->qty;
        });
        $tax            = $data->tax;
        $tax_amount     = $tax / 100 * $subtotal;
        $total_after_tax = $subtotal + $tax_amount;
        $disc           = $data->disc;
        $total_after_disc = $total_after_tax - $disc;
        $dp             = $data->dp;
        $total_after_dp = $total_after_disc + $dp;

        return view('pages.po.detail', compact('data', 'subtotal', 'tax_amount', 'total_after_tax', 'total_after_disc', 'total_after_dp'));
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
        try {
            DB::beginTransaction();
            $data->po_no             = $input['po_no'];
            $data->po_date           = $input['po_date'];
            $data->entitas_id        = $input['entitas_id'];
            $data->vendor_id         = $input['vendor_id'];
            $data->tax               = $input['tax'];
            $data->disc              = hapusTitikAngka($input['disc']);
            $data->dp                = hapusTitikAngka($input['dp']);
            $data->notes             = $input['notes'];
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
                            'unit_price'        => $item['nilai_variant']
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
            $data = Po::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function checked(Request $request, int $id)
    {
        $data   = Po::where('id', $id)->first();
        try {
            DB::beginTransaction();
            $data->checked_date    = date("Y-m-d H:i:s");
            $data->checked_by      = Auth::user()->id;
            $data->save();
            $des                   = tanggalIndoWaktuLidgkap($data->checked_date) . " by " . $data->checkedBy->firstname . " " . $data->checkedBy->lastname;
            DB::commit();
            return response()->json(['success' => true, 'approve' => $des]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function approved(Request $request, int $id)
    {
        $data   = Po::where('id', $id)->first();
        try {
            if ($data->checked_date != NULL) {
                DB::beginTransaction();
                $data->director_date    = date("Y-m-d H:i:s");
                $data->director_id      = Auth::user()->id;
                $data->po_status        = "Approved";
                $data->save();
                $des                    = tanggalIndoWaktuLidgkap($data->director_date) . " by " . $data->directorBy->firstname . " " . $data->directorBy->lastname;
                DB::commit();
                return response()->json(['success' => true, 'approve' => $des]);
            } else {
                return response()->json(['success' => false, 'message' => "PO must be checked first by finance"]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function downloadPo(int $id, PoDownloladService $poService)
    {
        $data           = Po::select('po_no')->where('id', $id)->first();
        $pdf            = $poService->generatePdf($id);
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Purchase Order - ' . $data->po_no . ' - ' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
