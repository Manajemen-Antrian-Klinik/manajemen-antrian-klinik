<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{   
    // Tampilan Halaman Register User
    public function index() {
        return view('register.register', [
            'title' => 'Register',
        
        ]);
    }

    // Menyimpan data register ke database
    public function store(Request $request) {
        $validated = $request->validate([
            'name' => 'required|max:255',
            'email' => 'required|email:dns|unique:users',
            'password' => 'required|min:8|max:255',
            'password_confirmation' => 'required|same:password',
        ]);
        
        $validated['password'] = Hash::make($validated['password']);

        User::create([
            "name" => $validated["name"],
            "email" => $validated["email"],
            "password" => $validated["password"]
        ]);

        // Menuju halaman Login setelah Register
        return redirect('/login')->with("register_success", "Succeed to create the account, please login again");
    }
}