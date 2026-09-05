<?php

namespace App\Domain\Discounts\Services;

use App\Domain\Discounts\Actions\CreateDiscountAction;
use App\Domain\Discounts\Actions\DeleteDiscountAction;
use App\Domain\Discounts\Actions\UpdateDiscountAction;
use App\Domain\Discounts\DTOs\DiscountData;
use App\Models\Discount;

class DiscountService
{
    public function __construct(
        protected CreateDiscountAction $createDiscountAction,
        protected UpdateDiscountAction $updateDiscountAction,
        protected DeleteDiscountAction $deleteDiscountAction,
    ) {}

    public function create(array $data): Discount
    {
        $dto = DiscountData::fromArray($data);

        return $this->createDiscountAction->execute(
            $dto->toArray()
        );
    }

    public function update(
        Discount $discount,
        array $data
    ): Discount {
        $dto = DiscountData::fromArray($data);

        return $this->updateDiscountAction->execute(
            $discount,
            $dto->toArray()
        );
    }

    public function delete(Discount $discount): bool
    {
        return $this->deleteDiscountAction->execute($discount);
    }
}
