<?php

namespace App\Domain\Invoices\Services;

use App\Domain\Invoices\Actions\CreateInvoiceAction;
use App\Models\Invoice;
use App\Models\Order;

class InvoiceService
{
    public function __construct(
        private readonly CreateInvoiceAction $createInvoiceAction,
    ) {}

    /**
     * Membuat invoice dari order yang sudah PAID.
     */
    public function createFromOrder(
        Order $order
    ): Invoice {
        return $this->createInvoiceAction->execute(
            order: $order,
        );
    }

    /**
     * Ambil invoice berdasarkan order.
     */
    public function findByOrder(
        Order $order
    ): ?Invoice {
        return Invoice::query()
            ->where('order_id', $order->id)
            ->first();
    }

    /**
     * Ambil invoice berdasarkan nomor invoice.
     */
    public function findByInvoiceNumber(
        string $invoiceNumber
    ): ?Invoice {
        return Invoice::query()
            ->where('invoice_number', $invoiceNumber)
            ->first();
    }
}