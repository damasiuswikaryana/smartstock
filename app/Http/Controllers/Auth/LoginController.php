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
            'username' => 'required',
            'password' => 'required',
        ]);

        $user                   = User::where('username', $request->username)->first();
        $credentials            = $request->only('username', 'password');

        if (Auth::attempt($credentials) && $user->roles[0]->name == "masteradmin") {
            return redirect()->intended('/')->withSuccess('Welcome');
        } elseif (Auth::attempt($credentials) && $user->roles[0]->name == "admin") {
            return redirect()->intended('/')->withSuccess('Welcome Admin');
        } elseif (Auth::attempt($credentials) && $user->roles[0]->name == "admin_cabang") {
            return redirect()->intended('/')->withSuccess('Welcome Branch Admin');
        } elseif (Auth::attempt($credentials) && $user->roles[0]->name == "admin") {
            return redirect()->intended('/')->withSuccess('Welcome Keuangan');
        } else {
            return redirect("login")->with('error', 'Wrong username or password.');
        }

        // return redirect("login")->with('error', 'Akun tidak terdaftar!');
    }

    public function logout()
    {
        Session::flush();
        Auth::logout();
        return Redirect('login');
    }
}
