<?php

namespace App\Domain\Notifications\Jobs;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Domain\Invoices\Services\InvoicePdfService;
use App\Domain\Tickets\Services\TicketPdfService;
use App\Mail\OrderTicketMail;
use App\Models\NotificationLog;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Throwable;

class SendOrderTicketEmailJob implements ShouldQueue
{
    use Dispatchable;
    use InteractsWithQueue;
    use Queueable;
    use SerializesModels;

    /**
     * Maksimal percobaan queue.
     */
    public int $tries = 3;

    /**
     * Timeout job dalam detik.
     */
    public int $timeout = 120;

    public function __construct(
        public int $orderId,
        public int $notificationLogId,
    ) {}

    /**
     * Execute the job.
     */
    public function handle(
        TicketPdfService $ticketPdfService,
        InvoicePdfService $invoicePdfService,
        AuditLogService $auditLogService,
    ): void {
        /*
        |--------------------------------------------------------------------------
        | Load Order & Notification
        |--------------------------------------------------------------------------
        */

        $order = Order::query()
            ->with([
                'items',
                'tickets',
                'invoice',
            ])
            ->find($this->orderId);

        $notificationLog = NotificationLog::find(
            $this->notificationLogId
        );

        /*
        |--------------------------------------------------------------------------
        | Data Tidak Ditemukan
        |--------------------------------------------------------------------------
        */

        if (! $order || ! $notificationLog) {
            Log::warning(
                'ORDER TICKET EMAIL: Data tidak ditemukan',
                [
                    'order_id' => $this->orderId,
                    'notification_log_id' => $this->notificationLogId,
                ]
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Customer Email
        |--------------------------------------------------------------------------
        */

        if (! $order->customer_email) {
            $this->markAsFailed(
                $notificationLog,
                'Customer tidak memiliki email.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Order
        |--------------------------------------------------------------------------
        */

        if ($order->status !== 'PAID') {
            $this->markAsFailed(
                $notificationLog,
                'Order belum berstatus PAID.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Ticket
        |--------------------------------------------------------------------------
        */

        if ($order->tickets->isEmpty()) {
            $this->markAsFailed(
                $notificationLog,
                'Order belum memiliki ticket.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Validasi Invoice
        |--------------------------------------------------------------------------
        */

        if (! $order->invoice) {
            $this->markAsFailed(
                $notificationLog,
                'Order belum memiliki invoice.'
            );

            return;
        }

        /*
        |--------------------------------------------------------------------------
        | Generate E-Ticket PDF
        |--------------------------------------------------------------------------
        */

        $ticketPdfContent = $ticketPdfService->generateForOrder(
            $order
        );

        /*
        |--------------------------------------------------------------------------
        | Generate Invoice PDF
        |--------------------------------------------------------------------------
        */

        $invoicePdfContent = $invoicePdfService->generate(
            $order->invoice
        );

        /*
        |--------------------------------------------------------------------------
        | Send Email
        |--------------------------------------------------------------------------
        |
        | Jangan catch exception di sini.
        |
        | Jika SMTP gagal, exception akan dilempar ke Queue.
        | Laravel akan melakukan retry sampai 3 kali.
        |
        */

        Mail::to($order->customer_email)->send(
            new OrderTicketMail(
                order: $order,
                ticketPdfContent: $ticketPdfContent,
                invoicePdfContent: $invoicePdfContent,
            )
        );

        /*
        |--------------------------------------------------------------------------
        | Notification SENT
        |--------------------------------------------------------------------------
        */

        $notificationLog->update([
            'status' => 'SENT',
            'sent_at' => now(),
            'error_message' => null,
        ]);

        /*
        |--------------------------------------------------------------------------
        | Audit Log
        |--------------------------------------------------------------------------
        */

        $this->createAuditLog(
            auditLogService: $auditLogService,
            order: $order,
            notificationLog: $notificationLog,
        );

        /*
        |--------------------------------------------------------------------------
        | Success Log
        |--------------------------------------------------------------------------
        */

        Log::info(
            'ORDER TICKET EMAIL: Berhasil dikirim',
            [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'notification_log_id' => $notificationLog->id,
                'recipient' => $order->customer_email,
            ]
        );
    }

    /**
     * Mark notification as failed.
     */
    private function markAsFailed(
        NotificationLog $notificationLog,
        string $message,
    ): void {
        $notificationLog->update([
            'status' => 'FAILED',
            'error_message' => $message,
        ]);

        Log::warning(
            'ORDER TICKET EMAIL: Validation gagal',
            [
                'notification_log_id' => $notificationLog->id,
                'message' => $message,
            ]
        );
    }

    /**
     * Create notification audit log.
     *
     * Audit failure must never make the email job fail.
     */
    private function createAuditLog(
        AuditLogService $auditLogService,
        Order $order,
        NotificationLog $notificationLog,
    ): void {
        try {
            $auditLogService->log(
                action: 'CREATE',
                module: 'NOTIFICATION',
                model: $order,
                description: "E-Ticket dan Invoice order {$order->order_number} berhasil dikirim melalui email.",
                newValues: [
                    'channel' => 'EMAIL',
                    'type' => 'E_TICKET',
                    'status' => 'SENT',
                    'recipient' => $order->customer_email,
                    'notification_log_id' => $notificationLog->id,
                    'attachments' => [
                        'E-Ticket-' . $order->order_number . '.pdf',
                        'Invoice-' . $order->invoice->invoice_number . '.pdf',
                    ],
                ],
            );
        } catch (Throwable $e) {
            Log::error(
                'ORDER TICKET EMAIL: Audit gagal',
                [
                    'order_id' => $order->id,
                    'notification_log_id' => $notificationLog->id,
                    'error' => $e->getMessage(),
                ]
            );
        }
    }

    /**
     * Handle failed job after all retries.
     */
    public function failed(Throwable $exception): void
    {
        $notificationLog = NotificationLog::find(
            $this->notificationLogId
        );

        if ($notificationLog) {
            $notificationLog->update([
                'status' => 'FAILED',
                'error_message' => $exception->getMessage(),
            ]);
        }

        Log::error(
            'ORDER TICKET EMAIL: Gagal setelah retry',
            [
                'order_id' => $this->orderId,
                'notification_log_id' => $this->notificationLogId,
                'error' => $exception->getMessage(),
                'exception' => get_class($exception),
            ]
        );
    }
}
