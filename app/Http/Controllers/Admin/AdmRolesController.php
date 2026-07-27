<?php

namespace App\Http\Controllers\Admin;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class AdmRolesController extends Controller
{
    public function index(Request $req)
    {
        $roles      = Role::all();
        $role_name  = 'admin';
        if ($req->has('role_name')) {
            $role_name = $req->input('role_name');
        }
        $this_role  = Role::findByName($role_name);
        return view('pages.roles.index', compact('this_role', 'roles', 'role_name'));
    }

    public function rolesUpdate(Request $request, $role_name)
    {
        $role = Role::findByName($role_name);
        try {
            DB::beginTransaction();
            if ($request->has('menu')) {
                $role->syncPermissions($request->input('menu'));
            }
            DB::commit();
            return response()->json(['success' => true]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Gagal: " . $th->getMessage()]);
        }
    }
}
