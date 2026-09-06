<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Support\Collection;
use Inertia\Inertia;
use Inertia\Response;

class HomeController extends Controller
{
    /**
     * Halaman utama public website.
     *
     * Data yang dikirim ke React:
     * - settings
     * - banners
     * - products
     */
    public function index(): Response
    {
        /*
        |--------------------------------------------------------------------------
        | SITE SETTINGS
        |--------------------------------------------------------------------------
        |
        | Mengambil seluruh setting aktif dari CMS.
        |
        | Hasil:
        |
        | [
        |     'site_name'    => 'Dusun Semilir',
        |     'site_tagline' => 'Liburan Lebih Seru Bersama OSIL!',
        |     'logo'         => '...',
        |     ...
        | ]
        |
        */

        $settings = SiteSetting::query()
            ->where('is_active', true)
            ->orderBy('id')
            ->get([
                'key',
                'value',
            ])
            ->mapWithKeys(function (SiteSetting $setting) {
                return [
                    $setting->key => $this->settingValue(
                        $setting->key,
                        $setting->value
                    ),
                ];
            })
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | BANNERS
        |--------------------------------------------------------------------------
        |
        | Banner berasal dari CMS Banner.
        |
        | Hanya banner aktif yang ditampilkan.
        |
        */

        $banners = Banner::query()
            ->where('is_active', true)
            ->orderBy('sort_order')
            ->orderByDesc('id')
            ->get([
                'id',
                'title',
                'subtitle',
                'image',
                'button_text',
                'button_url',
                'sort_order',
            ])
            ->map(function (Banner $banner) {
                return [
                    'id' => $banner->id,

                    'title' => $banner->title,

                    'subtitle' => $banner->subtitle,

                    'image' => $this->imageUrl(
                        $banner->image
                    ),

                    'button_text' => $banner->button_text,

                    'button_url' => $banner->button_url,

                    'sort_order' => $banner->sort_order,
                ];
            })
            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | PRODUCTS
        |--------------------------------------------------------------------------
        |
        | Produk hanya yang aktif.
        |
        | Harga diambil dari Product Price:
        |
        | WEEKDAY
        | WEEKEND
        | HOLIDAY
        |
        */

        $products = Product::query()
            ->where('is_active', true)

            ->with([
                'prices' => function ($query) {
                    $query
                        ->where('is_active', true)
                        ->orderByRaw("
                            CASE day_type
                                WHEN 'WEEKDAY' THEN 1
                                WHEN 'WEEKEND' THEN 2
                                WHEN 'HOLIDAY' THEN 3
                                ELSE 4
                            END
                        ")
                        ->select([
                            'id',
                            'product_id',
                            'day_type',
                            'price',
                        ]);
                },
            ])

            ->orderBy('sort_order')
            ->orderBy('name')

            ->get([
                'id',
                'name',
                'slug',
                'description',
                'image',
                'is_active',
                'sort_order',
            ])

            ->map(function (Product $product) {
                return [
                    'id' => $product->id,

                    'name' => $product->name,

                    'slug' => $product->slug,

                    'description' => $product->description,

                    'image' => $this->imageUrl(
                        $product->image
                    ),

                    'is_active' => $product->is_active,

                    'sort_order' => $product->sort_order,

                    'prices' => $product->prices
                        ->map(function ($price) {
                            return [
                                'id' => $price->id,

                                'product_id' => $price->product_id,

                                'day_type' => $price->day_type,

                                'price' => (float) $price->price,
                            ];
                        })
                        ->values()
                        ->toArray(),
                ];
            })

            ->values()
            ->toArray();


        /*
        |--------------------------------------------------------------------------
        | RENDER INERTIA
        |--------------------------------------------------------------------------
        */

        return Inertia::render('Public/Home', [
            'settings' => $settings,

            'banners' => $banners,

            'products' => $products,
        ]);
    }


    /**
     * Mengubah value setting tertentu menjadi value
     * yang siap digunakan oleh React.
     */
    private function settingValue(
        string $key,
        ?string $value
    ): ?string {
        if ($value === null || $value === '') {
            return $value;
        }

        /*
        |--------------------------------------------------------------------------
        | IMAGE SETTINGS
        |--------------------------------------------------------------------------
        |
        | Contoh database:
        |
        | images/semilir_logo.png
        |
        | atau:
        |
        | storage/settings/logo.png
        |
        */

        if (in_array($key, [
            'logo',
            'favicon',
        ], true)) {
            return $this->imageUrl($value);
        }

        return $value;
    }


    /**
     * Mengubah path file storage menjadi URL public.
     *
     * Database sebaiknya menyimpan:
     *
     * banners/banner.jpg
     *
     * bukan:
     *
     * storage/banners/banner.jpg
     */
    private function imageUrl(?string $path): ?string
    {
        if (!$path) {
            return null;
        }

        /*
        |--------------------------------------------------------------------------
        | URL ABSOLUTE
        |--------------------------------------------------------------------------
        */

        if (
            str_starts_with($path, 'http://') ||
            str_starts_with($path, 'https://')
        ) {
            return $path;
        }


        /*
        |--------------------------------------------------------------------------
        | PATH DENGAN / DI DEPAN
        |--------------------------------------------------------------------------
        */

        $path = ltrim($path, '/');


        /*
        |--------------------------------------------------------------------------
        | HILANGKAN PREFIX storage/
        |--------------------------------------------------------------------------
        |
        | Agar tidak menjadi:
        |
        | /storage/storage/banners/xxx.jpg
        |
        */

        if (str_starts_with($path, 'storage/')) {
            $path = substr(
                $path,
                strlen('storage/')
            );
        }


        /*
        |--------------------------------------------------------------------------
        | FILE YANG BERADA LANGSUNG DI PUBLIC
        |--------------------------------------------------------------------------
        |
        | Contoh:
        |
        | images/semilir_logo.png
        |
        | Jika file memang ada di:
        |
        | public/images/semilir_logo.png
        |
        | maka jangan tambahkan /storage.
        |
        */

        if (
            str_starts_with($path, 'images/') ||
            str_starts_with($path, 'assets/')
        ) {
            return asset($path);
        }


        /*
        |--------------------------------------------------------------------------
        | STORAGE
        |--------------------------------------------------------------------------
        */

        return asset(
            'storage/' . $path
        );
    }
}
