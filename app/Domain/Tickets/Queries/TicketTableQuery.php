<?php

namespace App\Domain\Tickets\Queries;

use App\Models\Ticket;
use Illuminate\Database\Eloquent\Builder;

class TicketTableQuery
{
    public function builder(): Builder
    {
        return Ticket::query()
            ->with([
                'order:id,customer_name',
                'usedBy:id,name',
                'product:id,name',
            ])
            ->select([
                'tickets.id',
                'tickets.order_id',
                'tickets.order_item_id',
                'tickets.product_id',
                'tickets.ticket_number',
                'tickets.product_name',
                'tickets.visit_date',
                'tickets.status',
                'tickets.issued_at',
                'tickets.used_at',
                'tickets.expired_at',
                'tickets.used_by',
                'tickets.created_at',
            ])
            ->orderBy(
                'tickets.created_at',
                'desc'
            );
    }
}
