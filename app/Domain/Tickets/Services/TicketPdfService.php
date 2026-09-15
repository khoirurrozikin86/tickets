<?php

namespace App\Domain\Tickets\Services;

use App\Models\Order;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;
use RuntimeException;

class TicketPdfService
{
    public function generateForOrder(Order $order): string
    {
        $order->loadMissing([
            'items',
            'tickets.product',
            'tickets.orderItem',
        ]);

        $tickets = $order->tickets
            ->sortBy('id')
            ->values();

        if ($tickets->isEmpty()) {
            throw new RuntimeException(
                'Order ini belum memiliki ticket.'
            );
        }

        $qrCodes = [];

        foreach ($tickets as $ticket) {
            $renderer = new ImageRenderer(
                new RendererStyle(220, 10),
                new ImagickImageBackEnd()
            );

            $writer = new Writer($renderer);

            $qrCodes[$ticket->id] = base64_encode(
                $writer->writeString($ticket->token)
            );
        }

        return Pdf::loadView(
            'super.orders.tickets-pdf',
            [
                'order' => $order,
                'tickets' => $tickets,
                'qrCodes' => $qrCodes,
            ]
        )
            ->setPaper('a4', 'portrait')
            ->output();
    }
}
