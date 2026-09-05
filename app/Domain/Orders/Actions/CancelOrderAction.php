<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class CancelTicketAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Ticket $ticket): Ticket
    {
        return DB::transaction(function () use ($ticket) {

            if ($ticket->status === 'USED') {
                throw new RuntimeException(
                    'Tiket yang sudah digunakan tidak dapat dibatalkan.'
                );
            }

            $oldValues = $ticket->only([
                'status',
            ]);

            $ticket->update([
                'status' => 'CANCELLED',
            ]);

            $ticket->refresh();

            $this->auditLogService->log(
                action: 'CANCEL',
                module: 'TICKET',
                model: $ticket,
                description: 'Membatalkan tiket',
                oldValues: $oldValues,
                newValues: $ticket->only([
                    'status',
                ]),
            );

            return $ticket;
        });
    }
}
