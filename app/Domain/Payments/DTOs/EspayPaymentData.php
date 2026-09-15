<?php

namespace App\Domain\Payments\DTOs;

final class EspayPaymentData
{
    public function __construct(
        public readonly string $virtualAccountNo,
        public readonly ?string $paymentRequestId,
        public readonly ?string $paidAmount,
        public readonly ?string $totalAmount,
        public readonly string $currency,
        public readonly ?string $transactionStatus,
        public readonly ?string $paymentRef,
        public readonly ?string $rrn,
        public readonly ?string $approvalCode,
        public readonly ?string $trxId,
        public readonly array $payload,
    ) {}

    public static function fromArray(array $data): self
    {
        $additionalInfo = $data['additionalInfo'] ?? [];

        return new self(
            virtualAccountNo: (string) (
                $data['virtualAccountNo'] ?? ''
            ),

            paymentRequestId:
                isset($data['paymentRequestId'])
                    ? (string) $data['paymentRequestId']
                    : null,

            paidAmount:
                isset($data['paidAmount']['value'])
                    ? (string) $data['paidAmount']['value']
                    : null,

            totalAmount:
                isset($data['totalAmount']['value'])
                    ? (string) $data['totalAmount']['value']
                    : null,

            currency: (string) (
                $data['paidAmount']['currency']
                ?? $data['totalAmount']['currency']
                ?? 'IDR'
            ),

            transactionStatus:
                isset($additionalInfo['transactionStatus'])
                    ? (string) $additionalInfo['transactionStatus']
                    : null,

            paymentRef:
                isset($additionalInfo['paymentRef'])
                    ? (string) $additionalInfo['paymentRef']
                    : null,

            rrn:
                isset($additionalInfo['rrn'])
                    ? (string) $additionalInfo['rrn']
                    : null,

            approvalCode:
                isset($additionalInfo['approvalCode'])
                    ? (string) $additionalInfo['approvalCode']
                    : null,

            trxId:
                isset($additionalInfo['trxId'])
                    ? (string) $additionalInfo['trxId']
                    : null,

            payload: $data,
        );
    }
}