<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\Project;
use App\Models\Outlet;
use App\Models\ItemMaster;
use App\Models\ItemVarian;
use App\Models\StockTransferMaster;
use App\Models\StockTransferMasterPhoto;
use App\Models\StockTransferChild;
use App\Models\Stock;
use App\Models\Category;
use App\Models\StockOutChild;
use App\Models\User;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

use App\Services\FirebaseNotificationService;
use App\Services\TandaTerimaTransferService;

class TransferStockController extends Controller
{
    public function index(Request $request)
    {
        $gudang     = Auth::user()->loc_id;
        $pekerjaan  = Project::all();
        $entitas    = Entitas::all();
        $dataGudang = Outlet::all();
        $categories = Category::all();

        $stockav    = Stock::where('lokasi_id', $gudang)->get();
        $items      =
            ItemMaster::with([
                'varian' => function ($q) use ($gudang) {
                    $q->whereHas('stock', function ($q) use ($gudang) {
                        $q->where('lokasi_id', $gudang);
                    })->with([
                        'stock' => function ($q) use ($gudang) {
                            $q->where('lokasi_id', $gudang);
                        }
                    ]);
                }
            ])->whereHas('varian.stock', function ($q) use ($gudang) {
                $q->where('lokasi_id', $gudang);
            })->get();

        $data       = StockTransferMaster::with('child');

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == "Pending") {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stocktransfer.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('stocktransfer.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                    } else {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stocktransfer.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                    }
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('st_number', function ($row) {
                    return "<code>" . $row->stock_transfer_number . "</code>";
                })
                ->addColumn('date', function ($row) {
                    return tanggalIndo($row->transfer_date);
                })
                ->addColumn('werehouse_source', function ($row) {
                    return $row->gudangAsal->nama;
                })
                ->addColumn('werehouse_target', function ($row) {
                    return $row->gudangTarget->nama;
                })
                ->addColumn('entitas', function ($row) {
                    return '<p class="fw-bold mb-0">' . $row->pekerjaan->name . '</p>
                        <p class="f-10 mb-0">' . $row->entitas->entitas_name . '</p>';
                })
                ->addColumn('items', function ($row) {
                    return $row->child->count();
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == "Pending") {
                        return '<span class="badge bg-light-dark f-14">Pending</span>';
                    } else {
                        return '<span class="badge bg-light-success f-14 text-green">Approval</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'st_number', 'date', 'werehouse_source', 'werehouse_target', 'entitas', 'status'])
                ->make(true);
        }
        return view('pages.stock.transfer.index', compact('gudang', 'pekerjaan', 'items', 'entitas', 'stockav', 'dataGudang', 'categories'));
    }

    public function store(Request $request, FirebaseNotificationService $firebase)
    {
        $gudang     = Auth::user()->loc_id;
        $input      = $request->all();
        try {
            DB::beginTransaction();
            $stock_master = StockTransferMaster::create([
                'stock_transfer_number'     => $input['stock_transfer_number'],
                'transfer_srf'              => $input['transfer_srf'],
                'transfer_date'             => $input['transfer_date'],
                'werehouse_source_id'       => $gudang,
                'werehouse_target_id'       => $input['werehouse_target_id'],
                'entitas_id'                => $input['entitas_id'],
                'pekerjaan_id'              => $input['pekerjaan_id'],
                'note'                      => $input['notes'],
                'received_by'               => $input['received_by'],
                'status'                    => "Pending",
                'created_by'                => Auth::user()->id,
                'approved_by'               => NULL,
                'approved_date'             => NULL,
            ]);
            DB::commit();

            foreach ($request->item as $item) {
                foreach ($item['variants'] as $variant) {
                    if (!empty($variant['qty']) && $variant['qty'] > 0) {
                        StockTransferChild::create([
                            'transfer_master_id'    => $stock_master->id,
                            'item_varian_id'        => $variant['id_variant'],
                            'qty'                   => $variant['qty'],
                            'entitas_id'            => $variant['entitas'],
                        ]);
                    }
                }
            }
            // if ($stock_master->werehouse_target_id == 1) {
            //     $targetToken    = User::role('gudang')->select('device_token')->first();
            // } else {
            //     $targetToken    = User::where('loc_id', $stock_master->werehouse_target_id)->select('device_token')->first();
            // }
            // $dataNumber     = $stock_master->stock_transfer_number;
            // $idRequestData  = $stock_master->id;
            // $firebase->send(
            //     $targetToken->device_token,
            //     'BUTUH APPROVAL',
            //     '[Stock Transfer] - ' . $dataNumber . ' telah diinput. Dari : ' . namaLokasi($gudang) . ' - Ke : ' . namaLokasi($stock_master->werehouse_target_id) . ' Butuh approval Anda. Lihat pada dashboard Smartwarehouse.',
            //     ['url' => '/stock/transfer/' . $idRequestData . '/detail']
            // );

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function detail(int $id)
    {
        $data           = StockTransferMaster::with('child')->where('id', $id)->first();
        $document       = StockTransferMasterPhoto::where('stock_transfer_m_id', $id)->get();
        return view('pages.stock.transfer.detail', compact('data', 'document'));
    }

    public function edit(int $id)
    {
        $data               = StockTransferMaster::with('child')->where('id', $id)->first();
        $gudang             = $data->werehouse_source_id;
        $pekerjaan          = Project::all();
        $entitas            = Entitas::all();
        $dataGudang         = Outlet::all();

        $items              =
            ItemMaster::with([
                'varian' => function ($q) use ($gudang) {
                    $q->whereHas('stock', function ($q) use ($gudang) {
                        $q->where('lokasi_id', $gudang);
                    })->with([
                        'stock' => function ($q) use ($gudang) {
                            $q->where('lokasi_id', $gudang);
                        }
                    ]);
                }
            ])->whereHas('varian.stock', function ($q) use ($gudang) {
                $q->where('lokasi_id', $gudang);
            })->get();

        $document           = StockTransferMasterPhoto::where('stock_transfer_m_id', $id)->get();
        $dataVarian         = $data->child->pluck('item_varian_id')->toArray();
        $variants           = ItemVarian::whereIn('id', $dataVarian)->with('itemMaster')->get();
        $groupedVariants    = $variants->groupBy('item_master_id');

        // Ambil master yang terlibat
        $itemMasterIds      = ItemVarian::whereIn('id', $dataVarian)->pluck('item_master_id')->unique();
        // Ambil semua master beserta seluruh variannya
        $itemMasters        = ItemMaster::with('varian')->whereIn('id', $itemMasterIds)->get();
        // Mapping qty berdasarkan item_varian_id
        $qtyData            = $data->child->keyBy('item_varian_id');

        return view('pages.stock.transfer.edit', compact('data', 'pekerjaan', 'entitas', 'items', 'itemMasters', 'document', 'qtyData', 'dataGudang'));
    }

    public function update(Request $request, int $id)
    {
        $data   = StockTransferMaster::where('id', $id)->first();
        $gudang = $data->werehouse_source_id;
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->stock_transfer_number    = $input['stock_transfer_number'];
            $data->transfer_srf             = $input['transfer_srf'];
            $data->transfer_date            = $input['transfer_date'];
            $data->werehouse_source_id      = $gudang;
            $data->werehouse_target_id      = $input['werehouse_target_id'];;
            $data->entitas_id               = $input['entitas_id'];
            $data->pekerjaan_id             = $input['pekerjaan_id'];
            $data->note                     = $input['notes'];
            $data->received_by              = $input['received_by'];
            $data->save();
            DB::commit();

            foreach ($request->items as $item) {
                if ($item['qty'] > 0) {
                    StockTransferChild::updateOrCreate(
                        [
                            'transfer_master_id'    => $id,
                            'item_varian_id'        => $item['item_varian_id'],
                        ],
                        [
                            'qty'                   => $item['qty'],
                            'entitas_id'            => $item['entitas'],
                        ]
                    );
                } else {
                    StockTransferChild::where('transfer_master_id', $id)->where('item_varian_id', $item['item_varian_id'])->delete();
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
            $data = StockTransferMaster::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function upload(Request $request, int $id)
    {
        $request->validate([
            'file' => 'required|image|max:5120',
        ]);
        $file       = $request->file('file');
        $filename   = Str::uuid() . '.' . $file->getClientOriginalExtension();
        $path       = $file->storeAs('stock_transfer', $filename, 'public');

        DB::beginTransaction();
        $insertDocument = StockTransferMasterPhoto::create([
            'stock_transfer_m_id'   => $id,
            'filename'              => $filename,
            'sort'                  => 1,
        ]);
        DB::commit();

        return response()->json([
            'success'   => true,
            'filename'  => $filename,
            'path'      => $path,
            'url'       => Storage::url($path)
        ]);
    }

    public function destroy_photo(int $id)
    {
        try {
            $data = StockTransferMasterPhoto::findOrFail($id);
            if ($data->filename && Storage::disk('public')->exists('stock_transfer/' . $data->filename)) {
                Storage::disk('public')->delete('stock_transfer/' . $data->filename);
            }
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function approveTransfer(Request $request, int $id, FirebaseNotificationService $firebase)
    {
        $data               = StockTransferMaster::where('id', $id)->first();
        $gudangAsal         = $data->werehouse_source_id;
        $gudangTarget       = $data->werehouse_target_id;
        $namaGudangAsal     = namaLokasi($gudangAsal);
        $namaGudangTarget   = namaLokasi($gudangTarget);
        try {
            DB::beginTransaction();
            $data->status           = 'Approved';
            $data->approved_date    = date("Y-m-d H:i:s");
            $data->approved_by      = Auth::user()->id;
            $data->save();
            DB::commit();

            // masukin ke stock mutasi, agar dapat ditrack
            $tipe       = 'Transfer';
            if ($gudangAsal == 1) {
                $source     = 'Central';
                $source_id  = $gudangAsal;
            } else {
                $source     = 'Cabang';
                $source_id  = $gudangAsal;
            }

            if ($gudangTarget == 1) {
                $target     = 'Central';
                $target_id  = $gudangTarget;
            } else {
                $target     = 'Cabang';
                $target_id  = $gudangTarget;
            }

            $keterangan     = 'Item transfer dari gudang ' . $namaGudangAsal . ' ke gudang ' . $namaGudangTarget;
            $entitas_target = $data->entitas_id;
            $pekerjaan      = $data->pekerjaan_id;
            $dataChild      = $data->child()->get();
            foreach ($dataChild as $child) {
                $entitas_asal_item = $child->entitas_id;
                storeMutation(
                    $tipe,
                    $pekerjaan,
                    $source,
                    $source_id,
                    $target,
                    $target_id,
                    $child->item_varian_id,
                    $child->qty,
                    $keterangan,
                    $entitas_target,
                    $entitas_asal_item
                );
                // sesudah itu update stocks current (pertama kurangi jumlah stok pada gudang asal)
                $cekStok        = Stock::where('item_varian_id', $child->item_varian_id)
                    ->where('lokasi_id', $gudangAsal)
                    ->where('entitas_id', $entitas_asal_item)
                    ->first();
                $qtyCurrent     = $cekStok->jumlah;
                $qtyBaru        = $qtyCurrent - $child->qty;

                Stock::updateOrCreate(
                    [
                        'item_varian_id'    => $child->item_varian_id,
                        'lokasi_id'         => $gudangAsal,
                        'entitas_id'        => $entitas_asal_item,
                    ],
                    [
                        'jumlah'            => $qtyBaru,
                    ]
                );
                // sesudah itu tambahkan stock pada gudang target
                $cekStokTarget          = Stock::where('item_varian_id', $child->item_varian_id)
                    ->where('lokasi_id', $gudangTarget)
                    ->where('entitas_id', $entitas_target)
                    ->first();
                if ($cekStokTarget != null) {
                    $qtyCurrentTarget       = $cekStokTarget->jumlah;
                    $qtyBaruTarget          = $qtyCurrentTarget - $child->qty;
                } else {
                    $qtyBaruTarget          = $child->qty;
                }
                Stock::updateOrCreate(
                    [
                        'item_varian_id'    => $child->item_varian_id,
                        'lokasi_id'         => $gudangTarget,
                        'entitas_id'        => $entitas_target,
                    ],
                    [
                        'jumlah'            => $qtyBaruTarget,
                    ]
                );
            }
            $des                    = tanggalIndoWaktuLidgkap($data->approved_date) . " by " . $data->approvedBy->firstname . " " . $data->approvedBy->lastname;

            // kirim notif ke keuangan
            $targetToken    = User::role('keuangan')->select('device_token')->first();
            $dataNumber     = $data->stock_transfer_number;
            $idRequestData  = $data->id;
            $firebase->send(
                $targetToken->device_token,
                'STOK TRANSFER',
                '[Stock Transfer] - ' . $dataNumber . ' telah diinput dan terverifikasi oleh tim gudang. Lihat pada dashboard Smartwarehouse.',
                ['url' => '/stock/transfer/' . $idRequestData . '/detail']
            );

            // kirim juga ke source gudang
            $targetToken2    = User::where('loc_id', $data->werehouse_source_id)->select('device_token')->first();
            $firebase->send(
                $targetToken2->device_token,
                'STOK TRANSFER',
                '[Stock Transfer] - ' . $dataNumber . ' telah diinput dan terverifikasi oleh penerima gudang. Lihat pada dashboard Smartwarehouse.',
                ['url' => '/stock/transfer/' . $idRequestData . '/detail']
            );

            return response()->json(['success' => true, 'approve' => $des]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function downloadTandaTerima(int $id, TandaTerimaTransferService $reportService)
    {
        $pdf            = $reportService->generatePdf($id);
        $waktu          = tanggalIndoWaktu(date('Y-m-d H:i:s'));
        $filename       = 'Tanda Terima - ' . $waktu . '.pdf';
        return $pdf->stream($filename);
    }
}
