<?php

namespace App\Http\Controllers\Admin;

use App\Models\Project;
use App\Models\Entitas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use DataTables;
use DB;

class AdmProjectController extends Controller
{
    public function index(Request $request)
    {
        $entitas    = Entitas::all();
        $data       = Project::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('project.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->addColumn('no_kontrak', function ($row) {
                    return '<code>' . $row->no_kontrak . '</code>';
                })->addColumn('jangka_waktu', function ($row) {
                    return $row->jangka_waktu . ' months';
                })->rawColumns(['action', 'updated_at', 'no_kontrak', 'jangka_waktu'])
                ->make(true);
        }
        return view('pages.project.index', compact('entitas'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $input = $request->all();
        try {
            DB::beginTransaction();
            Project::create([
                'name'                  => $input['name'],
                'entitas_id'            => $input['entitas_id'],
                'no_kontrak'            => $input['no_kontrak'],
                'jangka_waktu'          => $input['jangka_waktu'],
                'date_join'             => $input['date_join'],
                'status'                => $input['status'],
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
        $entitas    = Entitas::all();
        $data       = Project::where('id', $id)->first();
        try {
            return view('pages.project.edit', compact('data', 'entitas'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data   = Project::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->name              = $input['name'];
            $data->entitas_id        = $input['entitas_id'];
            $data->no_kontrak        = $input['no_kontrak'];
            $data->jangka_waktu      = $input['jangka_waktu'];
            $data->date_join         = $input['date_join'];
            $data->status            = $input['status'];
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
            $data = Project::findOrFail($id);
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
