<?php

namespace App\Domain\ScanRecords\Actions;

use App\Domain\ScanRecords\DTOs\ScanTicketData;
use App\Models\ScanRecord;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use RuntimeException;

use App\Domain\Tickets\Actions\MarkTicketAsUsedAction;

final class ScanTicketAction
{
    public function __construct(
        private readonly MarkTicketAsUsedAction $markTicketAsUsedAction,
    ) {}

    public function execute(ScanTicketData $data): ScanRecord
    {
        return DB::transaction(function () use ($data) {

            $ticket = Ticket::query()
                ->where('token', $data->token)
                ->first();

            /*
             |--------------------------------------------------------------------------
             | Ticket tidak ditemukan
             |--------------------------------------------------------------------------
             */

            if (! $ticket) {
                return ScanRecord::create([
                    'ticket_id' => null,
                    'result' => 'FAILED',
                    'reason' => 'NOT_FOUND',
                    'scanned_by' => $data->userId,
                    'scanned_at' => now(),
                    'ip_address' => $data->ipAddress,
                    'user_agent' => $data->userAgent,
                    'metadata' => $data->metadata,
                ]);
            }

            /*
             |--------------------------------------------------------------------------
             | Ticket ditemukan
             |--------------------------------------------------------------------------
             */

            try {
                $ticket = $this->markTicketAsUsedAction->execute(
                    ticket: $ticket,
                    userId: $data->userId,
                );

                return ScanRecord::create([
                    'ticket_id' => $ticket->id,
                    'result' => 'SUCCESS',
                    'reason' => 'VALID',
                    'scanned_by' => $data->userId,
                    'scanned_at' => now(),
                    'ip_address' => $data->ipAddress,
                    'user_agent' => $data->userAgent,
                    'metadata' => $data->metadata,
                ]);
            } catch (RuntimeException $e) {

                $reason = match ($ticket->status) {
                    'USED' => 'ALREADY_USED',
                    'EXPIRED' => 'EXPIRED',
                    'CANCELLED' => 'CANCELLED',
                    default => 'INVALID_STATUS',
                };

                return ScanRecord::create([
                    'ticket_id' => $ticket->id,
                    'result' => 'FAILED',
                    'reason' => $reason,
                    'scanned_by' => $data->userId,
                    'scanned_at' => now(),
                    'ip_address' => $data->ipAddress,
                    'user_agent' => $data->userAgent,
                    'metadata' => [
                        ...$data->metadata,
                        'error' => $e->getMessage(),
                    ],
                ]);
            }
        });
    }
}
