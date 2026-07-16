<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\ItemMaster;
use App\Models\ItemVarian;
use App\Models\Vendor;
use App\Models\StockInMaster;
use App\Models\StockInMasterPhoto;
use App\Models\StockInChild;
use App\Models\Stock;
use App\Models\Outlet;
use App\Models\Project;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class InOutStockController extends Controller
{
    public function index(Request $request)
    {
        $vendor     = Vendor::all();
        $entitas    = Entitas::all();
        $data       = StockInMaster::with('child');
        $items      = ItemMaster::all();
        $pekerjaan  = Project::all();
        $gudang     = Outlet::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == "Pending") {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stockin.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('stockin.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                    } else {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stockin.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                    }
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('si_number', function ($row) {
                    return "<code>" . $row->stock_in_number . "</code>";
                })
                ->addColumn('werehouse', function ($row) {
                    return $row->gudang->nama;
                })
                ->addColumn('entitas', function ($row) {
                    return '<p class="fw-bold mb-0">' . $row->pekerjaan->name . '</p>
                        <p class="f-10 mb-0">' . $row->entitas->entitas_name . '</p>';
                })
                ->addColumn('date', function ($row) {
                    return tanggalIndo($row->in_date);
                })
                ->addColumn('vendor', function ($row) {
                    return $row->vendor->nama;
                })
                ->addColumn('po_number', function ($row) {
                    return $row->po_number;
                })
                ->addColumn('items', function ($row) {
                    return $row->child->count();
                })
                ->addColumn('status', function ($row) {
                    if ($row->status == "Pending") {
                        return '<span class="badge bg-light-dark">Pending</span>';
                    } else {
                        return '<span class="badge bg-light-success">Approval</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'si_number', 'entitas', 'date', 'werehouse', 'vendor', 'po_number', 'status'])
                ->make(true);
        }
        return view('pages.stock.in.index', compact('vendor', 'items', 'entitas', 'pekerjaan', 'gudang'));
    }

    public function store(Request $request)
    {
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $stock_master = StockInMaster::create([
                'stock_in_number'   => $input['stock_in_number'],
                'in_date'           => $input['in_date'],
                'vendor_id'         => $input['vendor_id'],
                'entitas_id'        => $input['entitas_id'],
                'werehouse_id'      => $input['werehouse_id'],
                'pekerjaan_id'      => $input['pekerjaan_id'],
                'po_number'         => $input['po_number'],
                'note'              => $input['notes'],
                'status'            => "Pending",
                'created_by'        => Auth::user()->id,
                'approved_by'       => NULL,
                'approved_date'     => NULL,
            ]);
            DB::commit();

            foreach ($request->item as $item) {
                foreach ($item['variants'] as $variant) {
                    if (!empty($variant['qty']) && $variant['qty'] > 0) {
                        StockInChild::create([
                            // 'id_item'           => $item['id_item'],
                            'in_master_id'      => $stock_master->id,
                            'item_varian_id'    => $variant['id_variant'],
                            'qty'               => $variant['qty'],
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
        $data           = StockInMaster::with('child')->where('id', $id)->first();
        $document       = StockInMasterPhoto::where('stock_in_m_id', $id)->get();
        return view('pages.stock.in.detail', compact('data', 'document'));
    }

    public function edit(int $id)
    {
        $vendor         = Vendor::all();
        $entitas        = Entitas::all();
        $pekerjaan      = Project::all();
        $gudang         = Outlet::all();
        $items          = ItemMaster::all();
        $data           = StockInMaster::with('child')->where('id', $id)->first();
        $document       = StockInMasterPhoto::where('stock_in_m_id', $id)->get();
        $dataVarian     = $data->child->pluck('item_varian_id')->toArray();

        $variants           = ItemVarian::whereIn('id', $dataVarian)->with('itemMaster')->get();
        $groupedVariants    = $variants->groupBy('item_master_id');

        // Ambil master yang terlibat
        $itemMasterIds  = ItemVarian::whereIn('id', $dataVarian)->pluck('item_master_id')->unique();
        // Ambil semua master beserta seluruh variannya
        $itemMasters    = ItemMaster::with('varian')->whereIn('id', $itemMasterIds)->get();
        // Mapping qty berdasarkan item_varian_id
        $qtyData        = $data->child->keyBy('item_varian_id');

        return view('pages.stock.in.edit', compact('data', 'vendor', 'entitas', 'items', 'itemMasters', 'document', 'qtyData', 'pekerjaan', 'gudang'));
    }

    public function update(Request $request, int $id)
    {
        $data   = StockInMaster::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->stock_in_number  = $input['stock_in_number'];
            $data->in_date          = $input['in_date'];
            $data->vendor_id        = $input['vendor_id'];
            $data->entitas_id       = $input['entitas_id'];
            $data->werehouse_id     = $input['werehouse_id'];
            $data->pekerjaan_id     = $input['pekerjaan_id'];
            $data->po_number        = $input['po_number'];
            $data->note             = $input['notes'];
            $data->save();
            DB::commit();

            foreach ($request->items as $item) {
                if ($item['qty'] > 0) {
                    StockInChild::updateOrCreate(
                        [
                            'in_master_id'    => $id,
                            'item_varian_id'  => $item['item_varian_id'],
                        ],
                        ['qty'             => $item['qty']]
                    );
                } else {
                    StockInChild::where('in_master_id', $id)
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
            $data = StockInMaster::findOrFail($id);
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
        $path       = $file->storeAs('stock_in', $filename, 'public');

        DB::beginTransaction();
        $insertDocument = StockInMasterPhoto::create([
            'stock_in_m_id'     => $id,
            'filename'          => $filename,
            'sort'              => 1,
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
            $data = StockInMasterPhoto::findOrFail($id);
            if ($data->filename && Storage::disk('public')->exists('stock_in/' . $data->filename)) {
                Storage::disk('public')->delete('stock_in/' . $data->filename);
            }
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function approveIn(Request $request, int $id)
    {
        $data   = StockInMaster::where('id', $id)->first();
        try {
            DB::beginTransaction();
            $data->status           = 'Approved';
            $data->approved_date    = date("Y-m-d H:i:s");
            $data->approved_by      = Auth::user()->id;
            $data->save();
            DB::commit();

            // masukin ke stock mutasi, agar dapat ditrack
            $namaGudang = namaLokasi($data->werehouse_id);
            $tipe       = 'Masuk';
            $pekerjaan  = $data->pekerjaan_id;
            $source     = 'External';
            $source_id  = NULL;
            if ($data->werehouse_id == 1) {
                $target = "Central";
            } else {
                $target = "Cabang";
            }
            $target_id  = $data->werehouse_id;
            $keterangan = 'Item masuk dari external ke ' . $namaGudang;
            $entitas    = $data->entitas_id;
            $dataChild  = $data->child()->get();
            foreach ($dataChild as $child) {
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
                    $entitas
                );
                // sesudah itu update stocks current
                $cekStok = Stock::where('item_varian_id', $child->item_varian_id)
                    ->where('lokasi_id', Auth::user()->lokasi->id)
                    ->where('entitas_id', $entitas)->first();
                if ($cekStok == null) {
                    $qtyBaru        = $child->qty;
                } else {
                    $qtyCurrent     = $cekStok->jumlah;
                    $qtyBaru        = $qtyCurrent + $child->qty;
                }
                Stock::updateOrCreate(
                    [
                        'item_varian_id'    => $child->item_varian_id,
                        'lokasi_id'         => Auth::user()->lokasi->id,
                        'entitas_id'        => $entitas,
                    ],
                    [
                        'jumlah'            => $qtyBaru,
                    ]
                );
            }
            $des                    = tanggalIndoWaktuLidgkap($data->approved_date) . " by " . $data->approvedBy->firstname . " " . $data->approvedBy->lastname;
            return response()->json(['success' => true, 'approve' => $des]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
