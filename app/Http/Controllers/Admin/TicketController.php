<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Tickets\Actions\CancelTicketAction;
use App\Domain\Tickets\Queries\TicketTableQuery;
use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Ticket;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;


use App\Exports\TicketsExport;
use Maatwebsite\Excel\Facades\Excel;


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;

class TicketController extends Controller
{
    /**
     * Halaman monitoring ticket.
     */
    public function index()
    {
        $products = Product::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('super.tickets.index', compact('products'));
    }

    /**
     * DataTables ticket.
     */
    public function dt(
        Request $request,
        TicketTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        /*
         * Filter Ticket Number
         */
        if ($request->filled('ticket_number')) {
            $builder->where(
                'tickets.ticket_number',
                'like',
                '%' . $request->ticket_number . '%'
            );
        }

        /*
         * Filter Product
         */
        if ($request->filled('product_id')) {
            $builder->where(
                'tickets.product_id',
                $request->product_id
            );
        }

        /*
         * Filter Status
         */
        if ($request->filled('status')) {
            $builder->where(
                'tickets.status',
                $request->status
            );
        }

        /*
 * Filter tanggal
 *
 * Default:
 * Jika user tidak memilih tanggal,
 * tampilkan ticket hari ini saja.
 */
        $dateFrom = $request->input(
            'date_from',
            now()->format('Y-m-d')
        );

        $dateTo = $request->input(
            'date_to',
            now()->format('Y-m-d')
        );

        $builder->whereDate(
            'tickets.visit_date',
            '>=',
            $dateFrom
        );

        $builder->whereDate(
            'tickets.visit_date',
            '<=',
            $dateTo
        );

        return DataTables::eloquent($builder)

            /*
             * Customer
             */
            ->addColumn('customer_name', function ($ticket) {
                return e(
                    $ticket->order?->customer_name ?? '-'
                );
            })

            /*
             * Status
             */
            ->editColumn('status', function ($ticket) {

                $badges = [
                    'ACTIVE' => 'success',
                    'USED' => 'primary',
                    'CANCELLED' => 'danger',
                    'EXPIRED' => 'secondary',
                ];

                $class = $badges[$ticket->status]
                    ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s ticket-status">%s</span>',
                    $class,
                    e($ticket->status)
                );
            })

            /*
             * Scan information
             */
            ->addColumn('scan', function ($ticket) {

                if (!$ticket->used_at) {
                    return '<span class="text-muted">-</span>';
                }

                $usedBy = $ticket->usedBy?->name;

                return sprintf(
                    '<div>
                        <div>%s</div>
                        <small class="text-muted">%s</small>
                    </div>',
                    e($ticket->used_at->format('d/m/Y H:i:s')),
                    e($usedBy ?? 'System')
                );
            })

            /*
             * Visit date
             */
            ->editColumn('visit_date', function ($ticket) {
                return $ticket->visit_date
                    ? $ticket->visit_date->format('d/m/Y')
                    : '-';
            })

            /*
             * Issued at
             */
            ->editColumn('issued_at', function ($ticket) {
                return $ticket->issued_at
                    ? $ticket->issued_at->format('d/m/Y H:i:s')
                    : '-';
            })

            /*
             * Actions
             */
            /*
 * Actions
 */
            ->addColumn('actions', function ($ticket) {

                $html = '
        <div class="d-flex align-items-center justify-content-center gap-1">

        
            <a href="' .
                    route(
                        'super.tickets.show',
                        $ticket->id
                    ) .
                    '"
            class="ticket-action btn-ticket-detail"
            title="Detail">

                <i data-feather="eye"></i>

            </a>

           
            <a href="' .
                    route(
                        'super.tickets.pdf',
                        $ticket->id
                    ) .
                    '"
            class="ticket-action"
            title="Lihat E-Ticket PDF"
            target="_blank">

                <i data-feather="file-text"></i>

            </a>

    ';

                /*
     * Cancel hanya untuk ACTIVE
     */
                if ($ticket->status === 'ACTIVE') {

                    $html .= '
          
            <a href="#"
            class="ticket-action cancel btn-ticket-cancel"
            data-url="' .
                        route(
                            'super.tickets.cancel',
                            $ticket->id
                        ) .
                        '"
            title="Cancel">

                <i data-feather="x-circle"></i>

            </a>
        ';
                }

                $html .= '
        </div>
    ';

                return $html;
            })

            ->rawColumns([
                'status',
                'scan',
                'actions',
            ])

            ->toJson();
    }

    /**
     * Detail ticket.
     */
    public function show(Ticket $ticket)
    {
        $ticket->load([
            'order',
            'orderItem',
            'product',
            'usedBy',
        ]);

        return view(
            'super.tickets.show',
            compact('ticket')
        );
    }

    /**
     * Cancel ticket.
     */
    public function cancel(
        Ticket $ticket,
        CancelTicketAction $action
    ): JsonResponse|RedirectResponse {

        try {

            $action->execute($ticket);

            if (request()->expectsJson()) {

                return response()->json([
                    'success' => true,
                    'message' => 'Ticket berhasil dibatalkan.',
                ]);
            }

            return back()->with(
                'success',
                'Ticket berhasil dibatalkan.'
            );
        } catch (\Throwable $e) {

            if (request()->expectsJson()) {

                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }




    /**
     * Generate E-Ticket PDF.
     */
    public function pdf(Ticket $ticket)
    {
        $ticket->load([
            'order',
            'orderItem',
            'product',
            'usedBy',
        ]);

        /*
     * Generate QR Code menggunakan Bacon QR Code
     * dan Imagick agar hasilnya PNG.
     */
        $renderer = new ImageRenderer(
            new RendererStyle(220, 10),
            new ImagickImageBackEnd()
        );

        $writer = new Writer($renderer);

        $qrCode = $writer->writeString($ticket->token);

        /*
     * Encode PNG ke Base64 agar bisa langsung
     * digunakan oleh DomPDF tanpa menyimpan file.
     */
        $qrBase64 = base64_encode($qrCode);

        return Pdf::loadView(
            'super.tickets.pdf',
            compact(
                'ticket',
                'qrBase64'
            )
        )
            ->setPaper('a4', 'portrait')
            ->stream(
                $ticket->ticket_number . '.pdf'
            );
    }


    public function export(Request $request)
    {
        $today = now()->format('Y-m-d');

        $filters = [
            'ticket_number' => $request->input('ticket_number'),
            'product_id'    => $request->input('product_id'),
            'status'        => $request->input('status'),

            'date_from' => $request->input(
                'date_from',
                $today
            ),

            'date_to' => $request->input(
                'date_to',
                $today
            ),
        ];

        return Excel::download(
            new TicketsExport($filters),
            'tickets-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
