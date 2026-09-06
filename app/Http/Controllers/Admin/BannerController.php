<?php

namespace App\Http\Controllers\Admin;

use App\Domain\Banners\Queries\BannerTableQuery;
use App\Domain\Banners\Services\BannerService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BannerRequest;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Yajra\DataTables\Facades\DataTables;

class BannerController extends Controller
{
    public function index(): View
    {
        return view('super.banners.index');
    }

    public function dt(
        Request $request,
        BannerTableQuery $query
    ): JsonResponse {
        $builder = $query->builder();

        if ($request->filled('title')) {
            $search = $request->title;

            $builder->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('subtitle', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $builder->where(
                'is_active',
                $request->status === 'ACTIVE'
            );
        }

        return DataTables::eloquent($builder)

            ->editColumn('image', function (Banner $banner) {
                if (!$banner->image) {
                    return '<span class="text-muted">-</span>';
                }

                return '
                    <img
                        src="' . e(asset($banner->image)) . '"
                        alt="' . e($banner->title ?? 'Banner') . '"
                        class="banner-thumb"
                    >
                ';
            })

            ->editColumn('title', function (Banner $banner) {
                return '
                    <div>
                        <div class="fw-semibold">'
                    . e($banner->title ?: 'Tanpa Judul') .
                    '</div>
                        <small class="text-muted">'
                    . e($banner->subtitle ?: '') .
                    '</small>
                    </div>
                ';
            })

            ->editColumn('sort_order', function (Banner $banner) {
                return '<span class="badge bg-light text-dark">'
                    . e($banner->sort_order)
                    . '</span>';
            })

            ->editColumn('is_active', function (Banner $banner) {
                return $banner->is_active
                    ? '<span class="badge bg-success">ACTIVE</span>'
                    : '<span class="badge bg-secondary">INACTIVE</span>';
            })

            ->addColumn('actions', function (Banner $banner) {

                $actions = [
                    [
                        'type' => 'edit',
                        'label' => 'Edit',
                        'icon' => 'edit-2',

                        'update_url' => route(
                            'super.banners.update',
                            $banner->getRouteKey()
                        ),

                        'payload' => [
                            'id' => $banner->id,
                            'title' => $banner->title,
                            'subtitle' => $banner->subtitle,

                            'image' => $banner->image
                                ? (
                                    str_starts_with($banner->image, 'storage/')
                                    ? asset($banner->image)
                                    : asset('storage/' . $banner->image)
                                )
                                : null,

                            'button_text' => $banner->button_text,
                            'button_url' => $banner->button_url,
                            'sort_order' => $banner->sort_order,
                            'is_active' => $banner->is_active,
                        ],
                    ],

                    [
                        'type' => 'delete',

                        'url' => route(
                            'super.banners.destroy',
                            $banner->getRouteKey()
                        ),

                        'label' => 'Delete',
                        'icon' => 'trash-2',

                        'confirm' =>
                        "Delete Banner " .
                            ($banner->title ?: 'Tanpa Judul') .
                            " ?",
                    ],
                ];

                return view(
                    'admin.partials.table-actions',
                    compact('actions')
                )->render();
            })

            ->rawColumns([
                'image',
                'title',
                'sort_order',
                'is_active',
                'actions',
            ])

            ->toJson();
    }

    public function store(
        BannerRequest $request,
        BannerService $service
    ): JsonResponse|RedirectResponse {
        try {
            $service->create(
                $request->validated(),
                $request->file('image')
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner berhasil ditambahkan.',
                ]);
            }

            return back()->with(
                'success',
                'Banner berhasil ditambahkan.'
            );
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function update(
        BannerRequest $request,
        Banner $banner,
        BannerService $service
    ): JsonResponse|RedirectResponse {
        try {
            $service->update(
                $banner,
                $request->validated(),
                $request->file('image')
            );

            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner berhasil diperbarui.',
                ]);
            }

            return back()->with(
                'success',
                'Banner berhasil diperbarui.'
            );
        } catch (\Throwable $e) {
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()
                ->withInput()
                ->with('error', $e->getMessage());
        }
    }

    public function destroy(
        Banner $banner,
        BannerService $service
    ): JsonResponse|RedirectResponse {
        try {
            $service->delete($banner);

            if (request()->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Banner berhasil dihapus.',
                ]);
            }

            return back()->with(
                'success',
                'Banner berhasil dihapus.'
            );
        } catch (\Throwable $e) {
            if (request()->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                ], 422);
            }

            return back()->with(
                'error',
                $e->getMessage()
            );
        }
    }
}
