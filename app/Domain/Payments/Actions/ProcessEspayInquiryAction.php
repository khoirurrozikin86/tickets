<?php

namespace App\Domain\Payments\Actions;

use App\Domain\Payments\DTOs\EspayInquiryData;
use App\Models\Payment;

final class ProcessEspayInquiryAction
{
    public function execute(EspayInquiryData $data): array
    {
        $payment = Payment::query()
            ->with('order')
            ->where('payment_number', $data->partnerReferenceNo)
            ->first();

        if (! $payment) {
            return [
                'responseCode' => '4044701',
                'responseMessage' => 'Payment not found',
            ];
        }

        if ($data->amount !== null) {
            $requestedAmount = (float) $data->amount;
            $paymentAmount = (float) $payment->amount;

            if (abs($requestedAmount - $paymentAmount) > 0.01) {
                return [
                    'responseCode' => '4004700',
                    'responseMessage' => 'Invalid amount',
                ];
            }
        }

        return [
            'responseCode' => '2004700',
            'responseMessage' => 'Successful',
            'virtualAccountData' => [
                'partnerReferenceNo' => $payment->payment_number,
                'customerName' => $payment->order?->customer_name,
                'amount' => [
                    'value' => number_format(
                        (float) $payment->amount,
                        2,
                        '.',
                        ''
                    ),
                    'currency' => $payment->currency ?: 'IDR',
                ],
            ],
        ];
    }
}
