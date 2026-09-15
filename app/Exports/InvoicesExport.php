<?php

namespace App\Exports;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class InvoicesExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function query(): Builder
    {
        $query = Invoice::query()
            ->with('order')
            ->latest('created_at');

        if (!empty($this->filters['invoice_number'])) {
            $query->where(
                'invoice_number',
                'like',
                '%' . $this->filters['invoice_number'] . '%'
            );
        }

        if (!empty($this->filters['order_number'])) {
            $orderNumber = $this->filters['order_number'];

            $query->whereHas('order', function ($q) use ($orderNumber) {
                $q->where('order_number', 'like', "%{$orderNumber}%");
            });
        }

        if (!empty($this->filters['customer'])) {
            $customer = $this->filters['customer'];

            $query->where(function ($q) use ($customer) {
                $q->where('customer_name', 'like', "%{$customer}%")
                    ->orWhere('customer_email', 'like', "%{$customer}%")
                    ->orWhere('customer_phone', 'like', "%{$customer}%");
            });
        }

        if (!empty($this->filters['status'])) {
            $query->where('status', $this->filters['status']);
        }

        if (!empty($this->filters['date_from'])) {
            $query->whereDate(
                'invoice_date',
                '>=',
                $this->filters['date_from']
            );
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate(
                'invoice_date',
                '<=',
                $this->filters['date_to']
            );
        }

        return $query;
    }

    public function headings(): array
    {
        return [
            'Invoice Number',
            'Order Number',
            'Customer',
            'Email',
            'Phone',
            'Subtotal',
            'Discount',
            'Total',
            'Currency',
            'Status',
            'Invoice Date',
            'Issued At',
        ];
    }

    public function map($invoice): array
    {
        return [
            $invoice->invoice_number,
            $invoice->order?->order_number ?? '-',
            $invoice->customer_name ?: '-',
            $invoice->customer_email ?: '-',
            $invoice->customer_phone ?: '-',
            $invoice->subtotal,
            $invoice->discount_amount,
            $invoice->total_amount,
            $invoice->currency,
            $invoice->status,
            $invoice->invoice_date?->format('d-m-Y'),
            $invoice->issued_at?->format('d-m-Y H:i:s'),
        ];
    }
}
