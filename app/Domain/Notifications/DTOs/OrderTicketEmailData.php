<?php

namespace App\Domain\Notifications\DTOs;

use App\Models\Order;

class OrderTicketEmailData
{
    public function __construct(
        public readonly int $orderId,
        public readonly string $recipient,
    ) {}

    public static function fromOrder(Order $order): self
    {
        return new self(
            orderId: $order->id,
            recipient: $order->customer_email,
        );
    }
}
