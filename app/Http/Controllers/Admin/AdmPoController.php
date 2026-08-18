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
use App\Models\User;
use App\Models\Category;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
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
        $categories = Category::all();
        $user       = User::where('id', Auth::user()->id)->first();
        $data       = Po::with('child')->whereMonth('po_date', $bulan)->whereYear('po_date', $tahun);

        if ($request->ajax()) {
            // filter werehouse
            if ($request->status) {
                $status     = $request->status;
                if ($status == 'pending') {
                    $data = $data->where('po_status', "Pending");
                } elseif ($status == 'checked') {
                    $data       = $data->where('po_status', "Checked");
                } elseif ($status == "recorded") {
                    $data       = $data->where('po_status', "Recorded");
                } elseif ($status == "approved") {
                    $data       = $data->where('po_status', "Approved");
                } elseif ($status == "myapproval") {
                    // cek roles
                    if ($user->hasRole('keuangan')) {
                        $data       = $data->whereNull('checked_date');
                    }
                    if ($user->hasRole('adminkeuangan')) {
                        $data       = $data->whereNull('adminInput_date');
                    }
                    if ($user->hasRole('director')) {
                        $data       = $data->whereNotNull('director_id')->whereNull('director_date');
                    }
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
            // filter director
            if ($request->director) {
                $director   = $request->director;
                if ($director == "yes") {
                    $data   = $data->whereNotNull('director_id');
                } elseif ($director == "no") {
                    $data   = $data->whereNull('director_id');
                } else {
                    $data   = $data;
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
                ->addColumn('po_dp', function ($row) {
                    if ($row->dp == NULL || $row->dp == 0) {
                        return '';
                    } else {
                        return '<span class="badge bg-danger rounded-pill">DP</span>';
                    }
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
                    } elseif ($row->po_status == "Recorded") {
                        return '<span class="badge bg-light-success text-green f-14">Recorded</span>';
                    } elseif ($row->po_status == "Checked") {
                        return '<span class="badge bg-light-primary f-14">Checked</span>';
                    } else {
                        return '<span class="badge bg-light-success text-green f-14">Approval</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'po_number', 'po_date', 'po_dp', 'entitas', 'vendor', 'items', 'po_status'])
                ->make(true);
        }
        return view('pages.po.index', compact('vendor', 'items', 'entitas', 'categories'));
    }

    public function store(Request $request, FirebaseNotificationService $firebase)
    {
        $input          = $request->all();
        $keuangan       = User::role('keuangan')->first();
        $keuanganAdmin  = User::role('adminkeuangan')->first();
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
            $po_master              = Po::create([
                'po_no'             => $input['po_no'],
                'po_date'           => $input['po_date'],
                'po_status'         => "Pending",
                'entitas_id'        => $input['entitas_id'],
                'vendor_id'         => $input['vendor_id'],
                'tax'               => $input['tax'],
                'ppn'               => $input['ppn'],
                'disc'              => $disc_rp,
                'disc_perc'         => $disc_pr,
                'dp'                => hapusTitikAngka($input['dp']),
                'created_by'        => Auth::user()->id,
                'adminInput_by'     => $keuanganAdmin->id,
                'adminInput_date'   => NULL,
                'checked_by'        => $keuangan->id,
                'checked_date'      => NULL,
                'director_id'       => $director_id,
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

            // kirim notif ke keuangan
            $targetToken    = User::role('keuangan')->select('device_token')->first();
            $dataNumber     = $po_master->po_no;
            $idRequestData  = $po_master->id;
            $firebase->send(
                $targetToken->device_token,
                'BUTUH CHECK',
                '[Purchase Order] - ' . $dataNumber . ' telah diinput. Butuh pengecekan Anda. Lihat pada dashboard Smartwarehouse.',
                ['url' => '/po/' . $idRequestData . '/detail']
            );
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
        $ppn            = $data->ppn;
        $ppn_amount     = $ppn / 100 * $subtotal;
        $total_after_tax = $subtotal + $tax_amount + $ppn_amount;
        $disc           = $data->disc;
        if ($disc != NULL) {
            $disc_amount        = $disc;
        } else {
            $disc_perc          = $data->disc_perc;
            $disc_amount        = $disc_perc / 100 * $subtotal;
        }
        $total_after_disc   = $total_after_tax - $disc_amount;
        $dp                 = $data->dp;
        $total_after_dp     = $total_after_disc + $dp;

        return view('pages.po.detail', compact('data', 'subtotal', 'tax_amount', 'ppn_amount', 'total_after_tax', 'disc_perc', 'disc_amount', 'total_after_disc', 'total_after_dp'));
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

    public function checked(Request $request, int $id, FirebaseNotificationService $firebase)
    {
        $data   = Po::where('id', $id)->first();
        try {
            DB::beginTransaction();
            $data->checked_date    = date("Y-m-d H:i:s");
            $data->checked_by      = Auth::user()->id;
            if ($data->director_id != NULL) {
                $data->po_status   = "Checked";
            } else {
                $data->po_status   = "Approved";
            }
            $data->save();
            $des                   = tanggalIndoWaktuLidgkap($data->checked_date) . " by " . $data->checkedBy->firstname . " " . $data->checkedBy->lastname;
            DB::commit();
            if ($data->director_id != NULL) {
                // kirim notif ke director
                $targetToken    = User::select('device_token')->where('id', $data->director_id)->first();
                $dataNumber     = $data->po_no;
                $idRequestData  = $data->id;
                $firebase->send(
                    $targetToken->device_token,
                    'BUTUH APPROVAL',
                    '[Purchase Order] - ' . $dataNumber . ' telah selesai dicek oleh keuangan. Butuh approval Anda. Lihat pada dashboard Smartwarehouse.',
                    ['url' => 'po/' . $idRequestData . '/detail']
                );
            } else {
                // kirim notif ke admin keuangan
                $targetToken    = User::role('adminkeuangan')->select('device_token')->first();
                $dataNumber     = $data->po_no;
                $idRequestData  = $data->id;
                $firebase->send(
                    $targetToken->device_token,
                    'BUTUH APPROVAL',
                    '[Purchase Order] - ' . $dataNumber . ' telah selesai dicek oleh keuangan. Saatnya menambahkan ke Zaheer. Lihat pada dashboard Smartwarehouse.',
                    ['url' => '/po/' . $idRequestData . '/detail']
                );
            }
            return response()->json(['success' => true, 'approve' => $des]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function approved(Request $request, int $id, FirebaseNotificationService $firebase)
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
                // kirim notif balik ke pengadaan
                $targetToken    = User::select('device_token')->where('id', $data->created_by)->first();
                $dataNumber     = $data->po_no;
                $idRequestData  = $data->id;
                $firebase->send(
                    $targetToken->device_token,
                    'PO DISETUJUI',
                    '[Purchase Order] - ' . $dataNumber . ' telah berhasil disetujui direktur. Lihat pada dashboard Smartwarehouse.',
                    ['url' => '/po/' . $idRequestData . '/detail']
                );
                // kirim juga ke admin keuangan agar ditambahkan ke zaheer
                $targetToken2    = User::role('adminkeuangan')->select('device_token')->first();
                $firebase->send(
                    $targetToken2->device_token,
                    'PO DISETUJUI',
                    '[Purchase Order] - ' . $dataNumber . ' telah berhasil disetujui direktur. Saatnya menambahkan ke Zaheer. Lihat pada dashboard Smartwarehouse.',
                    ['url' => '/po/' . $idRequestData . '/detail']
                );
                return response()->json(['success' => true, 'approve' => $des]);
            } else {
                return response()->json(['success' => false, 'message' => "PO must be checked first by finance"]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function recorded(Request $request, int $id, FirebaseNotificationService $firebase)
    {
        $data   = Po::where('id', $id)->first();
        try {
            DB::beginTransaction();
            $data->adminInput_date  = date("Y-m-d H:i:s");
            $data->adminInput_by    = Auth::user()->id;
            $data->po_status        = "Recorded";
            $data->save();
            $des                   = tanggalIndoWaktuLidgkap($data->adminInput_date) . " by " . $data->adminInputBy->firstname . " " . $data->adminInputBy->lastname;
            DB::commit();
            // kirim notif ke finance
            $targetToken    = User::role('keuangan')->select('device_token')->first();
            $dataNumber     = $data->po_no;
            $idRequestData  = $data->id;
            $firebase->send(
                $targetToken->device_token,
                'BUTUH APPROVAL',
                '[Purchase Order] - ' . $dataNumber . ' telah selesai penginputan di sistem Zaheer. Lihat pada dashboard Smartwarehouse.',
                ['url' => '/po/' . $idRequestData . '/detail']
            );

            return response()->json(['success' => true, 'approve' => $des]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function downloadPo(int $id, PoDownloladService $poService)
    {
        $data           = Po::select('po_no')->where('id', $id)->first();
        $namaPo         = str_replace('/', '_', $data->po_no);
        $pdf            = $poService->generatePdf($id);
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Purchase_Order_' . $namaPo . '_' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
