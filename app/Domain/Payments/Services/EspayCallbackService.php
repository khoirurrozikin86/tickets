<?php

namespace App\Domain\Payments\Services;

use App\Domain\Payments\Actions\ProcessEspayInquiryAction;
use App\Domain\Payments\Actions\ProcessEspayPaymentAction;
use App\Domain\Payments\DTOs\EspayInquiryData;
use App\Domain\Payments\DTOs\EspayPaymentData;
use Illuminate\Support\Facades\Log;

final class EspayCallbackService
{
    public function __construct(
        private readonly ProcessEspayInquiryAction $inquiryAction,
        private readonly ProcessEspayPaymentAction $paymentAction,
    ) {}

    public function inquiry(array $payload): array
    {
        Log::info('ESPay Inquiry Callback', [
            'payload' => $payload,
        ]);

        return $this->inquiryAction->execute(
            EspayInquiryData::fromArray($payload)
        );
    }

    public function payment(array $payload): array
    {
        Log::info('ESPay Payment Callback', [
            'payload' => $payload,
        ]);

        return $this->paymentAction->execute(
            EspayPaymentData::fromArray($payload)
        );
    }
}
