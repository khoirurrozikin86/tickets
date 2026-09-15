<?php

namespace App\Domain\Notifications\Services;

use App\Domain\Notifications\Actions\SendOrderTicketEmailAction;
use App\Models\Order;

class NotificationService
{
    public function __construct(
        private readonly SendOrderTicketEmailAction $sendOrderTicketEmailAction,
    ) {}

    public function sendOrderTicketEmail(Order $order): void
    {
        $this->sendOrderTicketEmailAction->execute($order);
    }
}
