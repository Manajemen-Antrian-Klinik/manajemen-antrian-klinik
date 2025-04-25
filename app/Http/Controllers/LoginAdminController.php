<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginAdminController extends Controller
{
    public function index()
    {
        return view("login.loginAdmin", [
            "title" => "Login Admin",
        ]);
    }

    public function autentificate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required'
        ]);
    
        // Cek apakah user ada dan adalah admin
        $user = User::where('email', $request->email)->first();
        
        if ($user && $user->type == "admin") {
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                // Redirect ke dashboard admin khusus
                return redirect()->intended('/home');
            }
        }
        
        return back()->with('login_error', 'Login gagal! Pastikan email dan password benar dan Anda adalah admin.');
    }

    public function logout(Request $request) {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login-adm');
    }
}