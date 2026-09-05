<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateTicketAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(
        OrderItem $orderItem,
        int $quantity
    ): array {
        return DB::transaction(function () use ($orderItem, $quantity) {

            $tickets = [];

            for ($i = 0; $i < $quantity; $i++) {

                $ticket = Ticket::create([
                    'order_id' => $orderItem->order_id,
                    'order_item_id' => $orderItem->id,
                    'product_id' => $orderItem->product_id,
                    'ticket_number' => $this->generateTicketNumber(),
                    'token' => $this->generateToken(),
                    'product_name' => $orderItem->product_name,
                    'visit_date' => $orderItem->visit_date,
                    'status' => 'ACTIVE',
                    'issued_at' => now(),
                ]);

                $this->auditLogService->log(
                    action: 'CREATE',
                    module: 'TICKET',
                    model: $ticket,
                    description: 'Membuat e-ticket',
                    newValues: $ticket->only([
                        'order_id',
                        'order_item_id',
                        'product_id',
                        'ticket_number',
                        'product_name',
                        'visit_date',
                        'status',
                        'issued_at',
                    ]),
                );

                $tickets[] = $ticket;
            }

            return $tickets;
        });
    }

    private function generateToken(): string
    {
        do {
            $token = Str::random(64);
        } while (
            Ticket::where('token', $token)->exists()
        );

        return $token;
    }

    private function generateTicketNumber(): string
    {
        do {
            $number = 'TKT-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(8));
        } while (
            Ticket::where('ticket_number', $number)->exists()
        );

        return $number;
    }
}
