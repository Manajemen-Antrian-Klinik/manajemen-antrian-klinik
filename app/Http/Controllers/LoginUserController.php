<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginUserController extends Controller
{   
    // Tampilan Halaman Login User
    public function index() {
        return view('login.LoginUser', [
            'title' => 'Login',
        ]);
    }

    // Authentikasi Login User
    public function authenticate(Request $request) {
        $credentials = $request->validate([
            'email' => 'required|email:dns',
            'password' => 'required|'
        ]);
        
        if(Auth::attempt($credentials)) {
            $request->session()->regenerate();

            return redirect()->intended('/home');
        }
        

        return back()->with('login_error', 'Login Failed');
        dd('Berhasil login');
    }

    // Logout User
    public function logout(Request $request) {
        
        Auth::logout();
        
        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/login');
    }
}