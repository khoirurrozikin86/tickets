<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class MarkTicketAsUsedAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        Ticket $ticket,
        int $userId
    ): Ticket {
        return DB::transaction(function () use ($ticket, $userId) {

            $ticket = Ticket::query()
                ->whereKey($ticket->id)
                ->lockForUpdate()
                ->firstOrFail();

            if ($ticket->status !== 'ACTIVE') {
                throw new RuntimeException(
                    "Tiket sudah tidak dapat digunakan. Status: {$ticket->status}"
                );
            }

            if (
                $ticket->visit_date->isBefore(
                    today()
                )
            ) {
                $ticket->update([
                    'status' => 'EXPIRED',
                    'expired_at' => now(),
                ]);

                throw new RuntimeException(
                    'Tiket sudah melewati tanggal kunjungan.'
                );
            }

            $oldValues = $ticket->only([
                'status',
                'used_at',
                'used_by',
            ]);

            $ticket->update([
                'status' => 'USED',
                'used_at' => now(),
                'used_by' => $userId,
            ]);

            $ticket->refresh();

            $this->auditLogService->log(
                action: 'SCAN',
                module: 'TICKET',
                model: $ticket,
                description: 'Tiket berhasil digunakan melalui scanner',
                oldValues: $oldValues,
                newValues: $ticket->only([
                    'status',
                    'used_at',
                    'used_by',
                ]),
            );

            return $ticket;
        });
    }
}
