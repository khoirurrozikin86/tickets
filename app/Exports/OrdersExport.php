<?php

namespace App\Exports;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class OrdersExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function query(): Builder
    {
        $query = Order::query()
            ->with([
                'items:id,order_id,product_name,visit_date,quantity',
            ]);

        if (!empty($this->filters['order_number'])) {
            $query->where(
                'order_number',
                'like',
                '%' . $this->filters['order_number'] . '%'
            );
        }

        if (!empty($this->filters['customer'])) {
            $customer = $this->filters['customer'];

            $query->where(function ($q) use ($customer) {
                $q->where(
                    'customer_name',
                    'like',
                    '%' . $customer . '%'
                )
                    ->orWhere(
                        'customer_email',
                        'like',
                        '%' . $customer . '%'
                    )
                    ->orWhere(
                        'customer_phone',
                        'like',
                        '%' . $customer . '%'
                    );
            });
        }

        if (!empty($this->filters['payment_status'])) {
            $query->where(
                'payment_status',
                $this->filters['payment_status']
            );
        }

        if (!empty($this->filters['status'])) {
            $query->where(
                'status',
                $this->filters['status']
            );
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate(
                'created_at',
                '>=',
                $this->filters['date_from']
            );
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate(
                'created_at',
                '<=',
                $this->filters['date_to']
            );
        }

        return $query->orderByDesc('created_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Order Number',
            'Customer',
            'Email',
            'Phone',
            'Items',
            'Subtotal',
            'Discount Code',
            'Discount Amount',
            'Total Amount',
            'Payment Status',
            'Order Status',
            'Expires At',
            'Paid At',
            'Created At',
        ];
    }

    public function map($order): array
    {
        static $no = 0;

        $no++;

        $items = $order->items
            ->map(function ($item) {
                return $item->product_name
                    . ' x' . $item->quantity
                    . ' (' . $item->visit_date->format('d/m/Y') . ')';
            })
            ->implode(', ');

        return [
            $no,
            $order->order_number,
            $order->customer_name,
            $order->customer_email,
            $order->customer_phone,
            $items ?: '-',
            $order->subtotal,
            $order->discount_code ?? '-',
            $order->discount_amount,
            $order->total_amount,
            $order->payment_status,
            $order->status,
            $order->expires_at?->format('d/m/Y H:i:s') ?? '-',
            $order->paid_at?->format('d/m/Y H:i:s') ?? '-',
            $order->created_at?->format('d/m/Y H:i:s') ?? '-',
        ];
    }
}
