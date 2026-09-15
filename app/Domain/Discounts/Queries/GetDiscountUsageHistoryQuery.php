<?php

namespace App\Domain\Discounts\Queries;

use App\Models\Discount;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

final class GetDiscountUsageHistoryQuery
{
    public function execute(
        Discount $discount,
        int $perPage = 10
    ): LengthAwarePaginator {
        return $discount->usages()
            ->with([
                'order:id,order_number,customer_name,customer_email',
            ])
            ->latest('used_at')
            ->paginate($perPage);
    }
}
