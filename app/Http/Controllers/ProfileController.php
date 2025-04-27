<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Rules\PhoneNumber;

class ProfileController extends Controller
{   
    // Tampilan Halaman Profile User
    public function index() {
        $user = Auth::user();
        
        return view('profile.index', [
            'title' => 'Profile',
            'user' => $user
        ]);
    }

    // Update perubahan data di profile
    public function update(Request $request) {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:Laki - Laki,Perempuan',
            'phone' => ['nullable', 'string', 'max:15', new PhoneNumber],
            'nik' => 'nullable|string|max:16',
            'bpjs_number' => 'nullable|string|max:13',
            'address' => 'nullable|string|max:255',
        ]);
        
        
        $user->update($validated);

        return redirect()->route('profile.index')->with('success', 'Profile updated successfully!');
    }
}