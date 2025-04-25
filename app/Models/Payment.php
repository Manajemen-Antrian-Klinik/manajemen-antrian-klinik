<?php

namespace App\Models;

use Nette\Schema\Schema;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;

class Payment extends Model
{
    protected $fillable = ['user_id', 'queue_id', 'amount', 'payment_method', 'status'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function queue()
    {
        return $this->belongsTo(Queue::class);
    }
}