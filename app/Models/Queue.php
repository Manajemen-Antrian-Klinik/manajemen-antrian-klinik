<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Queue extends Model
{
    use HasFactory;

    protected $table = 'queues';
    
    protected $fillable = ['user_id', 'queue_number', 'complaint', 'date', 'status'];

    protected $attributes = [
        'status' => 'Menunggu',
    ];

    protected $casts = [
        'status' => 'string',
        'date' => 'date',
    ];

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function payment()
{
    return $this->hasOne(Payment::class);
}

    /**
     * Scope untuk antrian hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('date', today());
    }

    /**
     * Scope untuk antrian berdasarkan tanggal
     */
    public function scopeForDate($query, $date)
    {
        return $query->whereDate('date', $date);
    }

    /**
     * Scope untuk antrian dengan status tertentu
     */
    public function scopeWithStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Cek apakah antrian sedang menunggu
     */
    public function isWaiting(): bool
    {
        return $this->status === 'Menunggu';
    }

    /**
     * Cek apakah antrian sedang diperiksa
     */
    public function isInProgress(): bool
    {
        return $this->status === 'Dalam Pemeriksaan';
    }

    /**
     * Cek apakah antrian sudah selesai
     */
    public function isCompleted(): bool
    {
        return $this->status === 'Selesai';
    }

    public static function getActiveQueue()
{
    return self::where('status', 'Dalam Pemeriksaan')->first();
}

public static function getNextPendingQueue()
{
    return self::where('status', 'Menunggu')
        ->orderBy('queue_number')
        ->first();
}
}