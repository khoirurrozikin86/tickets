<?php

namespace App\Domain\Holidays\Actions;

use App\Models\Holiday;
use Illuminate\Support\Facades\DB;

class CreateHolidayAction
{
    public function execute(array $data): Holiday
    {
        return DB::transaction(function () use ($data) {
            return Holiday::create($data);
        });
    }
}
