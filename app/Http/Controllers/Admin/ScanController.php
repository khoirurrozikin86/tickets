<?php

namespace App\Http\Controllers\Admin;

use App\Domain\ScanRecords\DTOs\ScanTicketData;
use App\Domain\ScanRecords\Queries\ScanTableQuery;
use App\Domain\ScanRecords\Services\ScanService;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

use App\Exports\ScanExport;
use Maatwebsite\Excel\Facades\Excel;


use Yajra\DataTables\Facades\DataTables;




class ScanController extends Controller
{
    public function __construct(
        private readonly ScanService $scanService,
    ) {}

    /*
    |--------------------------------------------------------------------------
    | Scan Barcode
    |--------------------------------------------------------------------------
    */

    public function barcode(): View
    {
        return view('super.scan.barcode');
    }

    /*
    |--------------------------------------------------------------------------
    | Scan Camera
    |--------------------------------------------------------------------------
    */

    public function camera(): View
    {
        return view('super.scan.camera');
    }

    /*
    |--------------------------------------------------------------------------
    | Process Scan
    |--------------------------------------------------------------------------
    */

    public function scan(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'token' => [
                'required',
                'string',
                'max:255',
            ],
        ]);

        $scanRecord = $this->scanService->scan(
            new ScanTicketData(
                token: $validated['token'],
                userId: (int) auth()->id(),
                ipAddress: $request->ip(),
                userAgent: $request->userAgent(),
            )
        );

        $scanRecord->load('ticket');

        $ticket = $scanRecord->ticket;

        return response()->json([
            'success' => $scanRecord->result === 'SUCCESS',

            'result' => $scanRecord->result,

            'reason' => $scanRecord->reason,

            'message' => $this->getScanMessage(
                $scanRecord->reason
            ),

            'ticket' => $ticket
                ? [
                    'id' => $ticket->id,
                    'ticket_number' => $ticket->ticket_number,
                    'product_name' => $ticket->product_name,
                    'visit_date' => $ticket->visit_date?->format('Y-m-d'),
                    'status' => $ticket->status,
                    'used_at' => $ticket->used_at?->toDateTimeString(),
                ]
                : null,
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | Monitoring Page
    |--------------------------------------------------------------------------
    */

    public function monitoring(): View
    {
        return view('super.scan.monitoring');
    }

    /*
    |--------------------------------------------------------------------------
    | DataTables
    |--------------------------------------------------------------------------
    */

    public function dt(
        Request $request,
        ScanTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        /*
        |--------------------------------------------------------------------------
        | Default Date
        |--------------------------------------------------------------------------
        |
        | Kalau tidak dikirim dari DataTables:
        | tampilkan data hari ini.
        |
        */

        $dateFrom = $request->input(
            'date_from',
            now()->format('Y-m-d')
        );

        $dateTo = $request->input(
            'date_to',
            now()->format('Y-m-d')
        );

        /*
        |--------------------------------------------------------------------------
        | Date From
        |--------------------------------------------------------------------------
        */

        $builder->whereDate(
            'scan_records.scanned_at',
            '>=',
            $dateFrom
        );

        /*
        |--------------------------------------------------------------------------
        | Date To
        |--------------------------------------------------------------------------
        */

        $builder->whereDate(
            'scan_records.scanned_at',
            '<=',
            $dateTo
        );

        /*
        |--------------------------------------------------------------------------
        | Result Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('result')) {
            $builder->where(
                'scan_records.result',
                $request->string('result')->toString()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Reason Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('reason')) {
            $builder->where(
                'scan_records.reason',
                $request->string('reason')->toString()
            );
        }

        /*
        |--------------------------------------------------------------------------
        | DataTables
        |--------------------------------------------------------------------------
        */

        return DataTables::eloquent($builder)

            /*
            |--------------------------------------------------------------------------
            | Ticket Number
            |--------------------------------------------------------------------------
            */

            ->addColumn('ticket_number', function ($record) {
                return e(
                    $record->ticket?->ticket_number ?? '-'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Product
            |--------------------------------------------------------------------------
            */

            ->addColumn('product_name', function ($record) {
                return e(
                    $record->ticket?->product_name ?? '-'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Visit Date
            |--------------------------------------------------------------------------
            */

            ->addColumn('visit_date', function ($record) {
                return $record->ticket?->visit_date
                    ? $record->ticket->visit_date->format('d/m/Y')
                    : '-';
            })

            /*
            |--------------------------------------------------------------------------
            | Result
            |--------------------------------------------------------------------------
            */

            ->editColumn('result', function ($record) {

                $badges = [
                    'SUCCESS' => 'success',
                    'FAILED' => 'danger',
                ];

                $class = $badges[$record->result]
                    ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $class,
                    e($record->result)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Reason
            |--------------------------------------------------------------------------
            */

            ->editColumn('reason', function ($record) {

                $badges = [
                    'VALID' => 'success',
                    'NOT_FOUND' => 'danger',
                    'ALREADY_USED' => 'warning',
                    'EXPIRED' => 'secondary',
                    'CANCELLED' => 'dark',
                    'INVALID_STATUS' => 'secondary',
                ];

                $class = $badges[$record->reason]
                    ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $class,
                    e($record->reason)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Ticket Status
            |--------------------------------------------------------------------------
            */

            ->addColumn('ticket_status', function ($record) {

                if (! $record->ticket) {
                    return '-';
                }

                $badges = [
                    'ACTIVE' => 'success',
                    'USED' => 'secondary',
                    'EXPIRED' => 'danger',
                    'CANCELLED' => 'dark',
                ];

                $class = $badges[$record->ticket->status]
                    ?? 'secondary';

                return sprintf(
                    '<span class="badge bg-%s">%s</span>',
                    $class,
                    e($record->ticket->status)
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Scanner
            |--------------------------------------------------------------------------
            */

            ->addColumn('scanner', function ($record) {

                return e(
                    $record->scannedBy?->name
                        ?? 'System'
                );
            })

            /*
            |--------------------------------------------------------------------------
            | Scan Time
            |--------------------------------------------------------------------------
            */

            ->editColumn('scanned_at', function ($record) {

                return $record->scanned_at
                    ? $record->scanned_at->format('d/m/Y H:i:s')
                    : '-';
            })

            /*
            |--------------------------------------------------------------------------
            | Raw HTML
            |--------------------------------------------------------------------------
            */

            ->rawColumns([
                'result',
                'reason',
                'ticket_status',
            ])

            ->toJson();
    }

    /*
    |--------------------------------------------------------------------------
    | Scan Message
    |--------------------------------------------------------------------------
    */

    private function getScanMessage(string $reason): string
    {
        return match ($reason) {

            'VALID' =>
            'Tiket valid dan berhasil digunakan.',

            'NOT_FOUND' =>
            'Tiket tidak ditemukan.',

            'EXPIRED' =>
            'Tiket sudah expired.',

            'ALREADY_USED' =>
            'Tiket sudah digunakan sebelumnya.',

            'CANCELLED' =>
            'Tiket sudah dibatalkan.',

            'INVALID_STATUS' =>
            'Tiket tidak dapat digunakan.',

            default =>
            'Tiket tidak dapat digunakan.',
        };
    }



    public function export(Request $request)
    {
        $dateFrom = $request->input(
            'date_from',
            now()->format('Y-m-d')
        );

        $dateTo = $request->input(
            'date_to',
            now()->format('Y-m-d')
        );

        $filename = 'scan-ticket-' . $dateFrom . '-sampai-' . $dateTo . '.xlsx';

        return Excel::download(
            new ScanExport($request),
            $filename
        );
    }
}
