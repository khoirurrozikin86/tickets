<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Discounts\Queries\DiscountTableQuery;
use App\Domain\Discounts\Services\DiscountService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\DiscountStoreRequest;
use App\Http\Requests\Admin\DiscountUpdateRequest;
use App\Models\Discount;
use Yajra\DataTables\Facades\DataTables;

class DiscountController extends Controller
{
    public function index()
    {
        return view('super.discounts.index');
    }

    public function dt(DiscountTableQuery $q)
    {
        return DataTables::eloquent($q->builder())

            ->editColumn('type', function (Discount $discount) {

                return match ($discount->type) {

                    'PERCENTAGE' =>
                    '<span class="badge bg-info">Percentage</span>',

                    'FIXED' =>
                    '<span class="badge bg-warning">Fixed</span>',

                    default =>
                    '<span class="badge bg-secondary">'
                        . e($discount->type)
                        . '</span>',
                };
            })

            ->editColumn('value', function (Discount $discount) {

                if ($discount->type === 'PERCENTAGE') {
                    return number_format(
                        (float) $discount->value,
                        0,
                        ',',
                        '.'
                    ) . '%';
                }

                return 'Rp ' . number_format(
                    (float) $discount->value,
                    0,
                    ',',
                    '.'
                );
            })

            ->editColumn('min_purchase', function (Discount $discount) {

                return 'Rp ' . number_format(
                    (float) $discount->min_purchase,
                    0,
                    ',',
                    '.'
                );
            })

            ->editColumn('usage', function (Discount $discount) {

                $limit = $discount->usage_limit === null
                    ? '∞'
                    : $discount->usage_limit;

                return $discount->usage_count . ' / ' . $limit;
            })

            ->editColumn('period', function (Discount $discount) {

                $start = $discount->start_at
                    ? $discount->start_at->format('d/m/Y H:i')
                    : '-';

                $end = $discount->end_at
                    ? $discount->end_at->format('d/m/Y H:i')
                    : '-';

                return $start . '<br>' . $end;
            })

            ->editColumn('is_active', function (Discount $discount) {

                return $discount->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Not Active</span>';
            })

            ->addColumn('actions', function (Discount $discount) {

                $actions = [
                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',

                        'update_url' => route(
                            'super.discounts.update',
                            $discount->getRouteKey()
                        ),

                        'payload' => [
                            'id' => $discount->id,
                            'code' => $discount->code,
                            'name' => $discount->name,
                            'type' => $discount->type,
                            'value' => $discount->value,
                            'max_discount' => $discount->max_discount,
                            'min_purchase' => $discount->min_purchase,
                            'start_at' => $discount->start_at?->format('Y-m-d\TH:i'),
                            'end_at' => $discount->end_at?->format('Y-m-d\TH:i'),
                            'usage_limit' => $discount->usage_limit,
                            'is_active' => $discount->is_active,
                        ],
                    ],

                    [
                        'type' => 'delete',
                        'url' => route(
                            'super.discounts.destroy',
                            $discount->getRouteKey()
                        ),
                        'label' => 'Delete',
                        'icon' => 'trash-2',
                        'confirm' =>
                        "Delete discount {$discount->code} ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns([
                'type',
                'is_active',
                'period',
                'actions',
            ])

            ->toJson();
    }

    public function store(
        DiscountStoreRequest $request,
        DiscountService $service
    ) {
        $service->create(
            $request->sanitized()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Discount berhasil ditambahkan.',
            ]);
        }

        return back()->with(
            'success',
            'Discount berhasil ditambahkan.'
        );
    }

    public function update(
        DiscountUpdateRequest $request,
        Discount $discount,
        DiscountService $service
    ) {
        $service->update(
            $discount,
            $request->sanitized()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Discount berhasil diperbarui.',
            ]);
        }

        return back()->with(
            'success',
            'Discount berhasil diperbarui.'
        );
    }

    public function destroy(
        Discount $discount,
        DiscountService $service
    ) {
        $service->delete($discount);

        return response()->json([
            'message' => 'Discount berhasil dihapus.',
        ]);
    }
}
