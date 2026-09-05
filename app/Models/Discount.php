<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'value',
        'max_discount',
        'min_purchase',
        'start_at',
        'end_at',
        'usage_limit',
        'usage_count',
        'is_active',
    ];

    protected $casts = [
        'value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'usage_limit' => 'integer',
        'usage_count' => 'integer',
        'is_active' => 'boolean',
    ];
}
