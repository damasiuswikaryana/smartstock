<?php

namespace App\Http\Controllers\Admin;

use App\Models\Entitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AdmEntitasController extends Controller
{
    public function index(Request $request)
    {
        $data   = Entitas::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('entitas.ubah', $row->id) . '" class="avtar avtar-s btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-s btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->rawColumns(['action', 'updated_at'])
                ->make(true);
        }
        return view('pages.entitas.index');
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            DB::beginTransaction();
            Entitas::create([
                'entitas_name'     => $input['name'],
                'entitas_alamat'   => $input['alamat'],
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
        $data   = Entitas::where('id', $id)->first();
        try {
            return view('pages.entitas.edit', compact('data'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data   = Entitas::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->entitas_name       = $input['name'];
            $data->entitas_alamat     = $input['alamat'];
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
            $data = Entitas::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
