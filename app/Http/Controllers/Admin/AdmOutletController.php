<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Outlet;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AdmOutletController extends Controller
{
    public function index(Request $request)
    {
        $data = Outlet::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('outlet.ubah', $row->id) . '" class="avtar avtar-s btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-s btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })
                ->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })
                ->rawColumns(['action', 'updated_at'])
                ->make(true);
        }
        return view('pages.outlet.index');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            DB::beginTransaction();
            Outlet::create([
                'nama'      => $input['nama'],
                'alamat'    => $input['alamat'],
                'kabupaten' => $input['kabupaten'],
                'provinsi'  => $input['provinsi'],
                'lat'       => $input['lat'],
                'long'      => $input['long'],
                // 'kapasitas' => (int) $input['kapasitas'],
            ]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {

            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function show(int $id)
    {
        $data = Outlet::where('id', $id)->first();
        try {
            return view('pages.outlet.detail', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function edit(int $id)
    {
        $data = Outlet::where('id', $id)->first();
        try {
            return view('pages.outlet.edit', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data = Outlet::where('id', $id)->first();
        $input = $request->all();
        try {
            DB::beginTransaction();
            $data->nama      = $input['nama'];
            $data->alamat    = $input['alamat'];
            $data->kabupaten = $input['kabupaten'];
            $data->provinsi  = $input['provinsi'];
            $data->lat       = $input['lat'];
            $data->long      = $input['long'];
            $data->save();
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $data = Outlet::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
