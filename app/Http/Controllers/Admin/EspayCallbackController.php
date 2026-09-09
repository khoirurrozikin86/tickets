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
        'body' => $request->getContent(),
        'json' => $request->json()->all(),
    ]);

    $data = $request->json()->all();

    $partnerServiceId = $data['partnerServiceId'] ?? ' Espay';
    $customerNo = $data['customerNo'] ?? config('espay.merchant_code');
    $virtualAccountNo = $data['virtualAccountNo'] ?? null;
    $inquiryRequestId = $data['inquiryRequestId'] ?? null;

    if (!$virtualAccountNo) {
        return response()->json([
            'responseCode' => '4002402',
            'responseMessage' => 'Invalid Virtual Account',
        ]);
    }

    /*
     * Sementara:
     * DS15P15
     *   DS = prefix
     *   15 = order ID
     *   P15 = payment ID
     */
    if (!preg_match('/^DS(\d+)P(\d+)$/', $virtualAccountNo, $matches)) {
        return response()->json([
            'responseCode' => '4002402',
            'responseMessage' => 'Invalid Virtual Account',
        ]);
    }

    $orderId = (int) $matches[1];
    $paymentId = (int) $matches[2];

    $order = \App\Models\Order::query()
        ->with('items')
        ->whereKey($orderId)
        ->first();

    $payment = \App\Models\Payment::query()
        ->whereKey($paymentId)
        ->where('order_id', $orderId)
        ->first();

    if (!$order || !$payment) {
        return response()->json([
            'responseCode' => '4042401',
            'responseMessage' => 'Virtual Account Not Found',
        ]);
    }

    return response()->json([
        'responseCode' => '2002400',
        'responseMessage' => 'Success',

        'virtualAccountData' => [
            'partnerServiceId' => $partnerServiceId,
            'customerNo' => $customerNo,
            'virtualAccountNo' => $virtualAccountNo,
            'virtualAccountName' => $order->customer_name,
            'virtualAccountEmail' => $order->customer_email,
            'virtualAccountPhone' => $order->customer_phone,
            'inquiryRequestId' => $inquiryRequestId,

            'totalAmount' => [
                'value' => number_format(
                    (float) $order->total_amount,
                    2,
                    '.',
                    ''
                ),
                'currency' => $order->currency ?? 'IDR',
            ],

            'billDetails' => [
                [
                    'billDescription' => [
                        'english' => 'Dusun Semilir Ticket',
                        'indonesia' => 'Tiket Dusun Semilir',
                    ],
                ],
            ],
        ],

        'additionalInfo' => [
            'transactionDate' => now('Asia/Jakarta')
                ->format('Y-m-d\TH:i:sP'),
        ],
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
