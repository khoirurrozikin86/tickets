<?php

namespace App\Http\Controllers\Admin;

use App\Domain\ProductPrices\Queries\ProductPriceTableQuery;
use App\Domain\ProductPrices\Services\ProductPriceService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\ProductPriceStoreRequest;
use App\Http\Requests\Admin\ProductPriceUpdateRequest;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ProductPriceController extends Controller
{
    public function index()
    {
        $products = \App\Models\Product::query()
            ->where('is_active', true)
            ->orderBy('name')
            ->get([
                'id',
                'name',
            ]);

        return view('super.product-prices.index', compact('products'));
    }

    public function dt(ProductPriceTableQuery $q)
    {
        return DataTables::eloquent($q->builder())

            ->editColumn('product', function (ProductPrice $productPrice) {
                return e($productPrice->product?->name ?? '-');
            })

            ->editColumn('day_type', function (ProductPrice $productPrice) {
                return match ($productPrice->day_type) {
                    'WEEKDAY' => '<span class="badge bg-info">Weekday</span>',
                    'WEEKEND' => '<span class="badge bg-warning">Weekend</span>',
                    'HOLIDAY' => '<span class="badge bg-danger">Holiday</span>',
                    default => '<span class="badge bg-secondary">'
                        . e($productPrice->day_type)
                        . '</span>',
                };
            })

            ->editColumn('price', function (ProductPrice $productPrice) {
                return 'Rp ' . number_format(
                    (float) $productPrice->price,
                    0,
                    ',',
                    '.'
                );
            })

            ->editColumn('is_active', function (ProductPrice $productPrice) {
                return $productPrice->is_active
                    ? '<span class="badge bg-success">Active</span>'
                    : '<span class="badge bg-secondary">Not Active</span>';
            })

            ->addColumn('actions', function (ProductPrice $productPrice) {

                $actions = [
                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',

                        'update_url' => route(
                            'super.product-prices.update',
                            $productPrice->getRouteKey()
                        ),

                        'payload' => [
                            'id' => $productPrice->id,
                            'product_id' => $productPrice->product_id,
                            'day_type' => $productPrice->day_type,
                            'price' => $productPrice->price,
                            'is_active' => $productPrice->is_active,
                        ],
                    ],

                    [
                        'type' => 'delete',
                        'url' => route(
                            'super.product-prices.destroy',
                            $productPrice->getRouteKey()
                        ),
                        'label' => 'Delete',
                        'icon' => 'trash-2',
                        'confirm' => "Delete price {$productPrice->day_type} ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns([
                'day_type',
                'is_active',
                'actions',
            ])

            ->toJson();
    }

    public function store(
        ProductPriceStoreRequest $request,
        ProductPriceService $service
    ) {
        $service->create(
            $request->sanitized()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Harga product berhasil ditambahkan.',
            ]);
        }

        return back()->with(
            'success',
            'Harga product berhasil ditambahkan.'
        );
    }

    public function update(
        ProductPriceUpdateRequest $request,
        ProductPrice $productPrice,
        ProductPriceService $service
    ) {
        $service->update(
            $productPrice,
            $request->sanitized()
        );

        if ($request->expectsJson()) {
            return response()->json([
                'message' => 'Harga product berhasil diperbarui.',
            ]);
        }

        return back()->with(
            'success',
            'Harga product berhasil diperbarui.'
        );
    }

    public function destroy(
        ProductPrice $productPrice,
        ProductPriceService $service
    ) {
        $service->delete($productPrice);

        return response()->json([
            'message' => 'Harga product berhasil dihapus.',
        ]);
    }
}
