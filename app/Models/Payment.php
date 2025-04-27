<?php

namespace App\Models;

use Nette\Schema\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{   
    use HasFactory;
    
    protected $fillable = [
        'queue_id',
        'amount',
        'status',
        'payment_method',
        'receipt_number',
        'notes',
        'paid_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'paid_at' => 'datetime',
    ];

    /**
     * Relasi ke antrian
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}