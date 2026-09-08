<?php

namespace App\Domain\Payments\DTOs;

final class EspayPaymentData
{
    public function __construct(
        public readonly string $partnerReferenceNo,
        public readonly ?string $referenceNo = null,
        public readonly ?string $merchantId = null,
        public readonly ?string $amount = null,
        public readonly ?string $currency = null,
        public readonly ?string $transactionStatus = null,
        public readonly array $payload = [],
    ) {}

    public static function fromArray(array $data): self
    {
        $additionalInfo = $data['additionalInfo'] ?? [];

        return new self(
            partnerReferenceNo: (string) (
                $data['partnerReferenceNo']
                ?? $data['originalPartnerReferenceNo']
                ?? ''
            ),
            referenceNo: isset($data['referenceNo'])
                ? (string) $data['referenceNo']
                : (
                    isset($additionalInfo['referenceNo'])
                    ? (string) $additionalInfo['referenceNo']
                    : null
                ),
            merchantId: isset($data['merchantId'])
                ? (string) $data['merchantId']
                : null,
            amount: isset($data['amount']['value'])
                ? (string) $data['amount']['value']
                : null,
            currency: isset($data['amount']['currency'])
                ? (string) $data['amount']['currency']
                : null,
            transactionStatus: isset($data['transactionStatus'])
                ? (string) $data['transactionStatus']
                : null,
            payload: $data,
        );
    }
}
