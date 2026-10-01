<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebsiteVisit extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'visitor_hash',
        'path',
        'referrer_host',
        'device',
        'browser',
        'visited_at',
    ];

    protected $casts = [
        'visited_at' => 'datetime',
    ];
}
