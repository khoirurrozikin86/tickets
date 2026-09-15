<?php

namespace App\Domain\Notifications\Actions;

use App\Domain\Notifications\Jobs\SendOrderTicketEmailJob;
use App\Models\Order;

class SendOrderTicketEmailAction
{
    public function __construct(
        private readonly CreateNotificationLogAction $createNotificationLogAction,
    ) {}

    public function execute(Order $order): void
    {
        if (! $order->customer_email) {
            return;
        }

        $notificationLog = $this->createNotificationLogAction->execute(
            order: $order,
            channel: 'EMAIL',
            type: 'E_TICKET',
            recipient: $order->customer_email,
            status: 'QUEUED',
            metadata: [
                'order_number' => $order->order_number,
            ],
        );

        SendOrderTicketEmailJob::dispatch(
            orderId: $order->id,
            notificationLogId: $notificationLog->id,
        )->afterCommit();
    }
}
