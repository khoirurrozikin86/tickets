<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Public\{
    CheckoutController,
    HomeController,
    PaymentController,
    ReservationController,
    TicketController
};
use App\Http\Controllers\Super\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])
    ->name('home');

/*
|--------------------------------------------------------------------------
| Public Tickets
|--------------------------------------------------------------------------
*/

Route::prefix('tickets')
    ->name('public.tickets.')
    ->group(function () {

        // Detail produk
        Route::get('/{product:slug}', [
            TicketController::class,
            'show',
        ])->name('show');

        // Cek harga
        Route::get('/{product:slug}/price', [
            TicketController::class,
            'price',
        ])->name('price');

        // Validasi discount / voucher
        Route::post('/{product:slug}/voucher', [
            TicketController::class,
            'voucher',
        ])
            ->middleware('throttle:discount')
            ->name('voucher');
    });

/*
|--------------------------------------------------------------------------
| Public Checkout
|--------------------------------------------------------------------------
*/

// Halaman checkout
Route::get('/checkout', [
    CheckoutController::class,
    'show',
])->name('public.checkout');

// Proses checkout
// Maksimal 10 request / menit / IP
Route::post('/checkout', [
    CheckoutController::class,
    'store',
])
    ->middleware('throttle:checkout')
    ->name('public.checkout.store');

/*
|--------------------------------------------------------------------------
| Public Payment
|--------------------------------------------------------------------------
*/

// Halaman pembayaran
Route::get('/payment', [
    PaymentController::class,
    'show',
])->name('public.payment');

/*
|--------------------------------------------------------------------------
| Public Reservation
|--------------------------------------------------------------------------
*/

// Halaman pencarian reservasi
Route::get('/reservasi', [
    ReservationController::class,
    'index',
])->name('public.reservation');

// Proses pencarian reservasi
// Maksimal 20 request / menit / IP
Route::post('/reservasi', [
    ReservationController::class,
    'search',
])
    ->middleware('throttle:reservation-search')
    ->name('public.reservation.search');

// Detail reservasi
Route::get('/reservasi/{order:order_number}', [
    ReservationController::class,
    'show',
])->name('public.reservation.show');

/*
|--------------------------------------------------------------------------
| Super / Admin
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])
    ->prefix('super')
    ->name('super.')
    ->group(function () {

        Route::get('/dashboard', [
            DashboardController::class,
            'index',
        ])->name('dashboard');
    });

/*
|--------------------------------------------------------------------------
| Profile
|--------------------------------------------------------------------------
*/

Route::middleware('auth')
    ->group(function () {

        Route::get('/profile', [
            ProfileController::class,
            'edit',
        ])->name('profile.edit');

        Route::patch('/profile', [
            ProfileController::class,
            'update',
        ])->name('profile.update');

        Route::delete('/profile', [
            ProfileController::class,
            'destroy',
        ])->name('profile.destroy');
    });

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

require __DIR__ . '/auth.php';
require __DIR__ . '/super.php';
