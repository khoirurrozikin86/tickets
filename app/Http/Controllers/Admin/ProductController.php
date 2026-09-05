<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domain\Products\Queries\ProductTableQuery;
use App\Domain\Products\Services\ProductService;
use App\Models\Product;
use Yajra\DataTables\Facades\DataTables;

use App\Http\Requests\Admin\ProductStoreRequest;
use App\Http\Requests\Admin\ProductUpdateRequest;



class ProductController extends Controller
{
    public function index()
    {
        return view('super.products.index');
    }

    public function dt(ProductTableQuery $q)
    {
        return DataTables::eloquent($q->builder())

            ->editColumn(
                'image',
                function (Product $product) {

                    if (!$product->image) {
                        return '<span class="text-muted">No Image</span>';
                    }

                    return '<img
                    src="' . asset('storage/' . $product->image) . '"
                    alt="' . e($product->name) . '"
                    style="
                        width: 70px;
                        height: 50px;
                        object-fit: cover;
                        border-radius: 6px;
                    "
                >';
                }
            )

            ->editColumn(
                'is_active',
                fn(Product $product) =>
                $product->is_active
                    ? 'Active'
                    : 'Not Active'
            )

            ->addColumn('actions', function (Product $product) {

                $actions = [
                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',

                        'update_url' => route(
                            'super.products.update',
                            $product->getRouteKey()
                        ),

                        'payload' => [
                            'id' => $product->id,
                            'name' => $product->name,
                            'slug' => $product->slug,
                            'description' => $product->description,
                            'image' => $product->image
                                ? asset('storage/' . $product->image)
                                : null,
                            'is_active' => $product->is_active,
                            'sort_order' => $product->sort_order,
                        ],
                    ],

                    [
                        'type' => 'delete',

                        'url' => route(
                            'super.products.destroy',
                            $product->getRouteKey()
                        ),

                        'label' => 'Delete',
                        'icon' => 'trash-2',

                        'confirm' =>
                        "Delete Product {$product->name} ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns([
                'image',
                'actions',
            ])

            ->toJson();
    }

    public function store(
        ProductStoreRequest $request,
        ProductService $service
    ) {
        $product = $service->create(
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Product created',
                'id' => $product->id,
            ], 201)
            : back()->with(
                'success',
                'Product created'
            );
    }

    public function update(
        ProductUpdateRequest $request,
        Product $product,
        ProductService $service
    ) {
        $service->update(
            $product,
            $request->sanitized()
        );

        return $request->ajax() || $request->expectsJson()
            ? response()->json([
                'message' => 'Product updated',
            ])
            : back()->with(
                'success',
                'Product updated'
            );
    }

    public function destroy(
        Product $product,
        ProductService $service
    ) {
        $service->delete($product);

        return request()->ajax() || request()->expectsJson()
            ? response()->json([
                'message' => 'Product deleted',
            ])
            : redirect()
            ->route('super.products.index')
            ->with(
                'success',
                'Product deleted'
            );
    }
}
