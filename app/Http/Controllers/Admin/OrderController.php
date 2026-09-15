<?php

namespace App\Http\Controllers\Admin;


use App\Domain\Orders\Queries\OrderTableQuery;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

use App\Exports\OrdersExport;
use Maatwebsite\Excel\Facades\Excel;


use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index(): View
    {
        return view('super.orders.index');
    }

    public function dt(
        Request $request,
        OrderTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_number')) {
            $builder->where(
                'orders.order_number',
                'like',
                '%' . $request->order_number . '%'
            );
        }

        if ($request->filled('customer')) {
            $builder->where(function ($q) use ($request) {
                $keyword = '%' . $request->customer . '%';

                $q->where('orders.customer_name', 'like', $keyword)
                    ->orWhere('orders.customer_email', 'like', $keyword)
                    ->orWhere('orders.customer_phone', 'like', $keyword);
            });
        }

        if ($request->filled('payment_status')) {
            $builder->where(
                'orders.payment_status',
                $request->payment_status
            );
        }

        if ($request->filled('status')) {
            $builder->where(
                'orders.status',
                $request->status
            );
        }
        /*
|--------------------------------------------------------------------------
| Filter tanggal
|
| Default:
| Jika tanggal tidak dikirim, tampilkan order hari ini.
|--------------------------------------------------------------------------
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
            'orders.created_at',
            '>=',
            $dateFrom
        );

        $builder->whereDate(
            'orders.created_at',
            '<=',
            $dateTo
        );

        return DataTables::eloquent($builder)

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            ->addColumn('customer', function ($order) {
                return '
                    <div>
                        <div class="fw-semibold">'
                    . e($order->customer_name) .
                    '</div>

                        <small class="text-muted">'
                    . e($order->customer_email) .
                    '</small>
                    </div>
                ';
            })

            /*
            |--------------------------------------------------------------------------
            | Items
            |--------------------------------------------------------------------------
            */

            ->addColumn('items', function ($order) {
                if ($order->items->isEmpty()) {
                    return '<span class="text-muted">-</span>';
                }

                $html = '';

                foreach ($order->items as $item) {
                    $html .= '
                        <div class="mb-1">
                            <span class="fw-semibold">'
                        . e($item->product_name) .
                        '</span>

                            <small class="text-muted">
                                × ' . e($item->quantity) . '
                            </small>
                        </div>
                    ';
                }

                return $html;
            })

            /*
            |--------------------------------------------------------------------------
            | Total
            |--------------------------------------------------------------------------
            */

            ->editColumn('total_amount', function ($order) {
                return 'Rp ' . number_format(
                    (float) $order->total_amount,
                    0,
                    ',',
                    '.'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Payment Status
            |--------------------------------------------------------------------------
            */

            ->editColumn('payment_status', function ($order) {

                $badges = [
                    'UNPAID' => 'secondary',
                    'PENDING' => 'warning',
                    'PAID' => 'success',
                    'FAILED' => 'danger',
                    'EXPIRED' => 'secondary',
                    'REFUNDED' => 'info',
                ];

                $class = $badges[$order->payment_status] ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $class,
                    e($order->payment_status)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Order Status
            |--------------------------------------------------------------------------
            */

            ->editColumn('status', function ($order) {

                $badges = [
                    'PENDING' => 'warning',
                    'PAID' => 'success',
                    'COMPLETED' => 'primary',
                    'CANCELLED' => 'danger',
                    'EXPIRED' => 'secondary',
                    'REFUNDED' => 'info',
                ];

                $class = $badges[$order->status] ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $class,
                    e($order->status)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Date
            |--------------------------------------------------------------------------
            */

            ->editColumn('created_at', function ($order) {
                return $order->created_at
                    ? $order->created_at->format('d/m/Y H:i:s')
                    : '-';
            })

            /*
            |--------------------------------------------------------------------------
            | Actions
            |--------------------------------------------------------------------------
            */

            ->addColumn('actions', function ($order) {

                return '
        <div class="d-flex align-items-center justify-content-center gap-1">

        
            <a href="' .
                    route(
                        'super.orders.show',
                        $order->id
                    ) .
                    '"
            class="order-action"
            title="Detail Order">

                <i data-feather="eye"></i>

            </a>

          
            <a href="' .
                    route(
                        'super.orders.tickets.pdf',
                        $order->id
                    ) .
                    '"
            class="order-action"
            title="Lihat Semua E-Ticket"
            target="_blank">

                <i data-feather="file-text"></i>

            </a>

        </div>
    ';
            })

            ->rawColumns([
                'customer',
                'items',
                'total_amount',
                'payment_status',
                'status',
                'actions',
            ])

            ->toJson();
    }

    public function show(Order $order): View
    {
        $order->load([
            'items.product',
            'payments',
            'tickets',
            'discount',
        ]);

        return view(
            'super.orders.show',
            compact('order')
        );
    }

    public function export(Request $request)
    {
        $today = now()->format('Y-m-d');

        $filters = [

            'order_number' => $request->input(
                'order_number'
            ),

            'customer' => $request->input(
                'customer'
            ),

            'payment_status' => $request->input(
                'payment_status'
            ),

            'status' => $request->input(
                'status'
            ),

            /*
        |--------------------------------------------------------------------------
        | Default tanggal = hari ini
        |--------------------------------------------------------------------------
        */

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

            new OrdersExport($filters),

            'orders-' .
                now()->format('Y-m-d-His') .
                '.xlsx'

        );
    }


    public function ticketsPdf(Order $order)
    {
        $order->load([
            'tickets',
            'items',
        ]);

        $tickets = $order->tickets
            ->sortBy('id')
            ->values();

        if ($tickets->isEmpty()) {
            abort(404, 'Order ini belum memiliki ticket.');
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
            compact(
                'order',
                'tickets',
                'qrCodes'
            )
        )
            ->setPaper('a4', 'portrait')
            ->stream(
                'E-Ticket-' . $order->order_number . '.pdf'
            );
    }
}
