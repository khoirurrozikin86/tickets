<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;

class EspayCallbackController extends Controller
{
    public function inquiry(Request $request): JsonResponse
    {
        Log::info('ESPay Inquiry Callback', [
            'payload' => $request->all(),
        ]);

        return response()->json([
            'responseCode' => '2005100',
            'responseMessage' => 'Successful',
        ]);
    }

    public function payment(Request $request): JsonResponse
    {
        Log::info('ESPay Payment Callback', [
            'payload' => $request->all(),
        ]);

        return response()->json([
            'responseCode' => '2005100',
            'responseMessage' => 'Successful',
        ]);
    }
}
