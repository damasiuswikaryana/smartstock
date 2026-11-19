<?php

namespace App\Http\Controllers\Admin;

use App\Models\Entitas;
use App\Models\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use DataTables;
use DB;

class AdmEntitasController extends Controller
{
    public function index(Request $request)
    {
        $data       = Entitas::all();
        $directors  = User::role('director')->get();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('entitas.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('updated_at', function ($row) {
                    return tanggalIndoWaktuLidgkap($row->updated_at);
                })->rawColumns(['action', 'updated_at'])
                ->make(true);
        }
        return view('pages.entitas.index', compact('directors'));
    }

    private function validateInput(Request $request): array
    {
        return $request->validate([
            'name'     => 'required|string|max:100',
            'alamat'   => 'required|string',
            'company'  => 'required|string|max:100',
            'email'    => 'nullable|email|max:100',
            'phone'    => 'nullable|string|max:20',
            'director' => 'required|exists:users,id',
        ], [], [
            'name'     => 'Entity Name',
            'alamat'   => 'Address',
            'company'  => 'Company Name',
            'director' => 'Director',
        ]);
    }

    private function mapInput(array $input): array
    {
        return [
            'entitas_name'    => $input['name'],
            'entitas_alamat'  => $input['alamat'],
            'entitas_company' => $input['company'],
            'entitas_email'   => $input['email'] ?? null,
            'entitas_phone'   => $input['phone'] ?? null,
            'director_id'     => $input['director'],
        ];
    }

    public function store(Request $request)
    {
        try {
            $input = $this->validateInput($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => implode(' ', $e->validator->errors()->all())]);
        }

        try {
            DB::beginTransaction();
            // entitas_global NOT NULL tanpa default; entitas baru bukan entitas global
            Entitas::create($this->mapInput($input) + ['entitas_global' => 0]);
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $data       = Entitas::findOrFail($id);
        $directors  = User::role('director')->get();

        try {
            return view('pages.entitas.edit', compact('data', 'directors'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data = Entitas::find($id);
        if (!$data) {
            return response()->json(['success' => false, 'message' => 'Data not found']);
        }

        try {
            $input = $this->validateInput($request);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['success' => false, 'message' => implode(' ', $e->validator->errors()->all())]);
        }

        try {
            DB::beginTransaction();
            $data->update($this->mapInput($input));
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

    public function storeLogo(Request $request, int $id)
    {
        try {
            $data = Entitas::where('id', $id)->first();
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

            if ($data->entitas_logo && Storage::disk('public')->exists('entitas/' . $data->entitas_logo)) {
                Storage::disk('public')->delete('entitas/' . $data->entitas_logo);
            }

            $path       = $file->store('entitas', 'public');
            $filename   = basename($path);
            $data->entitas_logo   = $filename;
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
