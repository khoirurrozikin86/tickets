<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Notifications\Queries\NotificationTableQuery;
use App\Http\Controllers\Controller;
use App\Models\NotificationLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class NotificationController extends Controller
{
    public function index()
    {
        return view('super.notifications.index', [
            'defaultDate' => now()->toDateString(),
        ]);
    }

    public function dt(
        Request $request,
        NotificationTableQuery $notificationTableQuery
    ) {
        $query = $notificationTableQuery->builder();

        /*
        |--------------------------------------------------------------------------
        | Order Number
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_number')) {
            $orderNumber = trim($request->order_number);

            $query->whereHas('order', function ($q) use ($orderNumber) {
                $q->where(
                    'order_number',
                    'like',
                    "%{$orderNumber}%"
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Recipient
        |--------------------------------------------------------------------------
        */

        if ($request->filled('recipient')) {
            $query->where(
                'notification_logs.recipient',
                'like',
                '%' . trim($request->recipient) . '%'
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Channel
        |--------------------------------------------------------------------------
        */

        if ($request->filled('channel')) {
            $query->where(
                'notification_logs.channel',
                $request->channel
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Type
        |--------------------------------------------------------------------------
        */

        if ($request->filled('type')) {
            $query->where(
                'notification_logs.type',
                $request->type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Status
        |--------------------------------------------------------------------------
        */

        if ($request->filled('status')) {
            $query->where(
                'notification_logs.status',
                $request->status
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        |
        | Jika date_from dan date_to kosong,
        | otomatis hanya menampilkan hari ini.
        |
        */

        $dateFrom = $request->input('date_from');
        $dateTo = $request->input('date_to');

        if ($dateFrom) {
            $query->whereDate(
                'notification_logs.created_at',
                '>=',
                $dateFrom
            );
        }

        if ($dateTo) {
            $query->whereDate(
                'notification_logs.created_at',
                '<=',
                $dateTo
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Default: Hari Ini
        |--------------------------------------------------------------------------
        */

        if (! $dateFrom && ! $dateTo) {
            $query->whereDate(
                'notification_logs.created_at',
                now()->toDateString()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DataTable
        |--------------------------------------------------------------------------
        */

        return DataTables::of($query)

            ->addColumn(
                'order_number',
                fn($notification) =>
                $notification->order?->order_number ?? '-'
            )

            ->editColumn(
                'channel',
                fn($notification) =>
                '<span class="badge bg-primary">'
                    . e($notification->channel)
                    . '</span>'
            )

            ->editColumn(
                'type',
                fn($notification) =>
                '<span class="badge bg-secondary">'
                    . e($notification->type)
                    . '</span>'
            )

            ->editColumn(
                'recipient',
                fn($notification) =>
                e($notification->recipient)
            )

            ->editColumn(
                'status',
                function ($notification) {
                    $class = match ($notification->status) {
                        'SENT' => 'bg-success',
                        'FAILED' => 'bg-danger',
                        'QUEUED' => 'bg-warning text-dark',
                        default => 'bg-secondary',
                    };

                    return '<span class="badge ' . $class . '">'
                        . e($notification->status)
                        . '</span>';
                }
            )

            ->editColumn(
                'queued_at',
                fn($notification) =>
                $notification->queued_at
                    ? $notification->queued_at->format('d-m-Y H:i:s')
                    : '-'
            )

            ->editColumn(
                'sent_at',
                fn($notification) =>
                $notification->sent_at
                    ? $notification->sent_at->format('d-m-Y H:i:s')
                    : '-'
            )

            ->addColumn('actions', function ($notification) {
                return '
                    <div class="d-flex align-items-center justify-content-center gap-1">
                        <a href="' . route(
                    'super.notifications.show',
                    $notification->id
                ) . '"
                           class="order-action"
                           title="Detail Notification">
                            <i data-feather="eye"></i>
                        </a>
                    </div>
                ';
            })

            ->rawColumns([
                'channel',
                'type',
                'status',
                'actions',
            ])

            ->make(true);
    }

    public function show(NotificationLog $notification)
    {
        $notification->load('order');

        return view(
            'super.notifications.show',
            compact('notification')
        );
    }
}
