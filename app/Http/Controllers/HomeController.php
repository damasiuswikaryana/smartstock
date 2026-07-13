<?php

namespace App\Http\Controllers;

use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class HomeController extends Controller
{
    public function dashboard(Request $request)
    {
        if (Auth::check()) {
            return view('home', []);
        }
        return redirect("login")->withSuccess('Oops! You do not have access');
    }

    public function profile()
    {
        $id_user    = Auth::user()->id;
        $data       = User::where('id', $id_user)->first();

        return view('pages.profile.index', [
            'data' => $data,
        ]);
    }

    public function updateProfile(Request $request)
    {
        $id_user    = Auth::user()->id;
        $data       = User::where('id', $id_user)->first();
        $input      = $request->all();
        try {
            DB::beginTransaction();
            $data->firstname        = $input['firstname'];
            $data->lastname         = $input['lastname'];
            $data->save();

            DB::commit();
            return response()->json(['success' => true, 'message' => "Profile updated"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function updatePassword(Request $request)
    {
        $id_user    = Auth::user()->id;
        $data       = User::where('id', $id_user)->first();
        $input      = $request->all();

        // Validasi input
        $validator = Validator::make($request->all(), [
            'old_password'      => 'required',
            'new_password'      => 'required|min:6|confirmed',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
            ]);
        }
        // Cek apakah old password sesuai
        if (!Hash::check($request->old_password, $data->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Wrong old password',
            ]);
        }
        try {
            DB::beginTransaction();
            $data->password         = Hash::make($request->new_password);
            $data->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => "New password updated"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }

    public function storeFoto(Request $request, int $idUser)
    {
        try {
            $data = User::where('id', $idUser)->first();
            DB::beginTransaction();

            if (!$request->hasFile('file')) {
                return response()->json([
                    'error' => 'File not found in request.'
                ], 400);
            }

            $file = $request->file('file');

            // Validasi tambahan opsional
            if (!$file->isValid()) {
                return response()->json([
                    'error' => 'File not valid.'
                ], 422);
            }

            $path           = $file->store('user', 'public');
            $filename       = basename($path);
            $data->photo    = $filename;
            $data->save();
            DB::commit();

            return response()->json([
                'url'  => Storage::url($path),
                'path' => $path,
            ]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'error' => 'Error Upload: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function change_mode(Request $request)
    {
        $input      = $request->all();
        $id_user    = Auth::user()->id;
        $data       = User::where('id', $id_user)->first();
        try {
            DB::beginTransaction();
            $data->mode_style  = $input['mode_style'];
            $data->save();
            DB::commit();
            return response()->json(['success' => true, 'message' => "Changed to " . $input['mode_style'] . " mode"]);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json(['success' => false, 'message' => "Error: " . $th->getMessage()]);
        }
    }
}
