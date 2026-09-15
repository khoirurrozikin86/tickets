<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void {}

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        RateLimiter::for('login', function (Request $request) {
            $email = strtolower((string) $request->input('email'));

            return Limit::perMinute(5)
                ->by($email . '|' . $request->ip());
        });






        /*
    |--------------------------------------------------------------------------
    | PUBLIC - Checkout
    |--------------------------------------------------------------------------
    | Maksimal 10 request / menit / IP
    */
        RateLimiter::for('checkout', function (Request $request) {
            return Limit::perMinute(10)
                ->by($request->ip());
        });

        /*
    |--------------------------------------------------------------------------
    | PUBLIC - Payment
    |--------------------------------------------------------------------------
    | Maksimal 20 request / menit / IP
    */
        RateLimiter::for('payment', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->ip());
        });

        /*
    |--------------------------------------------------------------------------
    | PUBLIC - Reservation Search
    |--------------------------------------------------------------------------
    | Maksimal 20 request / menit / IP
    */
        RateLimiter::for('reservation-search', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->ip());
        });

        /*
    |--------------------------------------------------------------------------
    | PUBLIC - Discount
    |--------------------------------------------------------------------------
    | Maksimal 20 request / menit / IP
    */
        RateLimiter::for('discount', function (Request $request) {
            return Limit::perMinute(20)
                ->by($request->ip());
        });
    }
}
