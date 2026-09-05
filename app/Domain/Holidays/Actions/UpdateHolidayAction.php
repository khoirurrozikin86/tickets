<?php

namespace App\Domain\Holidays\Actions;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class UpdateHolidayAction
{
    public function execute(Holiday $holiday, array $data): Holiday
    {
        return DB::transaction(function () use ($holiday, $data) {
            $holiday->update($data);

            return $holiday->fresh();
        });
    }
}
