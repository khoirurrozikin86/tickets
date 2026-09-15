<?php

namespace App\Domain\ScanRecords\Queries;

use App\Models\ScanRecord;
use Illuminate\Database\Eloquent\Builder;

final class ScanTableQuery
{
    public function builder(): Builder
    {
        return ScanRecord::query()
            ->with([
                'ticket:id,ticket_number,product_name,visit_date,status',
                'scannedBy:id,name',
            ])
            ->select('scan_records.*');
    }
}
