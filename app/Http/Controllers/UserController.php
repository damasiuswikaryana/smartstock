<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Outlet;

use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $outlet     = Outlet::all();
        $roles      = Role::all();
        // $data       = User::whereHas('roles', function ($query) {
        //     return $query->where('name', '!=', 'masteradmin');
        // });
        $data       = User::all();

        if ($request->ajax()) {
            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    return '<ul class="list-inline mb-0">
                                <li class="list-inline-item">
                                    <a data-bs-toggle="modal" data-bs-target="#modalEdit" data-bs-placement="top" title="Edit" href="' . route('user.ubah', $row->id) . '" class="avtar avtar-xs btn-link-success btn-pc-default btn-edit"><i class="ti ti-edit f-20"></i></a>
                                </li>
                                <li class="list-inline-item">
                                    <a data-bs-toggle="tooltip" data-bs-placement="top" data-bs-original-title="Delete" href="#" class="avtar avtar-xs btn-link-danger btn-pc-default btn-delete" data-id="' . $row->id . '" type="submit"><i class="ti ti-trash f-20"></i></a>
                                </li>
                            </ul>';
                })->addColumn('user', function ($row) {
                    return '<div class="d-flex align-items-center">
                            <div class="flex-shrink-0">
                              <img src="../assets/images/user/avatar-1.jpg" alt="user image" class="img-radius wid-40">
                            </div>
                            <div class="flex-grow-1 ms-3">
                              <h6 class="mb-0">' . $row->firstname . ' ' . $row->lastname . '</h6>
                              <p class="mb-0">' . $row->emp_id . '</p>
                            </div>
                          </div>';
                })->addColumn('werehouse', function ($row) {
                    return $row->lokasi->nama;
                })->addColumn('phone', function ($row) {
                    return $row->phone;
                })->addColumn('username', function ($row) {
                    return '<code>' . $row->username . '</code>';
                })->addColumn('status', function ($row) {
                    if ($row->status == 'Active') {
                        return '<span class="badge bg-light-success">Active</span>';
                    } else {
                        return '<span class="badge bg-light-danger">Inactive</span>';
                    }
                })->rawColumns(['action', 'user', 'werehouse', 'phone', 'username', 'status'])
                ->make(true);
        }
        return view('pages.user.index', compact('outlet', 'roles'));
    }

    public function store(Request $request)
    {
        $input = $request->all();
        try {
            DB::beginTransaction();
            $user = User::create([
                'emp_id'        => $input['emp_id'],
                'firstname'     => $input['firstname'],
                'lastname'      => $input['lastname'],
                'email'         => $input['email'],
                'phone'         => $input['phone'],
                'is_admin'      => 0,
                'username'      => $input['username'],
                'password'      => Hash::make("2026AstaStock"),
                'loc_id'        => $input['loc_id'],
                'status'        => 'Active',
                'photo'         => NULL,
            ]);
            DB::commit();
            $role = Role::findOrFail($input['roles']);
            $user->assignRole($role);

            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function edit(int $id)
    {
        $outlet     = Outlet::all();
        $roles      = Role::all();
        $data       = User::where('id', $id)->first();

        try {
            return view('pages.user.edit', compact('data', 'outlet', 'roles'));
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', "Error: " . $th->getMessage());
        }
    }

    public function update(Request $request, int $id)
    {
        $data   = User::where('id', $id)->first();
        $input  = $request->all();
        try {
            DB::beginTransaction();
            $data->emp_id       = $input['emp_id'];
            $data->firstname    = $input['firstname'];
            $data->lastname     = $input['lastname'];
            $data->email        = $input['email'];
            $data->phone        = $input['phone'];
            $data->username     = $input['username'];
            $data->loc_id       = $input['loc_id'];
            $data->status       = $input['status'];
            $data->save();
            DB::commit();
            $role = Role::findOrFail($input['roles']);
            $data->syncRoles($role);
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function destroy(int $id)
    {
        try {
            $data = User::findOrFail($id);
            if ($data->photo && Storage::disk('public')->exists('user/' . $data->photo)) {
                Storage::disk('public')->delete('user/' . $data->photo);
            }
            $data->delete();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function storeFoto(Request $request, int $id)
    {
        try {
            $data = User::where('id', $id)->first();
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

            if ($data->photo && Storage::disk('public')->exists('user/' . $data->photo)) {
                Storage::disk('public')->delete('user/' . $data->photo);
            }

            $path       = $file->store('user', 'public');
            $filename   = basename($path);

            $data->photo   = $filename;
            $data->save();
            DB::commit();

            return response()->json([
                'url'  => Storage::url($path),
                'path' => $path,
                'filename' => $filename,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Upload Error: ' . $e->getMessage(),
            ], 500);
        }
    }
}
