<?php

namespace App\Http\Controllers\Admin;

use App\Models\Vendor;
use App\Models\Category;
use App\Models\ItemMaster;
use App\Models\ItemVarian;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use DB;

class AdmItemController extends Controller
{
    // ITEM MASTER CONTROLLER HERE
    public function index(Request $request)
    {
        $category   = Category::all();
        $vendor     = Vendor::all();
        $data       = ItemMaster::with(['vendor', 'category']);

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-placement="top" title="Edit" href="' . route('item.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->addColumn('kode', function ($row) {
                    return '<span class="badge bg-light-secondary">' . $row->kode . '</span>';
                })->addColumn('vendor', function ($row) {
                    return $row->vendor->nama;
                })->addColumn('category', function ($row) {
                    return '<span class="badge bg-light-secondary">' . $row->category->title . '</span>';
                })->rawColumns(['action', 'updated_at', 'kode', 'vendor', 'category'])
                ->make(true);
        }
        return view('pages.item.index', compact('category', 'vendor'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            DB::beginTransaction();
            ItemMaster::create([
                'kode'          => $input['kode'],
                'nama'          => $input['nama'],
                'vendor_id'     => $input['vendor_id'],
                'category_id'   => $input['category_id'],
                'catatan'       => $input['catatan'],
                'deskripsi'     => $input['description'],
            ]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function edit(int $id)
    {
        $category   = Category::all();
        $vendor     = Vendor::all();
        $data       = ItemMaster::where('id', $id)->first();
        try {
            return view('pages.item.edit', compact('data', 'category', 'vendor'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }
    public function update(Request $request, int $id)
    {
        $data   = ItemMaster::where('id', $id)->first();
        $input  = $request->all();
        try {
            if ($input['tipe'] == "general") {
                DB::beginTransaction();
                $data->kode         = $input['kode'];
                $data->nama         = $input['nama'];
                $data->vendor_id    = $input['vendor_id'];
                $data->category_id  = $input['category_id'];
                $data->save();
                DB::commit();
                return response()->json(['success' => true]);
            } else {
                DB::beginTransaction();
                $data->catatan      = $input['catatan'];
                $data->deskripsi    = $input['description'];
                $data->save();
                DB::commit();
                return response()->json(['success' => true]);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function destroy(int $id)
    {
        try {
            $data = ItemMaster::findOrFail($id);

            if ($data->image_master_1 && Storage::disk('public')->exists('item/' . $data->image_master_1)) {
                Storage::disk('public')->delete('item/' . $data->image_master_1);
            }
            if ($data->image_master_2 && Storage::disk('public')->exists('item/' . $data->image_master_2)) {
                Storage::disk('public')->delete('item/' . $data->image_master_2);
            }
            if ($data->image_master_3 && Storage::disk('public')->exists('item/' . $data->image_master_3)) {
                Storage::disk('public')->delete('item/' . $data->image_master_3);
            }

            $data->varian()->delete();
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function storeFoto(Request $request, int $id)
    {
        try {
            $data = ItemMaster::where('id', $id)->first();
            DB::beginTransaction();

            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => 'File tidak ditemukan dalam request.'
                ], 400);
            }

            $file = $request->file('file');

            // Validasi tambahan opsional
            if (!$file->isValid()) {
                return response()->json([
                    'error' => 'File tidak valid.'
                ], 422);
            }

            if ($data->image_master_1 && Storage::disk('public')->exists('item/' . $data->image_master_1)) {
                Storage::disk('public')->delete('item/' . $data->image_master_1);
            }
            if ($data->image_master_2 && Storage::disk('public')->exists('item/' . $data->image_master_2)) {
                Storage::disk('public')->delete('item/' . $data->image_master_2);
            }
            if ($data->image_master_3 && Storage::disk('public')->exists('item/' . $data->image_master_3)) {
                Storage::disk('public')->delete('item/' . $data->image_master_3);
            }

            $path       = $file->store('item', 'public');
            $filename   = basename($path);

            $data->image_master_1   = $filename;
            $data->save();
            DB::commit();

            return response()->json([
                'url'  => Storage::url($path),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Upload Error: ' . $e->getMessage(),
            ], 500);
        }
    }


    // ITEM VARIAN CONTROLLER HERE
    public function varian_index(Request $request, int $id)
    {
        $data       = ItemVarian::where('item_master_id', $id)->get();
        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('item_variant.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->addColumn('kode_varian', function ($row) {
                    return '<span class="badge bg-light-secondary">' . $row->kode_varian . '</span>';
                })->addColumn('nilai', function ($row) {
                    return rupiah($row->nilai);
                })->rawColumns(['action', 'updated_at', 'kode_varian', 'nilai'])
                ->make(true);
        }
    }
    public function varian_store(Request $request, int $id)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            ItemVarian::create([
                'item_master_id'       => $id,
                'kode_varian'          => $input['kode_varian'],
                'sku_varian'            => $input['sku_varian'],
                'name_varian'          => $input['name_varian'],
                'nilai'                => hapusTitikAngka($input['nilai']),
            ]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function varian_edit(int $id)
    {
        $data           = ItemVarian::where('id', $id)->first();
        try {
            return view('pages.item.edit_variant', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }
    public function varian_update(Request $request, int $id)
    {
        $data   = ItemVarian::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->kode_varian      = $input['kode_varian'];
            $data->sku_varian       = $input['sku_varian'];
            $data->name_varian      = $input['name_varian'];
            $data->nilai            = hapusTitikAngka($input['nilai']);
            $data->save();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function varian_destroy(int $id)
    {
        try {
            $data = ItemVarian::findOrFail($id);

            if ($data->image_varian && Storage::disk('public')->exists('item/' . $data->image_varian)) {
                Storage::disk('public')->delete('item/' . $data->image_varian);
            }

            $data->delete();

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
    public function storeFotoVariant(Request $request, int $id)
    {
        try {
            $data = ItemVarian::where('id', $id)->first();
            DB::beginTransaction();

            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => 'File tidak ditemukan dalam request.'
                ], 400);
            }

            $file = $request->file('file');

            // Validasi tambahan opsional
            if (!$file->isValid()) {
                return response()->json([
                    'error' => 'File tidak valid.'
                ], 422);
            }

            if ($data->image_varian && Storage::disk('public')->exists('item/' . $data->image_varian)) {
                Storage::disk('public')->delete('item/' . $data->image_varian);
            }

            $path       = $file->store('variant', 'public');
            $filename   = basename($path);

            $data->image_varian   = $filename;
            $data->save();
            DB::commit();

            return response()->json([
                'url'  => Storage::url($path),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Upload Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
