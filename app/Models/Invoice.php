<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Invoice extends Model
{
    protected $fillable = [
        'order_id',
        'invoice_number',
        'invoice_date',

        'customer_name',
        'customer_email',
        'customer_phone',

        'subtotal',
        'discount_amount',
        'total_amount',
        'currency',

        'status',
        'issued_at',

        'notes',
        'metadata',
    ];

    protected $casts = [
        'invoice_date' => 'date',

        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',

        'issued_at' => 'datetime',

        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}