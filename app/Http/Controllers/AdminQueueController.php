<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Queue;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use App\Http\Controllers\AdminPaymentController;

class AdminQueueController extends Controller
{      
    // Tampilan halaman Antrian pada Admin
    public function index(Request $request) {
        // Tangani parameter tanggal
        try {
            $selectedDate = $request->filled('date') 
                ? Carbon::parse($request->date)
                : now();
        } catch (\Exception $e) {
            $selectedDate = now();
        }
    
        // Get data antrian untuk tanggal yang dipilih
        $queues = Queue::with(['patient' => function($query) {
            $query->select('id', 'name', 'address', 'birth_date', 'phone');
        }])
            ->whereDate('date', $selectedDate->format('Y-m-d'))
            ->orderBy('queue_number') // Berurut berdasarkan nomor antrian
            ->get();
        
        // Generate tanggal yang tersedia yaitu 6 hari
        $availableDates = collect();
        for ($i = 0; $i < 6; $i++) {
            $date = now()->addDays($i);
            $availableDates->push([
                'date' => $date,
                'formatted' => $date->format('d F Y') . ($date->isToday() ? ' (Hari Ini)' : '')
            ]);
        }
        return view('admin.antrian.index', [
            'title' => 'Manajemen Antrian',
            'queues' => $queues, // Daftar antrian untuk tanggal yang dipilih
            'availableDates' => $availableDates, // Koleksi tanggal
            'selectedDate' => Carbon::parse($selectedDate), // Tanggal yang sedang dipilih
        ]);
    }

    // Tombol next pada tabel action
    public function next(Queue $queue) {
        // Jika status saat ini "Menunggu", ubah menjadi "Dalam Pemeriksaan"
        if ($queue->status === 'Menunggu') {
            $queue->update(['status' => 'Dalam Pemeriksaan']);
            return back()->with('success', 'Antrian berhasil dipindahkan ke status Dalam Pemeriksaan');
        }
        
        // Jika status saat ini "Dalam Pemeriksaan", ubah menjadi "Selesai"
        if ($queue->status === 'Dalam Pemeriksaan') {
            $queue->update(['status' => 'Selesai']);
            
            // Tambahkan ini: Update pembayaran ketika antrian selesai dengan default amount
            AdminPaymentController::updatePaymentWhenQueueCompleted($queue, 50000);
            
            // Cari antrian berikutnya yang "Menunggu"
            $nextQueue = Queue::where('date', $queue->date)
                ->where('queue_number', '>', $queue->queue_number)
                ->where('status', 'Menunggu')
                ->orderBy('queue_number')
                ->first();
                
            if ($nextQueue) {
                $nextQueue->update(['status' => 'Dalam Pemeriksaan']);
            }
            
            return back()->with('success', 'Antrian selesai diproses');
        }
    
        return back()->with('error', 'Aksi tidak valid untuk status saat ini');
    }

    // Tombol previous pada tabel action
    public function previous(Queue $queue) {
        // Validasi: hanya bisa previous jika status sedang diperiksa
        if ($queue->status !== 'Dalam Pemeriksaan') {
            return back()->with('error', 'Hanya antrian yang sedang diperiksa yang bisa di-previous');
        }

        // Kembalikan status antrian saat ini menjadi menunggu
        $queue->update(['status' => 'Menunggu']);

        // Cari antrian sebelumnya yang selesai
        $prevQueue = Queue::where('date', $queue->date)
            ->where('queue_number', '<', $queue->queue_number)
            ->where('status', 'Selesai')
            ->orderByDesc('queue_number')
            ->first();

        if ($prevQueue) {
            $prevQueue->update(['status' => 'Dalam Pemeriksaan']);
        }

        return back()->with('success', 'Status antrian berhasil diperbarui');
    }

    // Menghapus Antrian 
    public function destroy(Queue $queue)
    {   
        if ($queue->status === 'Dalam Pemeriksaan') {
            return back()->with('error', 'Tidak bisa menghapus antrian yang sedang diproses');
        }
        
        // Payment akan otomatis terhapus karena ada onDelete('cascade') di migrasi
        $queue->delete();
        
        return back()->with('success', 'Antrian berhasil dihapus');
    }
}