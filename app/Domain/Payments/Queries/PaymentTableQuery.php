<?php

namespace App\Domain\Payments\Queries;

use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;

class PaymentTableQuery
{
    public function builder(): Builder
    {
        return Payment::query()
            ->with([
                'order:id,order_number,customer_name,customer_email,customer_phone',
            ])
            ->select([
                'payments.id',
                'payments.order_id',
                'payments.payment_number',
                'payments.gateway',
                'payments.payment_method',
                'payments.payment_channel',
                'payments.gateway_reference',
                'payments.gateway_transaction_id',
                'payments.amount',
                'payments.currency',
                'payments.status',
                'payments.expired_at',
                'payments.paid_at',
                'payments.cancelled_at',
                'payments.created_at',
            ])
            ->orderByDesc('payments.created_at');
    }
}
