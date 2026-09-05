<?php

namespace App\Http\Controllers\Admin;

use App\Exports\PaymentsExport;
use App\Domain\Payments\Queries\PaymentTableQuery;
use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\Facades\DataTables;

class PaymentController extends Controller
{
    public function index(): View
    {
        return view('super.payments.index');
    }

    public function dt(
        Request $request,
        PaymentTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        /*
        |--------------------------------------------------------------------------
        | Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('payment_number')) {
            $builder->where(
                'payments.payment_number',
                'like',
                '%' . $request->payment_number . '%'
            );
        }

        if ($request->filled('order_number')) {
            $builder->whereHas('order', function ($q) use ($request) {
                $q->where(
                    'order_number',
                    'like',
                    '%' . $request->order_number . '%'
                );
            });
        }

        if ($request->filled('customer')) {
            $customer = $request->customer;

            $builder->whereHas('order', function ($q) use ($customer) {
                $q->where('customer_name', 'like', "%{$customer}%")
                    ->orWhere('customer_email', 'like', "%{$customer}%")
                    ->orWhere('customer_phone', 'like', "%{$customer}%");
            });
        }

        if ($request->filled('gateway')) {
            $builder->where(
                'payments.gateway',
                $request->gateway
            );
        }

        if ($request->filled('payment_method')) {
            $builder->where(
                'payments.payment_method',
                $request->payment_method
            );
        }

        if ($request->filled('status')) {
            $builder->where(
                'payments.status',
                $request->status
            );
        }

        if ($request->filled('date_from')) {
            $builder->whereDate(
                'payments.created_at',
                '>=',
                $request->date_from
            );
        }

        if ($request->filled('date_to')) {
            $builder->whereDate(
                'payments.created_at',
                '<=',
                $request->date_to
            );
        }

        return DataTables::eloquent($builder)

            /*
            |--------------------------------------------------------------------------
            | Order
            |--------------------------------------------------------------------------
            */

            ->addColumn('order_number', function ($payment) {
                return e(
                    $payment->order?->order_number ?? '-'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Customer
            |--------------------------------------------------------------------------
            */

            ->addColumn('customer_name', function ($payment) {
                return e(
                    $payment->order?->customer_name ?? '-'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Amount
            |--------------------------------------------------------------------------
            */

            ->editColumn('amount', function ($payment) {
                return 'Rp ' . number_format(
                    (float) $payment->amount,
                    0,
                    ',',
                    '.'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Status
            |--------------------------------------------------------------------------
            */

            ->editColumn('status', function ($payment) {

                $badges = [
                    'PENDING'   => 'warning',
                    'PAID'      => 'success',
                    'FAILED'    => 'danger',
                    'EXPIRED'   => 'secondary',
                    'CANCELLED' => 'danger',
                    'REFUNDED'  => 'dark',
                ];

                $class = $badges[$payment->status] ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s payment-status">%s</span>',
                    $class,
                    e($payment->status)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Paid At
            |--------------------------------------------------------------------------
            */

            ->editColumn('paid_at', function ($payment) {
                return $payment->paid_at
                    ? $payment->paid_at->format('d/m/Y H:i:s')
                    : '-';
            })

            /*
            |--------------------------------------------------------------------------
            | Created At
            |--------------------------------------------------------------------------
            */

            ->editColumn('created_at', function ($payment) {
                return $payment->created_at
                    ? $payment->created_at->format('d/m/Y H:i:s')
                    : '-';
            })

            /*
            |--------------------------------------------------------------------------
            | Actions
            |--------------------------------------------------------------------------
            */

            ->addColumn('actions', function ($payment) {

                return '
                    <a href="' . route(
                    'super.payments.show',
                    $payment->id
                ) . '"
                    class="payment-action btn-payment-detail"
                    title="Detail">
                        <i data-feather="eye"></i>
                    </a>
                ';
            })

            ->rawColumns([
                'status',
                'actions',
            ])

            ->toJson();
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order.items.product',
            'order.discount',
            'order.tickets',
        ]);

        return view(
            'super.payments.show',
            compact('payment')
        );
    }

    public function export(Request $request): BinaryFileResponse
    {
        $filters = [
            'payment_number' => $request->payment_number,
            'order_number'   => $request->order_number,
            'customer'       => $request->customer,
            'gateway'        => $request->gateway,
            'payment_method' => $request->payment_method,
            'status'         => $request->status,
            'date_from'      => $request->date_from,
            'date_to'        => $request->date_to,
        ];

        return Excel::download(
            new PaymentsExport($filters),
            'payments-' . now()->format('Y-m-d-His') . '.xlsx'
        );
    }
}
