<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\InvoicesExport;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    /**
     * Invoice List
     */
    public function index()
    {
        return view('super.invoices.index');
    }

    /**
     * Invoice DataTable
     */
    public function dt(Request $request)
    {
        $query = Invoice::query()
            ->with('order')
            ->latest('created_at');


        /*
    |--------------------------------------------------------------------------
    | FILTER INVOICE NUMBER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('invoice_number')) {

            $query->where(
                'invoice_number',
                'like',
                '%' . $request->invoice_number . '%'
            );
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER ORDER NUMBER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('order_number')) {

            $query->whereHas('order', function ($q) use ($request) {

                $q->where(
                    'order_number',
                    'like',
                    '%' . $request->order_number . '%'
                );
            });
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER CUSTOMER
    |--------------------------------------------------------------------------
    */

        if ($request->filled('customer')) {

            $customer = $request->customer;

            $query->where(function ($q) use ($customer) {

                $q->where(
                    'customer_name',
                    'like',
                    '%' . $customer . '%'
                )

                    ->orWhere(
                        'customer_email',
                        'like',
                        '%' . $customer . '%'
                    )

                    ->orWhere(
                        'customer_phone',
                        'like',
                        '%' . $customer . '%'
                    );
            });
        }


        /*
    |--------------------------------------------------------------------------
    | FILTER STATUS
    |--------------------------------------------------------------------------
    */

        if ($request->filled('status')) {

            $query->where(
                'status',
                $request->status
            );
        }


        /*
   
    /*
    |--------------------------------------------------------------------------
    | FILTER DATE
    |--------------------------------------------------------------------------
    | Default: hari ini
    */

        $dateFrom = $request->input(
            'date_from',
            now()->format('Y-m-d')
        );

        $dateTo = $request->input(
            'date_to',
            now()->format('Y-m-d')
        );

        $query->whereDate(
            'invoice_date',
            '>=',
            $dateFrom
        );

        $query->whereDate(
            'invoice_date',
            '<=',
            $dateTo
        );



        /*
    |--------------------------------------------------------------------------
    | DATATABLE
    |--------------------------------------------------------------------------
    */

        return DataTables::of($query)

            /*
        |--------------------------------------------------------------------------
        | Order Number
        |--------------------------------------------------------------------------
        */

            ->addColumn(
                'order_number',
                fn($invoice) =>
                $invoice->order?->order_number ?? '-'
            )


            /*
        |--------------------------------------------------------------------------
        | Invoice Number
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'invoice_number',
                fn($invoice) =>
                e($invoice->invoice_number)
            )


            /*
        |--------------------------------------------------------------------------
        | Customer
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'customer_name',
                fn($invoice) =>
                e($invoice->customer_name ?: '-')
            )


            /*
        |--------------------------------------------------------------------------
        | Total Amount
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'total_amount',
                fn($invoice) =>
                'Rp ' . number_format(
                    (float) $invoice->total_amount,
                    0,
                    ',',
                    '.'
                )
            )


            /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'status',
                function ($invoice) {

                    $badges = [
                        'ISSUED' => 'success',
                        'CANCELLED' => 'danger',
                        'VOID' => 'secondary',
                    ];

                    $badgeClass =
                        $badges[$invoice->status]
                        ?? 'secondary';

                    return '
                    <span class="badge bg-' . $badgeClass . ' invoice-status">
                        ' . e($invoice->status) . '
                    </span>
                ';
                }
            )


            /*
        |--------------------------------------------------------------------------
        | Invoice Date
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'invoice_date',
                fn($invoice) =>
                $invoice->invoice_date
                    ? $invoice->invoice_date->format('d/m/Y')
                    : '-'
            )


            /*
        |--------------------------------------------------------------------------
        | Issued At
        |--------------------------------------------------------------------------
        */

            ->editColumn(
                'issued_at',
                fn($invoice) =>
                $invoice->issued_at
                    ? $invoice->issued_at->format('d/m/Y H:i:s')
                    : '-'
            )


            /*
        |--------------------------------------------------------------------------
        | Actions
        |--------------------------------------------------------------------------
        */

            ->addColumn('actions', function ($invoice) {

                $showUrl = route(
                    'super.invoices.show',
                    ['invoice' => $invoice->id]
                );

                $pdfUrl = route(
                    'super.invoices.pdf',
                    ['invoice' => $invoice->id]
                );

                return '
        <div class="d-flex justify-content-center gap-1">

            <a
                href="' . $showUrl . '"
                class="invoice-action"
                title="Detail Invoice"
            >
                <i data-feather="eye"></i>
            </a>

            <a
                href="' . $pdfUrl . '"
                class="invoice-action"
                title="Lihat PDF"
                target="_blank"
            >
                <i data-feather="file-text"></i>
            </a>

        </div>
    ';
            })


            /*
        |--------------------------------------------------------------------------
        | RAW HTML
        |--------------------------------------------------------------------------
        */

            ->rawColumns([
                'status',
                'actions',
            ])

            ->make(true);
    }

    /**
     * Invoice Detail
     */
    public function show(Invoice $invoice)
    {
        $invoice->load([
            'order.items',
            'order.payments',
            'order.tickets',
        ]);

        return view(
            'super.invoices.show',
            compact('invoice')
        );
    }

    public function export(Request $request)
    {
        $today = now()->format('Y-m-d');

        $filters = [
            'invoice_number' => $request->input('invoice_number'),
            'order_number' => $request->input('order_number'),
            'customer' => $request->input('customer'),
            'status' => $request->input('status'),
            'date_from' => $request->input('date_from', $today),
            'date_to' => $request->input('date_to', $today),
        ];

        return Excel::download(
            new InvoicesExport($filters),
            'invoices-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }

    public function pdf(Invoice $invoice)
    {
        $invoice->load([
            'order.items',
            'order.payments',
            'order.tickets',
        ]);

        return Pdf::loadView(
            'super.invoices.pdf',
            compact('invoice')
        )
            ->setPaper('a4', 'portrait')
            ->stream(
                $invoice->invoice_number . '.pdf'
            );
    }
}
