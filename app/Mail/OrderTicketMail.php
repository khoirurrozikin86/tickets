<?php

namespace App\Mail;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OrderTicketMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Order $order,
        public string $ticketPdfContent,
        public string $invoicePdfContent,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'E-Ticket Dusun Semilir - '
                . $this->order->order_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'super.emails.order-ticket',
        );
    }

    public function attachments(): array
    {
        return [
            Attachment::fromData(
                fn() => $this->ticketPdfContent,
                'E-Ticket-' . $this->order->order_number . '.pdf'
            )->withMime('application/pdf'),

            Attachment::fromData(
                fn() => $this->invoicePdfContent,
                'Invoice-' . $this->order->invoice->invoice_number . '.pdf'
            )->withMime('application/pdf'),
        ];
    }
}
