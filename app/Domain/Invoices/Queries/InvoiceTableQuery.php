<?php

namespace App\Domain\Invoices\Queries;

use App\Models\Invoice;
use Illuminate\Database\Eloquent\Builder;

class InvoiceTableQuery
{
    public function builder(): Builder
    {
        return Invoice::query()
            ->with([
                'order',
            ])
            ->select('invoices.*');
    }
}