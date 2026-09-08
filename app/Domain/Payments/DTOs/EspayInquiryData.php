<?php

namespace App\Domain\Payments\DTOs;

final class EspayInquiryData
{
    public function __construct(
        public readonly string $partnerReferenceNo,
        public readonly ?string $merchantId = null,
        public readonly ?string $amount = null,
        public readonly ?string $currency = null,
        public readonly array $payload = [],
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            partnerReferenceNo: (string) (
                $data['partnerReferenceNo']
                ?? $data['originalPartnerReferenceNo']
                ?? ''
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
            payload: $data,
        );
    }
}
