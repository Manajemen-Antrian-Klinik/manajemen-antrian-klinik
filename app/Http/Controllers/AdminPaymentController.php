<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPaymentController extends Controller
{
    public function index()
{
    return view('admin.pembayaran.index', [
        'title' => 'Manajemen Pembayaran',
        'payments' => []
    ]);
}
}