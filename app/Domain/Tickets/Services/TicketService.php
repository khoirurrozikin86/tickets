<?php

namespace App\Domain\Tickets\Services;

use App\Domain\Tickets\Actions\CancelTicketAction;
use App\Domain\Tickets\Actions\CreateTicketAction;
use App\Domain\Tickets\Actions\ExpireTicketAction;
use App\Domain\Tickets\Actions\MarkTicketAsUsedAction;
use App\Domain\Tickets\DTOs\TicketData;
use App\Models\OrderItem;
use App\Models\Ticket;
use Illuminate\Support\Collection;

class TicketService
{
    public function __construct(
        private readonly CreateTicketAction $createTicketAction,
        private readonly MarkTicketAsUsedAction $markTicketAsUsedAction,
        private readonly CancelTicketAction $cancelTicketAction,
        private readonly ExpireTicketAction $expireTicketAction,
    ) {}

    /**
     * Generate ticket berdasarkan quantity OrderItem.
     */
    public function createFromOrderItem(
        OrderItem $orderItem
    ): array {
        return $this->createTicketAction->execute(
            orderItem: $orderItem,
            quantity: $orderItem->quantity,
        );
    }

    /**
     * Generate ticket dengan quantity tertentu.
     */
    public function create(
        OrderItem $orderItem,
        int $quantity
    ): array {
        return $this->createTicketAction->execute(
            orderItem: $orderItem,
            quantity: $quantity,
        );
    }

    /**
     * Generate ticket dari seluruh item dalam Order.
     */
    public function createFromOrderItems(
        Collection $orderItems
    ): array {
        $tickets = [];

        foreach ($orderItems as $orderItem) {
            $created = $this->createFromOrderItem(
                $orderItem
            );

            $tickets = array_merge(
                $tickets,
                $created
            );
        }

        return $tickets;
    }

    /**
     * Menandai ticket sebagai USED.
     * Dipakai oleh scanner.
     */
    public function markAsUsed(
        Ticket $ticket,
        int $userId
    ): Ticket {
        return $this->markTicketAsUsedAction->execute(
            ticket: $ticket,
            userId: $userId,
        );
    }

    /**
     * Membatalkan ticket.
     */
    public function cancel(
        Ticket $ticket
    ): Ticket {
        return $this->cancelTicketAction->execute(
            ticket: $ticket,
        );
    }

    /**
     * Membuat ticket menjadi EXPIRED.
     */
    public function expire(
        Ticket $ticket
    ): Ticket {
        return $this->expireTicketAction->execute(
            ticket: $ticket,
        );
    }
}
