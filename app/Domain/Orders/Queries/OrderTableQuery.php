<?php

namespace App\Domain\Orders\Queries;

use App\Models\Order;
use Illuminate\Database\Eloquent\Builder;

class OrderTableQuery
{
    public function builder(): Builder
    {
        return Order::query()
            ->with([
                'items:id,order_id,product_name,visit_date,quantity,subtotal',
            ])
            ->select([
                'orders.id',
                'orders.order_number',
                'orders.order_token',
                'orders.customer_name',
                'orders.customer_email',
                'orders.customer_phone',
                'orders.subtotal',
                'orders.discount_code',
                'orders.discount_amount',
                'orders.total_amount',
                'orders.currency',
                'orders.status',
                'orders.payment_status',
                'orders.expires_at',
                'orders.paid_at',
                'orders.cancelled_at',
                'orders.completed_at',
                'orders.created_at',
            ])
            ->orderByDesc('orders.created_at');
    }
}
