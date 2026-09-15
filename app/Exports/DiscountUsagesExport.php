<?php

namespace App\Exports;

use App\Models\DiscountUsage;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class DiscountUsagesExport implements
    FromQuery,
    WithHeadings,
    WithMapping
{
    protected int $number = 0;

    public function __construct(
        protected int $discountId,
        protected array $filters = [],
    ) {}

    public function query()
    {
        $query = DiscountUsage::query()
            ->with([
                'order:id,order_number,customer_name,customer_email',
            ])
            ->where(
                'discount_id',
                $this->discountId
            );

        /*
        |--------------------------------------------------------------------------
        | Filter Order
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['order_number'])) {
            $orderNumber = $this->filters['order_number'];

            $query->whereHas('order', function ($q) use ($orderNumber) {
                $q->where(
                    'order_number',
                    'like',
                    '%' . $orderNumber . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Customer
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['customer'])) {
            $customer = $this->filters['customer'];

            $query->whereHas('order', function ($q) use ($customer) {
                $q->where(
                    'customer_name',
                    'like',
                    '%' . $customer . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Email
        |--------------------------------------------------------------------------
        */

        if (!empty($this->filters['email'])) {
            $email = $this->filters['email'];

            $query->whereHas('order', function ($q) use ($email) {
                $q->where(
                    'customer_email',
                    'like',
                    '%' . $email . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Date
        |--------------------------------------------------------------------------
        |
        | Default: hari ini
        |
        */

        $dateFrom = $this->filters['date_from']
            ?? now()->format('Y-m-d');

        $dateTo = $this->filters['date_to']
            ?? now()->format('Y-m-d');

        $query
            ->whereDate(
                'discount_usages.used_at',
                '>=',
                $dateFrom
            )
            ->whereDate(
                'discount_usages.used_at',
                '<=',
                $dateTo
            );

        return $query
            ->orderByDesc('discount_usages.used_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Order',
            'Customer',
            'Email',
            'Discount',
            'Used At',
        ];
    }

    public function map($usage): array
    {
        $this->number++;

        return [
            $this->number,
            $usage->order?->order_number ?? '-',
            $usage->order?->customer_name ?? '-',
            $usage->order?->customer_email ?? '-',
            (float) $usage->discount_amount,
            $usage->used_at?->format('d/m/Y H:i:s') ?? '-',
        ];
    }
}
