<?php

namespace App\Domain\Notifications\Queries;

use App\Models\NotificationLog;
use Illuminate\Database\Eloquent\Builder;

class NotificationTableQuery
{
    public function builder(): Builder
    {
        return NotificationLog::query()
            ->with(['order'])
            ->select('notification_logs.*')
            ->latest('notification_logs.created_at');
    }
}
