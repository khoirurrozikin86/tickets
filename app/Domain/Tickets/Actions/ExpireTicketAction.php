<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

class ExpireTicketAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Ticket $ticket): Ticket
    {
        return DB::transaction(function () use ($ticket) {

            if ($ticket->status !== 'ACTIVE') {
                return $ticket;
            }

            $oldValues = $ticket->only([
                'status',
                'expired_at',
            ]);

            $ticket->update([
                'status' => 'EXPIRED',
                'expired_at' => now(),
            ]);

            $ticket->refresh();

            $this->auditLogService->log(
                action: 'EXPIRE',
                module: 'TICKET',
                model: $ticket,
                description: 'Tiket kadaluarsa',
                oldValues: $oldValues,
                newValues: $ticket->only([
                    'status',
                    'expired_at',
                ]),
            );

            return $ticket;
        });
    }
}
