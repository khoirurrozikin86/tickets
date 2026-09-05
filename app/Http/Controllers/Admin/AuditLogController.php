<?php

namespace App\Http\Controllers\Admin;

use App\Domain\AuditLogs\Queries\AuditLogTableQuery;
use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('super.audit-logs.index');
    }

    public function dt(AuditLogTableQuery $query): JsonResponse
    {
        return DataTables::eloquent($query->builder())
            ->addColumn('user_name', function ($audit) {
                return $audit->user?->name ?? 'System';
            })

            ->editColumn('action', function ($audit) {
                $badges = [
                    'CREATE' => 'success',
                    'UPDATE' => 'warning',
                    'DELETE' => 'danger',
                    'LOGIN' => 'primary',
                    'LOGOUT' => 'secondary',
                    'PAYMENT' => 'success',
                    'PAYMENT_CALLBACK' => 'success',
                    'CANCEL' => 'danger',
                    'SCAN' => 'info',
                    'REFUND' => 'danger',
                ];

                $class = $badges[$audit->action] ?? 'secondary';

                return '<span class="badge bg-' . $class . '">'
                    . e($audit->action)
                    . '</span>';
            })

            ->editColumn('module', function ($audit) {
                return '<span class="fw-semibold">'
                    . e($audit->module)
                    . '</span>';
            })

            ->editColumn('description', function ($audit) {
                return e($audit->description ?? '-');
            })

            ->editColumn('created_at', function ($audit) {
                return $audit->created_at
                    ? $audit->created_at->format('d/m/Y H:i:s')
                    : '-';
            })

            ->addColumn('record', function ($audit) {
                if (!$audit->auditable_id) {
                    return '-';
                }

                $type = class_basename($audit->auditable_type);

                return '<span class="text-muted">'
                    . e($type)
                    . ' #' . e($audit->auditable_id)
                    . '</span>';
            })


            ->addColumn('actions', function ($audit) {

                return '
        <a href="' . route(
                    'super.audit-logs.show',
                    $audit->id
                ) . '"
        class="audit-log-detail"
        title="Detail">

            <i data-feather="eye"></i>

        </a>
    ';
            })

            ->rawColumns([
                'action',
                'module',
                'record',
                'actions',
            ])

            ->toJson();
    }

    public function show(int $auditLog)
    {
        $audit = \App\Models\AuditLog::with('user')
            ->findOrFail($auditLog);

        return view(
            'super.audit-logs.show',
            compact('audit')
        );
    }
}
