<?php

namespace App\Domain\Holidays\Queries;

use App\Models\Holiday;
use Illuminate\Database\Eloquent\Builder;

class HolidayTableQuery
{
    public function builder(): Builder
    {
        return Holiday::query()
            ->select([
                'holidays.id',
                'holidays.date',
                'holidays.name',
                'holidays.is_active',
                'holidays.created_at',
                'holidays.updated_at',
            ])
            ->orderBy('holidays.date', 'asc');
    }
}
