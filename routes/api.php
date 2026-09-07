<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EspayCallbackController;

Route::prefix('espay')->group(function () {
    Route::post('/inquiry', [EspayCallbackController::class, 'inquiry']);
    Route::post('/payment', [EspayCallbackController::class, 'payment']);
});
