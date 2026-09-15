<?php

namespace App\Domain\ScanRecords\DTOs;

final readonly class ScanTicketData
{
    public function __construct(
        public string $token,
        public int $userId,
        public ?string $ipAddress = null,
        public ?string $userAgent = null,
        public array $metadata = [],
    ) {}
}
