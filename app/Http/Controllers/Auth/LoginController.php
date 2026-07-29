<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Gerobak;
use Jenssegers\Agent\Agent;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class LoginController extends Controller
{
    public function index()
    {
        $agent = new Agent();
        if ($agent->isDesktop()) {
            return view('auth.login');
        } else {
            return view('auth.login');
        }
    }

    public function postLogin(Request $request)
    {
        $request->validate([
            'username'  => ['required', 'string'],
            'password'  => ['required', 'string'],
        ]);

        $credentials = [
            'username' => $request->username,
            'password' => $request->password,
            'status'   => 'Active',
        ];

        if (!Auth::attempt($credentials, $request->boolean('remember'))) {
            return response()->json([
                'status'  => false,
                'message' => 'Wrong username or password.',
            ], 401);
        }

        // Hindari Session Fixation
        $request->session()->regenerate();
        /** @var \App\Models\User $user */
        $user = Auth::user();

        if ($user->status != "Active") {
            Auth::logout();
            return response()->json([
                'status' => false,
                'message' => 'Your account has been deactivated.'
            ], 403);
        }

        // Pastikan memiliki role yang diizinkan
        if (!$user->hasAnyRole([
            'masteradmin',
            'admin',
            'admin_cabang',
            'pengadaan',
            'gudang',
        ])) {
            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return response()->json([
                'status'  => false,
                'message' => 'Unauthorized access.',
            ], 403);
        }

        // Pesan berdasarkan role
        if ($user->hasRole('masteradmin')) {
            $message = 'Success Login as Master Admin';
        } elseif ($user->hasRole('admin')) {
            $message = 'Success Login as Admin';
        } elseif ($user->hasRole('admin_cabang')) {
            $message = 'Success Login as Branch Admin';
        } elseif ($user->hasRole('pengadaan')) {
            $message = 'Success Login as Procurement';
        } elseif ($user->hasRole('gudang')) {
            $message = 'Success Login as Warehouse';
        } else {
            $message = 'Welcome guest';
        }

        return response()->json([
            'status'   => true,
            'message'  => $message,
            'redirect' => route('dashboard'),
        ]);
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
