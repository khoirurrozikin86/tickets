<?php

namespace App\Domain\Tickets\Actions;

use App\Domain\Tickets\Services\TicketService;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class GenerateTicketsForOrderAction
{
    public function __construct(
        private readonly TicketService $ticketService,
    ) {}

    /**
     * Generate semua ticket untuk order yang sudah PAID.
     *
     * Idempotent:
     * - Kalau ticket sudah lengkap → tidak membuat ticket baru.
     * - Kalau sebagian sudah ada → hanya membuat kekurangannya.
     */
    public function execute(Order $order): array
    {
        return DB::transaction(function () use ($order) {

            $order->loadMissing('items');

            $tickets = [];

            foreach ($order->items as $orderItem) {

                $existingCount = $orderItem->tickets()->count();

                $requiredQuantity = (int) $orderItem->quantity;

                $remainingQuantity = $requiredQuantity - $existingCount;

                if ($remainingQuantity <= 0) {
                    continue;
                }

                $created = $this->ticketService->create(
                    orderItem: $orderItem,
                    quantity: $remainingQuantity,
                );

                $tickets = array_merge(
                    $tickets,
                    $created
                );
            }

            return $tickets;
        });
    }
}