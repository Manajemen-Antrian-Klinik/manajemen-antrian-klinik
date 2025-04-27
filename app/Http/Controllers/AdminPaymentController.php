<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Queue;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class AdminPaymentController extends Controller
{
    public function index(Request $request)
    {
        // Ambil tanggal yang dipilih dari parameter URL atau gunakan hari ini
        $selectedDateStr = $request->get('date');
        $selectedDate = $selectedDateStr ? Carbon::createFromFormat('Y-m-d', $selectedDateStr)->startOfDay() : Carbon::today()->startOfDay();
        
        // Filter status pembayaran
        $status = $request->get('status', 'semua');
        
        // Query untuk pembayaran berdasarkan tanggal antrian
        $query = Payment::whereHas('queue', function ($queueQuery) use ($selectedDate) {
            $queueQuery->whereDate('date', $selectedDate->format('Y-m-d'));
        })->with(['queue', 'queue.patient']);
        
        // Filter berdasarkan status pembayaran jika ada
        if ($status !== 'semua') {
            if ($status === 'belum-bayar') {
                $query->where('status', 'unpaid');
            } elseif ($status === 'sudah-bayar') {
                $query->where('status', 'paid');
            }
        }
        
        $payments = $query->orderBy('created_at', 'asc')
            ->paginate(15);
        
        // Dapatkan semua tanggal yang memiliki antrian untuk dropdown
        $availableDates = $this->getAvailableDates();
        
        return view('admin.pembayaran.index', [
            'payments' => $payments, 
            'selectedDate' => $selectedDate, 
            'availableDates' => $availableDates,
            'title' => 'Manajemen Pembayaran'
        ]);
    }
    
    /**
     * Mendapatkan semua tanggal yang tersedia untuk dropdown
     *
     * @return \Illuminate\Support\Collection
     */
    private function getAvailableDates()
    {
        // Generate tanggal yang tersedia yaitu 6 hari
        $availableDates = collect();
        for ($i = 0; $i < 6; $i++) {
            $date = now()->addDays($i);
            $availableDates->push([
                'date' => $date,
                'formatted' => $date->format('d F Y') . ($date->isToday() ? ' (Hari Ini)' : '')
            ]);
        }

        return $availableDates;
    }
    
    /**
     * Mendapatkan detail pembayaran
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getDetails($id)
    {
        $payment = Payment::with(['queue', 'queue.patient'])->findOrFail($id);
        
        return response()->json($payment);
    }
    
    /**
     * Memproses pembayaran
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function processPayment(Request $request)
    {
        $request->validate([
            'payment_id' => 'required|exists:payments,id',
            'amount' => 'required|string',
            'payment_method' => 'required|in:cash,debit,credit,transfer',
            'notes' => 'nullable|string',
        ]);
        
        // Konversi format angka berisi titik/koma
        $amount = str_replace(['.', ','], '', $request->amount);
        
        // Update pembayaran menjadi lunas
        $payment = Payment::findOrFail($request->payment_id);
        $payment->update([
            'amount' => $amount,
            'payment_method' => $request->payment_method,
            'status' => 'paid',
            'paid_at' => now(),
            'notes' => $request->notes,
            'receipt_number' => $this->generateReceiptNumber(),
        ]);
        
        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Pembayaran berhasil diproses!');
    }
    
    /**
     * Update data pembayaran
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $request->validate([
            'edit_payment_id' => 'required|exists:payments,id',
            'amount' => 'required|string',
            'payment_method' => 'required|in:cash,debit,credit,transfer',
            'status' => 'required|in:paid,unpaid',
            'notes' => 'nullable|string',
        ]);
        
        // Konversi format angka berisi titik/koma
        $amount = str_replace(['.', ','], '', $request->amount);
        
        // Update data pembayaran
        $payment = Payment::findOrFail($request->edit_payment_id);
        $paymentData = [
            'amount' => $amount,
            'payment_method' => $request->payment_method,
            'status' => $request->status,
            'notes' => $request->notes,
        ];
        
        // Jika status berubah menjadi paid dan sebelumnya unpaid
        if ($request->status === 'paid' && $payment->status === 'unpaid') {
            $paymentData['paid_at'] = now();
            $paymentData['receipt_number'] = $this->generateReceiptNumber();
        }
        
        // Jika status berubah menjadi unpaid dan sebelumnya paid
        if ($request->status === 'unpaid' && $payment->status === 'paid') {
            $paymentData['paid_at'] = null;
            $paymentData['receipt_number'] = null;
        }
        
        $payment->update($paymentData);
        
        return redirect()->route('admin.pembayaran.index')
            ->with('success', 'Data pembayaran berhasil diperbarui!');
    }
    
    /**
     * Mendapatkan data kuitansi pembayaran
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getReceipt($id)
    {
        $payment = Payment::with(['queue', 'queue.patient'])->findOrFail($id);
        
        if ($payment->status !== 'paid') {
            return response()->json(['error' => 'Pembayaran belum lunas'], 400);
        }
        
        $data = [
            'receipt_number' => $payment->receipt_number,
            'formatted_date' => Carbon::parse($payment->paid_at)->translatedFormat('d F Y'),
            'patient_name' => $payment->queue->patient->name,
            'queue_number' => $payment->queue->queue_number,
            'amount' => $payment->amount,
            'payment_method' => $payment->payment_method,
            'notes' => $payment->notes,
        ];
        
        return response()->json($data);
    }
    
    /**
     * Generate nomor kuitansi unik
     *
     * @return string
     */
    private function generateReceiptNumber()
    {
        $date = now()->format('Ymd');
        $random = strtoupper(Str::random(4));
        $count = Payment::whereDate('created_at', now())->count() + 1;
        
        return "INV/{$date}/{$random}/{$count}";
    }
    
    /**
     * Membuat pembayaran otomatis ketika antrian dibuat
     * Method ini dipanggil dari QueueController
     *
     * @param  \App\Models\Queue  $queue
     * @return void
     */
    public static function createPaymentForQueue(Queue $queue)
    {
        // Cek apakah pembayaran sudah ada
        $existingPayment = Payment::where('queue_id', $queue->id)->first();
        
        if (!$existingPayment) {
            Payment::create([
                'queue_id' => $queue->id,
                'amount' => 0, // Jumlah awal
                'status' => 'unpaid',
                'payment_method' => 'cash', // Default
            ]);
        }
    }
    
    /**
     * Update pembayaran ketika status antrian berubah menjadi selesai
     * Method ini dipanggil dari QueueController
     *
     * @param  \App\Models\Queue  $queue
     * @param  int  $amount
     * @return void
     */
    public static function updatePaymentWhenQueueCompleted(Queue $queue, $amount = null)
    {
        $payment = Payment::where('queue_id', $queue->id)->first();
        
        if ($payment) {
            $updateData = [];
            
            if ($amount) {
                $updateData['amount'] = $amount;
            } else {
                // Default amount jika tidak ada yang diberikan
                $updateData['amount'] = 50000;
            }
            
            if (!empty($updateData)) {
                $payment->update($updateData);
            }
        } else {
            // Jika belum ada pembayaran, buat baru
            self::createPaymentForQueue($queue);
            
            // Update amount setelah dibuat
            $payment = Payment::where('queue_id', $queue->id)->first();
            if ($payment) {
                $payment->update([
                    'amount' => $amount ?: 50000
                ]);
            }
        }
    }
}