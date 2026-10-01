<?php

namespace App\Exports;

use App\Models\WebsiteVisit;
use Carbon\CarbonInterface;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class WebsiteVisitsExport implements FromQuery, WithHeadings, WithMapping
{
    public function __construct(
        private readonly CarbonInterface $dateFrom,
        private readonly CarbonInterface $dateTo,
    ) {}

    public function query(): Builder
    {
        return WebsiteVisit::query()
            ->where('device', '!=', 'bot')
            ->whereBetween('visited_at', [$this->dateFrom, $this->dateTo])
            ->orderBy('visited_at');
    }

    public function headings(): array
    {
        return ['Visitor ID (anonim)', 'Waktu', 'Halaman', 'Asal Kunjungan', 'Perangkat', 'Browser'];
    }

    public function map($visit): array
    {
        return [
            substr($visit->visitor_hash, 0, 12),
            $visit->visited_at?->format('d/m/Y H:i:s'),
            $visit->path,
            $visit->referrer_host ?: 'Direct',
            ucfirst($visit->device),
            $visit->browser,
        ];
    }
}
