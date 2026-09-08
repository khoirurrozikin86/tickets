<?php

use App\Http\Controllers\EspayCallbackController;
use Illuminate\Support\Facades\Route;

Route::prefix('espay')->group(function () {

    Route::post('/inquiry', [
        EspayCallbackController::class,
        'inquiry',
    ])->name('espay.inquiry');

    Route::post('/payment', [
        EspayCallbackController::class,
        'payment',
    ])->name('espay.payment');
});
