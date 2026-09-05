<?php

namespace App\Exports;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class TicketsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    public function __construct(
        private readonly array $filters = []
    ) {}

    public function query(): Builder
    {
        $query = Ticket::query()
            ->with([
                'order:id,order_number,customer_name,customer_email,customer_phone',
                'usedBy:id,name',
            ]);

        if (!empty($this->filters['ticket_number'])) {
            $query->where(
                'ticket_number',
                'like',
                '%' . $this->filters['ticket_number'] . '%'
            );
        }

        if (!empty($this->filters['product_id'])) {
            $query->where(
                'product_id',
                $this->filters['product_id']
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
                'visit_date',
                '>=',
                $this->filters['date_from']
            );
        }

        if (!empty($this->filters['date_to'])) {
            $query->whereDate(
                'visit_date',
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
            'Ticket Number',
            'Order Number',
            'Customer',
            'Email',
            'Phone',
            'Produk',
            'Visit Date',
            'Status',
            'Issued At',
            'Scan At',
            'Scanned By',
        ];
    }

    public function map($ticket): array
    {
        static $no = 0;

        $no++;

        return [
            $no,
            $ticket->ticket_number,
            $ticket->order?->order_number ?? '-',
            $ticket->order?->customer_name ?? '-',
            $ticket->order?->customer_email ?? '-',
            $ticket->order?->customer_phone ?? '-',
            $ticket->product_name,
            $ticket->visit_date?->format('d/m/Y') ?? '-',
            $ticket->status,
            $ticket->issued_at?->format('d/m/Y H:i:s') ?? '-',
            $ticket->used_at?->format('d/m/Y H:i:s') ?? '-',
            $ticket->usedBy?->name ?? '-',
        ];
    }
}
