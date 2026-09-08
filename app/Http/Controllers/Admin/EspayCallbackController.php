<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class EspayCallbackController extends Controller
{
    /**
     * ESPay Inquiry Callback
     */
    public function inquiry(Request $request): JsonResponse
    {
        Log::info('ESPay INQUIRY RECEIVED', [
            'ip' => $request->ip(),

            'method' => $request->method(),

            'content_type' => $request->header('Content-Type'),

            'headers' => $request->headers->all(),

            'query' => $request->query(),

            'body' => $request->getContent(),

            'json' => $request->json()->all(),

            'input' => $request->all(),
        ]);

        return response()->json([
            'responseCode' => '2005100',
            'responseMessage' => 'Successful',
        ]);
    }

    /**
     * ESPay Payment Callback
     */
    public function payment(Request $request): JsonResponse
    {
        Log::info('ESPay PAYMENT RECEIVED', [
            'ip' => $request->ip(),
            'payload' => $request->all(),
            'headers' => $request->headers->all(),
        ]);

        return response()->json([
            'responseCode' => '2005100',
            'responseMessage' => 'Successful',
        ]);
    }
}
