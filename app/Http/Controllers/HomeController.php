<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{   
    // Tampilan halaman Home pada User dan Admin
    public function index()
    {
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
        
        $today = Carbon::today()->format('Y-m-d');
        
        // Data antrian untuk tampilan home
        $todayQueues = Queue::whereDate('date', $today)->get();
        
        $queueData = [
            'total_quota' => 20,
            'remaining_quota' => 20 - $todayQueues->count(),
            'current_queue' => $todayQueues->where('status', 'Dalam Pemeriksaan')->first()->queue_number ?? '-',
            'completed_queues' => $todayQueues->where('status', 'Selesai')->count()
        ];
        
        // Tampilan Home yang berbeda untuk Admin dan User 
        if (Auth::check() && Auth::user()->type === 'admin') {
            return view('admin.home.index', [
                'title' => 'Dashboard Admin',
                'queueData' => $queueData,
            ]);
        } else {
            return view('home.index', [
                'title' => 'Home',
                'queueData' => $queueData,
            ]);
        }

    }
}