<?php

namespace App\Domain\Payments\Actions;

use App\Domain\AuditLogs\Services\AuditLogService;
use App\Domain\Payments\Services\EspayService;
use App\Models\Payment;
use RuntimeException;

final class GenerateEspayQrisAction
{
    public function __construct(
        private readonly EspayService $espayService,
        private readonly AuditLogService $auditLogService,
    ) {}

    public function execute(Payment $payment): array
    {
        /*
         * Jangan generate ulang kalau QRIS
         * sudah pernah berhasil dibuat.
         */
        if (! empty($payment->qr_code)) {
            return [
                'reference_no' => $payment->gateway_reference,
                'qr_url' => $payment->payment_url,
                'qr_content' => $payment->qr_code,
                'external_id' => data_get(
                    $payment->metadata,
                    'external_id'
                ),
                'response_code' => '2004700',
                'response_message' => 'Successful',
            ];
        }

        /*
         * Payment harus masih pending.
         */
        if ($payment->status !== 'PENDING') {
            throw new RuntimeException(
                "Payment {$payment->payment_number} tidak dalam status PENDING."
            );
        }

        /*
         * Simpan kondisi sebelum update
         * untuk Audit Log.
         */
        $oldValues = [
            'status' => $payment->status,
            'gateway_reference' => $payment->gateway_reference,
            'payment_url' => $payment->payment_url,
            'qr_code' => $payment->qr_code,
        ];

        /*
         * Request ke ESPay.
         */
        $qris = $this->espayService->generateQris(
            $payment
        );

        /*
         * Pastikan ESPay benar-benar
         * mengembalikan QR.
         */
        if (
            empty($qris['qr_content']) &&
            empty($qris['qr_url'])
        ) {
            throw new RuntimeException(
                sprintf(
                    'ESPay tidak mengembalikan QRIS. Code: %s - %s',
                    $qris['response_code'] ?? '-',
                    $qris['response_message'] ?? '-',
                )
            );
        }

        /*
         * Simpan hasil QRIS.
         */
        $metadata = is_array($payment->metadata)
            ? $payment->metadata
            : [];

        $metadata = array_merge(
            $metadata,
            [
                'external_id' =>
                    $qris['external_id'] ?? null,

                'response_code' =>
                    $qris['response_code'] ?? null,

                'response_message' =>
                    $qris['response_message'] ?? null,

                'partner_reference_no' =>
                    $qris['partner_reference_no'] ?? null,

                'qr_generated_at' =>
                    now()->toIso8601String(),
            ]
        );

        $payment->update([
            'gateway_reference' =>
                $qris['reference_no'] ?? null,

            'payment_url' =>
                $qris['qr_url'] ?? null,

            'qr_code' =>
                $qris['qr_content'] ?? null,

            'metadata' =>
                $metadata,
        ]);

        /*
         * Audit hanya jika QRIS berhasil.
         */
        $this->auditLogService->log(
            action: 'UPDATE',
            module: 'PAYMENT',
            model: $payment,
            description:
                "QRIS berhasil dibuat untuk payment {$payment->payment_number}",
            oldValues: $oldValues,
            newValues: [
                'status' =>
                    $payment->status,

                'gateway_reference' =>
                    $payment->gateway_reference,

                'payment_url' =>
                    $payment->payment_url,

                'payment_method' =>
                    $payment->payment_method,

                'amount' =>
                    $payment->amount,
            ],
        );

        return $qris;
    }
}