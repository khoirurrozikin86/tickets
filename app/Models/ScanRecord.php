<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScanRecord extends Model
{
    protected $fillable = [
        'ticket_id',
        'result',
        'reason',
        'scanned_by',
        'scanned_at',
        'ip_address',
        'user_agent',
        'metadata',
    ];

    protected $casts = [
        'scanned_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function ticket(): BelongsTo
    {
        return $this->belongsTo(Ticket::class);
    }

    public function scannedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scanned_by');
    }
}
