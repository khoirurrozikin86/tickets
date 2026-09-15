<?php

namespace App\Domain\Tickets\Actions;

use App\Models\Ticket;

final class ExpirePastDueTicketsAction
{
    public function __construct(
        private readonly ExpireTicketAction $expireTicketAction,
    ) {}

    public function execute(): int
    {
        $tickets = Ticket::query()
            ->where('status', 'ACTIVE')
            ->whereDate('visit_date', '<', today())
            ->get();

        $expiredCount = 0;

        foreach ($tickets as $ticket) {
            $this->expireTicketAction->execute($ticket);

            $expiredCount++;
        }

        return $expiredCount;
    }
}
