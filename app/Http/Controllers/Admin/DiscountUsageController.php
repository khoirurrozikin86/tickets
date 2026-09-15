<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Discounts\Queries\GetDiscountUsageHistoryQuery;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\View\View;

class DiscountUsageController extends Controller
{
    public function __construct(
        private readonly GetDiscountUsageHistoryQuery $query,
    ) {}

    public function index(Discount $discount): View
    {
        $usages = $this->query->execute(
            discount: $discount,
            perPage: 10,
        );

        return view('super.discounts.usages.index', [
            'discount' => $discount,
            'usages' => $usages,
        ]);
    }
}
