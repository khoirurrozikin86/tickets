<?php

namespace App\Domain\Invoices\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Models\Invoice;
use App\Models\Order;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CreateInvoiceAction
{
    public function __construct(
        private readonly AuditLogService $auditLogService,
    ) {}

    /**
     * Create invoice dari Order yang sudah PAID.
     *
     * Idempotent:
     * - Jika invoice sudah ada, invoice tersebut dikembalikan.
     * - Tidak membuat invoice baru.
     */
    public function execute(Order $order): Invoice
    {
        return DB::transaction(function () use ($order) {

            /*
            |--------------------------------------------------------------------------
            | Pastikan order sudah PAID
            |--------------------------------------------------------------------------
            */

            if (
                $order->status !== 'PAID' ||
                $order->payment_status !== 'PAID'
            ) {
                throw new \RuntimeException(
                    'Invoice hanya dapat dibuat untuk order yang sudah PAID.'
                );
            }

            /*
            |--------------------------------------------------------------------------
            | Lock Order
            |--------------------------------------------------------------------------
            */

            $order = Order::query()
                ->lockForUpdate()
                ->with('items')
                ->findOrFail($order->id);

            /*
            |--------------------------------------------------------------------------
            | Idempotency
            |--------------------------------------------------------------------------
            */

            $existingInvoice = Invoice::query()
                ->where('order_id', $order->id)
                ->first();

            if ($existingInvoice) {
                return $existingInvoice;
            }

            /*
            |--------------------------------------------------------------------------
            | Create Invoice
            |--------------------------------------------------------------------------
            */

            $invoice = Invoice::create([
                'order_id' => $order->id,

                'invoice_number' =>
                    $this->generateInvoiceNumber(),

                'invoice_date' =>
                    now()->toDateString(),

                /*
                | Customer snapshot
                */
                'customer_name' =>
                    $order->customer_name,

                'customer_email' =>
                    $order->customer_email,

                'customer_phone' =>
                    $order->customer_phone,

                /*
                | Amount snapshot
                */
                'subtotal' =>
                    $order->subtotal,

                'discount_amount' =>
                    $order->discount_amount ?? 0,

                'total_amount' =>
                    $order->total_amount,

                'currency' =>
                    $order->currency ?? 'IDR',

                /*
                | Status
                */
                'status' =>
                    'ISSUED',

                'issued_at' =>
                    $order->paid_at ?? now(),

                'metadata' => [
                    'order_number' =>
                        $order->order_number,

                    'payment_status' =>
                        $order->payment_status,

                    'created_from' =>
                        'PAYMENT_CALLBACK',
                ],
            ]);

            /*
            |--------------------------------------------------------------------------
            | Audit Log
            |--------------------------------------------------------------------------
            */

            $this->auditLogService->log(
                action: 'CREATE',
                module: 'INVOICE',
                model: $invoice,
                description:
                    "Membuat invoice {$invoice->invoice_number} untuk order {$order->order_number}.",
                newValues: $invoice->only([
                    'order_id',
                    'invoice_number',
                    'invoice_date',
                    'customer_name',
                    'customer_email',
                    'customer_phone',
                    'subtotal',
                    'discount_amount',
                    'total_amount',
                    'currency',
                    'status',
                    'issued_at',
                ]),
            );

            return $invoice;
        });
    }

    /**
     * Generate nomor invoice unik.
     *
     * Format:
     * INV-20260910-ABC123
     */
    private function generateInvoiceNumber(): string
    {
        do {
            $number =
                'INV-' .
                now()->format('Ymd') .
                '-' .
                strtoupper(Str::random(6));

        } while (
            Invoice::query()
                ->where('invoice_number', $number)
                ->exists()
        );

        return $number;
    }
}