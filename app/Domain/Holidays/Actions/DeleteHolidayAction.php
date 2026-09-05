<?php

namespace App\Domain\Holidays\Actions;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class DeleteHolidayAction
{
    public function execute(Holiday $holiday): bool
    {
        return DB::transaction(function () use ($holiday) {
            return $holiday->delete();
        });
    }
}
