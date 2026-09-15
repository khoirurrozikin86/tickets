<?php

namespace App\Domain\Notifications\Actions;

use App\Models\NotificationLog;
use App\Models\Order;

class CreateNotificationLogAction
{
    public function execute(
        Order $order,
        string $channel,
        string $type,
        string $recipient,
        string $status = 'QUEUED',
        ?string $errorMessage = null,
        ?array $metadata = null,
    ): NotificationLog {
        return NotificationLog::create([
            'order_id' => $order->id,
            'channel' => $channel,
            'type' => $type,
            'recipient' => $recipient,
            'status' => $status,
            'queued_at' => now(),
            'sent_at' => $status === 'SENT' ? now() : null,
            'error_message' => $errorMessage,
            'metadata' => $metadata,
        ]);
    }
}
