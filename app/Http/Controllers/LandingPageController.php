<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingPageController extends Controller
{   
    // Tampilan Halaman Landingpage
    public function index()
    {
        return view('index', [
            'title' => 'Landing Page',
        ]);
    }
}