<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'method',
        'provider',
        'transaction_id',
        'amount',
        'status',
        'response_json',
        'payment_method',
    ];

    protected $casts = [
        'response_json' => 'array',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}
