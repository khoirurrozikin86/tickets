<?php

namespace App\Domain\Holidays\Services;

use App\Domain\Holidays\Actions\CreateHolidayAction;
use App\Domain\Holidays\Actions\DeleteHolidayAction;
use App\Domain\Holidays\Actions\UpdateHolidayAction;
use App\Domain\Holidays\DTOs\HolidayData;
use App\Models\Holiday;

class HolidayService
{
    public function __construct(
        protected CreateHolidayAction $createHolidayAction,
        protected UpdateHolidayAction $updateHolidayAction,
        protected DeleteHolidayAction $deleteHolidayAction,
    ) {}

    public function create(array $data): Holiday
    {
        $dto = HolidayData::fromArray($data);

        return $this->createHolidayAction->execute(
            $dto->toArray()
        );
    }

    public function update(Holiday $holiday, array $data): Holiday
    {
        $dto = HolidayData::fromArray($data);

        return $this->updateHolidayAction->execute(
            $holiday,
            $dto->toArray()
        );
    }

    public function delete(Holiday $holiday): bool
    {
        return $this->deleteHolidayAction->execute($holiday);
    }
}
