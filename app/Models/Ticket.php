<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Ticket extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'product_id',
        'ticket_number',
        'token',
        'product_name',
        'visit_date',
        'status',
        'issued_at',
        'used_at',
        'expired_at',
        'used_by',
        'metadata',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'issued_at' => 'datetime',
        'used_at' => 'datetime',
        'expired_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }
}
