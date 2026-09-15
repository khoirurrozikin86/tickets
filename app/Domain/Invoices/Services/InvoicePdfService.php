<?php

namespace App\Domain\Invoices\Services;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use RuntimeException;

class InvoicePdfService
{
    public function generate(Invoice $invoice): string
    {
        $invoice->loadMissing([
            'order.items',
            'order.payments',
            'order.tickets',
        ]);

        if (! $invoice->order) {
            throw new RuntimeException(
                'Invoice tidak memiliki order.'
            );
        }

        return Pdf::loadView(
            'super.invoices.pdf',
            compact('invoice')
        )
            ->setPaper('a4', 'portrait')
            ->output();
    }
}
