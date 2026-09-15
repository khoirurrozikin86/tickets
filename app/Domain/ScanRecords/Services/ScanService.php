<?php

namespace App\Domain\ScanRecords\Services;

use App\Domain\ScanRecords\Actions\ScanTicketAction;
use App\Domain\ScanRecords\DTOs\ScanTicketData;
use App\Models\ScanRecord;

final class ScanService
{
    public function __construct(
        private readonly ScanTicketAction $scanTicketAction,
    ) {}

    /**
     * Memproses scan tiket.
     */
    public function scan(
        ScanTicketData $data
    ): ScanRecord {
        return $this->scanTicketAction->execute(
            data: $data,
        );
    }
}
