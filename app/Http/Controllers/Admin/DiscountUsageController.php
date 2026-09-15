<?php

namespace App\Http\Controllers\Admin;

use App\Exports\DiscountUsagesExport;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\DiscountUsage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class DiscountUsageController extends Controller
{
    /**
     * Halaman Discount Usage History.
     */
    public function index(Discount $discount): View
    {
        return view('super.discounts.usages.index', [
            'discount' => $discount,
        ]);
    }

    /**
     * DataTables Discount Usage History.
     */
    public function dt(
        Request $request,
        Discount $discount
    ): JsonResponse {
        $query = DiscountUsage::query()
            ->with([
                'order:id,order_number,customer_name,customer_email',
            ])
            ->where('discount_id', $discount->id)
            ->select('discount_usages.*');

        /*
        |--------------------------------------------------------------------------
        | Filter Order
        |--------------------------------------------------------------------------
        */

        if ($request->filled('order_number')) {
            $orderNumber = $request->input('order_number');

            $query->whereHas('order', function ($q) use ($orderNumber) {
                $q->where(
                    'order_number',
                    'like',
                    '%' . $orderNumber . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Customer
        |--------------------------------------------------------------------------
        */

        if ($request->filled('customer')) {
            $customer = $request->input('customer');

            $query->whereHas('order', function ($q) use ($customer) {
                $q->where(
                    'customer_name',
                    'like',
                    '%' . $customer . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Email
        |--------------------------------------------------------------------------
        */

        if ($request->filled('email')) {
            $email = $request->input('email');

            $query->whereHas('order', function ($q) use ($email) {
                $q->where(
                    'customer_email',
                    'like',
                    '%' . $email . '%'
                );
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Filter Date
        |--------------------------------------------------------------------------
        |
        | Default: hari ini
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

        $query
            ->whereDate(
                'discount_usages.used_at',
                '>=',
                $dateFrom
            )
            ->whereDate(
                'discount_usages.used_at',
                '<=',
                $dateTo
            );

        /*
        |--------------------------------------------------------------------------
        | DataTables
        |--------------------------------------------------------------------------
        */

        return DataTables::eloquent($query)

            ->addIndexColumn()

            ->addColumn('order_number', function ($usage) {
                return e(
                    $usage->order?->order_number ?? '-'
                );
            })

            ->addColumn('customer_name', function ($usage) {
                return e(
                    $usage->order?->customer_name ?? '-'
                );
            })

            ->addColumn('customer_email', function ($usage) {
                return e(
                    $usage->order?->customer_email ?? '-'
                );
            })

            ->editColumn('discount_amount', function ($usage) {
                return 'Rp ' . number_format(
                    (float) $usage->discount_amount,
                    0,
                    ',',
                    '.'
                );
            })

            ->editColumn('used_at', function ($usage) {
                return $usage->used_at
                    ? $usage->used_at->format('d/m/Y H:i:s')
                    : '-';
            })

            ->rawColumns([
                'order_number',
                'customer_name',
                'customer_email',
                'discount_amount',
                'used_at',
            ])

            ->toJson();
    }

    /**
     * Export Discount Usage History.
     */
    public function export(
        Request $request,
        Discount $discount
    ) {
        $filters = [
            'order_number' => $request->input('order_number'),
            'customer' => $request->input('customer'),
            'email' => $request->input('email'),

            'date_from' => $request->input(
                'date_from',
                now()->format('Y-m-d')
            ),

            'date_to' => $request->input(
                'date_to',
                now()->format('Y-m-d')
            ),
        ];

        return Excel::download(
            new DiscountUsagesExport(
                discountId: $discount->id,
                filters: $filters,
            ),
            'discount-usage-' .
                $discount->code .
                '-' .
                now()->format('Y-m-d-His') .
                '.xlsx'
        );
    }
}
