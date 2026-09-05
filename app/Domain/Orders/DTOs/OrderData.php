<?php

namespace App\Domain\Tickets\DTOs;

use App\Models\OrderItem;

class TicketData
{
    public function __construct(
        public int $order_id,
        public int $order_item_id,
        public int $product_id,
        public string $ticket_number,
        public string $token,
        public string $product_name,
        public string $visit_date,
        public string $status = 'ACTIVE',
        public ?string $issued_at = null,
        public ?string $used_at = null,
        public ?string $expired_at = null,
        public ?int $used_by = null,
        public ?array $metadata = null,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            order_id: (int) $data['order_id'],
            order_item_id: (int) $data['order_item_id'],
            product_id: (int) $data['product_id'],
            ticket_number: $data['ticket_number'],
            token: $data['token'],
            product_name: $data['product_name'],
            visit_date: $data['visit_date'],
            status: strtoupper($data['status'] ?? 'ACTIVE'),
            issued_at: $data['issued_at'] ?? null,
            used_at: $data['used_at'] ?? null,
            expired_at: $data['expired_at'] ?? null,
            used_by: isset($data['used_by'])
                ? (int) $data['used_by']
                : null,
            metadata: $data['metadata'] ?? null,
        );
    }

    /**
     * Membuat data ticket dari OrderItem.
     */
    public static function fromOrderItem(
        OrderItem $orderItem,
        string $ticketNumber,
        string $token,
    ): self {
        return new self(
            order_id: $orderItem->order_id,
            order_item_id: $orderItem->id,
            product_id: $orderItem->product_id,
            ticket_number: $ticketNumber,
            token: $token,
            product_name: $orderItem->product_name,
            visit_date: $orderItem->visit_date->toDateString(),
            status: 'ACTIVE',
            issued_at: now()->toDateTimeString(),
            metadata: [
                'source' => 'PAYMENT',
            ],
        );
    }

    public function toArray(): array
    {
        return [
            'order_id' => $this->order_id,
            'order_item_id' => $this->order_item_id,
            'product_id' => $this->product_id,
            'ticket_number' => $this->ticket_number,
            'token' => $this->token,
            'product_name' => $this->product_name,
            'visit_date' => $this->visit_date,
            'status' => $this->status,
            'issued_at' => $this->issued_at,
            'used_at' => $this->used_at,
            'expired_at' => $this->expired_at,
            'used_by' => $this->used_by,
            'metadata' => $this->metadata,
        ];
    }
}
