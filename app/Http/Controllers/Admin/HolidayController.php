<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Holidays\Queries\HolidayTableQuery;
use App\Domain\Holidays\Services\HolidayService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\HolidayStoreRequest;
use App\Http\Requests\Admin\HolidayUpdateRequest;
use App\Models\Holiday;
use Yajra\DataTables\Facades\DataTables;

class HolidayController extends Controller
{
    public function index()
    {
        return view('super.holidays.index');
    }

    public function dt(HolidayTableQuery $q)
    {
        return DataTables::eloquent($q->builder())
            ->editColumn('date', function (Holiday $holiday) {
                return $holiday->date?->format('d/m/Y') ?? '-';
            })
            ->editColumn('is_active', function (Holiday $holiday) {
                return $holiday->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Not Active</span>';
            })
            ->addColumn('actions', function (Holiday $holiday) {
                $actions = [
                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',
                        'update_url' => route(
                            'super.holidays.update',
                            $holiday->getRouteKey()
                        ),
                        'payload' => [
                            'id' => $holiday->id,
                            'date' => $holiday->date?->format('Y-m-d'),
                            'name' => $holiday->name,
                            'is_active' => $holiday->is_active,
                        ],
                    ],
                    [
                        'type' => 'delete',
                        'url' => route(
                            'super.holidays.destroy',
                            $holiday->getRouteKey()
                        ),
                        'label' => 'Delete',
                        'icon' => 'trash-2',
                        'confirm' => "Delete holiday {$holiday->name} ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })
            ->rawColumns([
                'is_active',
                'actions',
            ])
            ->toJson();
    }

    public function store(
        HolidayStoreRequest $request,
        HolidayService $service
    ) {
        $service->create($request->sanitized());

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hari libur berhasil ditambahkan.',
            ]);
        }

        return back()->with(
            'success',
            'Hari libur berhasil ditambahkan.'
        );
    }

    public function update(
        HolidayUpdateRequest $request,
        Holiday $holiday,
        HolidayService $service
    ) {
        $service->update(
            $holiday,
            $request->sanitized()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Hari libur berhasil diperbarui.',
            ]);
        }

        return back()->with(
            'success',
            'Hari libur berhasil diperbarui.'
        );
    }

    public function destroy(
        Holiday $holiday,
        HolidayService $service
    ) {
        $service->delete($holiday);

        return response()->json([
            'message' => 'Hari libur berhasil dihapus.',
        ]);
    }
}
