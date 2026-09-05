<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends Model
{
    protected $fillable = [
        'order_id',

        'payment_number',

        'gateway',
        'payment_method',
        'payment_channel',

        'gateway_reference',
        'gateway_transaction_id',

        'amount',
        'currency',

        'status',

        'expired_at',
        'paid_at',
        'cancelled_at',

        'payment_url',
        'qr_code',

        'callback_payload',
        'metadata',
    ];

    protected $casts = [
        'amount' => 'decimal:2',

        'expired_at' => 'datetime',
        'paid_at' => 'datetime',
        'cancelled_at' => 'datetime',

        'callback_payload' => 'array',
        'metadata' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
