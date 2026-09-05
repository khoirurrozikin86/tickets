<?php

namespace App\Exports;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements
    FromQuery,
    WithHeadings,
    WithMapping,
    ShouldAutoSize
{
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function query(): Builder
    {
        $query = Payment::query()
            ->with('order')
            ->orderByDesc('created_at');

        if (!empty($this->filters['payment_number'])) {
            $query->where(
                'payment_number',
                'like',
                '%' . $this->filters['payment_number'] . '%'
            );
        }

        if (!empty($this->filters['order_number'])) {
            $query->whereHas('order', function ($q) {
                $q->where(
                    'order_number',
                    'like',
                    '%' . $this->filters['order_number'] . '%'
                );
            });
        }

        if (!empty($this->filters['customer'])) {

            $customer = $this->filters['customer'];

            $query->whereHas('order', function ($q) use ($customer) {

                $q->where(
                    'customer_name',
                    'like',
                    "%{$customer}%"
                )
                    ->orWhere(
                        'customer_email',
                        'like',
                        "%{$customer}%"
                    )
                    ->orWhere(
                        'customer_phone',
                        'like',
                        "%{$customer}%"
                    );
            });
        }

        if (!empty($this->filters['gateway'])) {
            $query->where(
                'gateway',
                $this->filters['gateway']
            );
        }

        if (!empty($this->filters['payment_method'])) {
            $query->where(
                'payment_method',
                $this->filters['payment_method']
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

        return $query;
    }

    public function headings(): array
    {
        return [
            'No',
            'Payment Number',
            'Order Number',
            'Customer',
            'Email',
            'Phone',
            'Gateway',
            'Payment Method',
            'Payment Channel',
            'Gateway Reference',
            'Gateway Transaction ID',
            'Amount',
            'Currency',
            'Status',
            'Expired At',
            'Paid At',
            'Created At',
        ];
    }

    public function map($payment): array
    {
        static $no = 0;

        $no++;

        return [
            $no,
            $payment->payment_number,
            $payment->order?->order_number ?? '-',
            $payment->order?->customer_name ?? '-',
            $payment->order?->customer_email ?? '-',
            $payment->order?->customer_phone ?? '-',
            $payment->gateway,
            $payment->payment_method,
            $payment->payment_channel ?? '-',
            $payment->gateway_reference ?? '-',
            $payment->gateway_transaction_id ?? '-',
            (float) $payment->amount,
            $payment->currency,
            $payment->status,
            $payment->expired_at?->format('d/m/Y H:i:s'),
            $payment->paid_at?->format('d/m/Y H:i:s'),
            $payment->created_at?->format('d/m/Y H:i:s'),
        ];
    }
}
