<?php

namespace App\Http\Controllers;

use App\Models\Queue;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

use Illuminate\Support\Facades\Auth;


class QueueController extends Controller
{   
    public function index() {
        date_default_timezone_set('Asia/Jakarta');
        Carbon::setLocale('id');
        
        $today = Carbon::today()->format('Y-m-d');
        
        // Generate Pendaftaran 6 hari ke depan
        $dates = collect();
        for ($i = 0; $i < 6; $i++) {
            $date = Carbon::today()->addDays($i);
            $dates->push([
                'date' => $date->format('Y-m-d'),
                'formatted_date' => $date->format('d-M-Y'),
                'queues_count' => Queue::where('date', $date->format('Y-m-d'))->count(),
                'remaining' => 20 - Queue::where('date', $date->format('Y-m-d'))->count()
            ]);
        }

        // Ambil Semua antrian untuk user yang login, diurutkan berdasarkan tanggal
        $userQueues = Queue::where('user_id', Auth::id())
                ->where('date', '>=', $today) // Hanya yang belum lewat
                ->orderBy('date', 'asc')
                ->orderBy('queue_number', 'asc')
                ->get();

        return view('antrian.index', [
            'title' => 'Antrian',
            'queues' => $userQueues,
            'dates' => $dates,
            'today' => $today,
        ]);
    }
    
    // Menampilkan form keluhan (popup)
    public function create($date)
    {
        // Validasi tanggal tidak boleh hari kemarin
        if (Carbon::parse($date)->lt(Carbon::today())) {
            return redirect()->route('queue.index')->with('error', 'Tidak bisa mendaftar untuk tanggal yang sudah lewat');
        }

        // Cek apakah masih ada slot tersedia
        $queuesCount = Queue::where('date', $date)->count();
        if ($queuesCount >= 20) {
            return redirect()->route('queue.index')->with('error', 'Kuota antrian untuk tanggal ini sudah penuh');
        }

        return view('antrian.create', [
            'selectedDate' => $date,
            'formattedDate' => Carbon::parse($date)->format('d-M-Y')
        ]);
    }

    // Menyimpan data antrian setelah mendaftar
    public function store(Request $request, $date) {
        // Validasi tanggal
        if (Carbon::parse($date)->lt(Carbon::today())) {
            return back()->with('error', 'Tidak bisa mendaftar untuk tanggal yang sudah lewat');
        }

        // Cek kuota
        $queuesCount = Queue::where('date', $date)->count();
        if ($queuesCount >= 20) {
            return back()->with('error', 'Kuota antrian untuk tanggal ini sudah penuh');
        }

        // Cek apakah user sudah memiliki antrian di tanggal tersebut
        if (Queue::where('user_id', Auth::id())->where('date', $date)->exists()) {
            return back()->with('error', 'Anda sudah memiliki antrian untuk tanggal ini');
        }
        
        // Dapatkan nomor antrian terakhir untuk tanggal tersebut
        $lastQueue = Queue::where('date', $date)->max('queue_number');
        $newQueueNumber = $lastQueue ? $lastQueue + 1 : 1;

        // Buat antrian baru
        Queue::create([
            'user_id' => Auth::id(),
            'queue_number' => $newQueueNumber,
            'date' => $date,
            'status' => 'Menunggu',
            'complaint' => $request->complaint,
        ]);

        // Menuju halaman Antrian setelah mendaftar
        return redirect()
            ->route('queue.index')
            ->with('success', 'Antrian berhasil dibuat! Nomor Anda: ' . $newQueueNumber);
    }
}