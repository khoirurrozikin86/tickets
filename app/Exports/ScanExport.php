<?php

namespace App\Exports;

use App\Models\ScanRecord;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ScanExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private readonly Request $request
    ) {}

    public function query()
    {
        $query = ScanRecord::query()
            ->with([
                'ticket:id,ticket_number,product_name,visit_date,status',
                'scannedBy:id,name',
            ]);

        $dateFrom = $this->request->input(
            'date_from',
            now()->format('Y-m-d')
        );

        $dateTo = $this->request->input(
            'date_to',
            now()->format('Y-m-d')
        );

        $query->whereDate(
            'scanned_at',
            '>=',
            $dateFrom
        );

        $query->whereDate(
            'scanned_at',
            '<=',
            $dateTo
        );

        if ($this->request->filled('result')) {
            $query->where(
                'result',
                $this->request->string('result')->toString()
            );
        }

        if ($this->request->filled('reason')) {
            $query->where(
                'reason',
                $this->request->string('reason')->toString()
            );
        }

        return $query->latest('scanned_at');
    }

    public function headings(): array
    {
        return [
            'No',
            'Ticket Number',
            'Product',
            'Visit Date',
            'Result',
            'Reason',
            'Ticket Status',
            'Scanner',
            'Scanned At',
        ];
    }

    public function map($scan): array
    {
        static $no = 0;

        $no++;

        return [
            $no,
            $scan->ticket?->ticket_number ?? '-',
            $scan->ticket?->product_name ?? '-',
            $scan->ticket?->visit_date?->format('d/m/Y') ?? '-',
            $scan->result,
            $scan->reason,
            $scan->ticket?->status ?? '-',
            $scan->scannedBy?->name ?? 'System',
            $scan->scanned_at?->format('d/m/Y H:i:s') ?? '-',
        ];
    }
}
