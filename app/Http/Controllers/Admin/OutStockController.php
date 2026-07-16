<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Entitas;
use App\Models\Project;
use App\Models\ItemMaster;
use App\Models\ItemVarian;
use App\Models\StockOutMaster;
use App\Models\StockOutMasterPhoto;
use App\Models\StockOutChild;
use App\Models\Stock;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class OutStockController extends Controller
{
    public function index(Request $request)
    {
        $gudang     = Auth::user()->loc_id;
        $pekerjaan  = Project::all();
        $entitas    = Entitas::all();

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

        $data       = StockOutMaster::with('child');

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    if ($row->status == "Pending") {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stockout.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('stockout.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                    } else {
                        return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Detail" href="' . route('stockout.detail', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-eye f-20"></i></a>
                                </li>
                            </ul>';
                    }
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->addColumn('so_number', function ($row) {
                    return "<code>" . $row->stock_out_number . "</code>";
                })
                ->addColumn('date', function ($row) {
                    return tanggalIndo($row->out_date);
                })
                ->addColumn('werehouse', function ($row) {
                    return $row->gudang->nama;
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
                        return '<span class="badge bg-light-dark">Pending</span>';
                    } else {
                        return '<span class="badge bg-light-success">Approval</span>';
                    }
                })
                ->rawColumns(['action', 'updated_at', 'so_number', 'date', 'werehouse', 'entitas', 'status'])
                ->make(true);
        }
        return view('pages.stock.out.index', compact('gudang', 'pekerjaan', 'items', 'entitas', 'stockav'));
    }

    public function store(Request $request)
    {
        $gudang     = Auth::user()->loc_id;
        $input      = $request->all();
        try {
            DB::beginTransaction();
            $stock_master = StockOutMaster::create([
                'stock_out_number'  => $input['stock_out_number'],
                'out_date'          => $input['out_date'],
                'werehouse_id'      => $gudang,
                'entitas_id'        => $input['entitas_id'],
                'pekerjaan_id'      => $input['pekerjaan_id'],
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
                        StockOutChild::create([
                            'out_master_id'     => $stock_master->id,
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
        $data           = StockOutMaster::with('child')->where('id', $id)->first();
        $document       = StockOutMasterPhoto::where('stock_out_m_id', $id)->get();
        return view('pages.stock.out.detail', compact('data', 'document'));
    }

    public function edit(int $id)
    {
        $data               = StockOutMaster::with('child')->where('id', $id)->first();
        $gudang             = $data->werehouse_id;
        $pekerjaan          = Project::all();
        $entitas            = Entitas::all();

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

        $document           = StockOutMasterPhoto::where('stock_out_m_id', $id)->get();
        $dataVarian         = $data->child->pluck('item_varian_id')->toArray();
        $variants           = ItemVarian::whereIn('id', $dataVarian)->with('itemMaster')->get();
        $groupedVariants    = $variants->groupBy('item_master_id');

        // Ambil master yang terlibat
        $itemMasterIds      = ItemVarian::whereIn('id', $dataVarian)->pluck('item_master_id')->unique();
        // Ambil semua master beserta seluruh variannya
        $itemMasters        = ItemMaster::with('varian')->whereIn('id', $itemMasterIds)->get();
        // Mapping qty berdasarkan item_varian_id
        $qtyData            = $data->child->keyBy('item_varian_id');

        return view('pages.stock.out.edit', compact('data', 'pekerjaan', 'entitas', 'items', 'itemMasters', 'document', 'qtyData'));
    }

    public function update(Request $request, int $id)
    {
        $data   = StockOutMaster::where('id', $id)->first();
        $gudang = $data->werehouse_id;
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->stock_out_number = $input['stock_out_number'];
            $data->out_date         = $input['out_date'];
            $data->werehouse_id     = $gudang;
            $data->entitas_id       = $input['entitas_id'];
            $data->pekerjaan_id     = $input['pekerjaan_id'];
            $data->note             = $input['notes'];
            $data->save();
            DB::commit();

            foreach ($request->items as $item) {
                if ($item['qty'] > 0) {
                    StockOutChild::updateOrCreate(
                        [
                            'out_master_id'     => $id,
                            'item_varian_id'    => $item['item_varian_id'],
                        ],
                        ['qty'                  => $item['qty']]
                    );
                } else {
                    StockOutChild::where('out_master_id', $id)->where('item_varian_id', $item['item_varian_id'])->delete();
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
            $data = StockOutMaster::findOrFail($id);
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
        $path       = $file->storeAs('stock_out', $filename, 'public');

        DB::beginTransaction();
        $insertDocument = StockOutMasterPhoto::create([
            'stock_out_m_id'    => $id,
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
            $data = StockOutMasterPhoto::findOrFail($id);
            if ($data->filename && Storage::disk('public')->exists('stock_out/' . $data->filename)) {
                Storage::disk('public')->delete('stock_out/' . $data->filename);
            }
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function approveOut(Request $request, int $id)
    {
        $data   = StockOutMaster::where('id', $id)->first();
        $gudang = $data->werehouse_id;
        $namaGudang = namaLokasi($gudang);
        try {
            DB::beginTransaction();
            $data->status           = 'Approved';
            $data->approved_date    = date("Y-m-d H:i:s");
            $data->approved_by      = Auth::user()->id;
            $data->save();
            DB::commit();

            // masukin ke stock mutasi, agar dapat ditrack
            $tipe       = 'Keluar';
            if ($gudang == 1) {
                $source     = 'Central';
                $source_id  = $gudang;
            } else {
                $source     = 'Cabang';
                $source_id  = $gudang;
            }
            $target     = 'External';
            $target_id  = NULL;
            $keterangan = 'Item keluar habis pakai dari gudang ' . $namaGudang . ' ke External';
            $entitas    = $data->entitas_id;
            $pekerjaan  = $data->pekerjaan_id;
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
                $cekStok        = Stock::where('item_varian_id', $child->item_varian_id)->where('lokasi_id', $gudang)->first();
                $qtyCurrent     = $cekStok->jumlah;
                $qtyBaru        = $qtyCurrent - $child->qty;
                Stock::updateOrCreate(
                    [
                        'item_varian_id'    => $child->item_varian_id,
                        'lokasi_id'         => $gudang,
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
