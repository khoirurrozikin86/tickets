import { useEffect, useState } from 'react';
import { router } from '@inertiajs/react';
import QRCode from 'qrcode';

import PublicLayout from '../../layouts/PublicLayout';
import { formatRupiah } from '../../lib/format';

interface OrderItem {
    productName: string;
    visitDate: string;
    quantity: number;
    unitPrice: number;
    subtotal: number;
}

interface PaymentData {
    paymentNumber: string;
    amount: number;
    method: string;
    channel: string | null;
    status: string;
    expiredAt: string | null;
    paymentUrl: string | null;
    qrCode: string | null;
}

interface Order {
    id: number;
    orderNumber: string;
    customerName: string;
    customerEmail: string;
    customerPhone: string;
    subtotal: number;
    discountAmount: number;
    totalAmount: number;
    currency: string;
    status: string;
    paymentStatus: string;
    expiresAt: string | null;
    items: OrderItem[];
    payment: PaymentData | null;
}

interface PaymentProps {
    order: Order;
    settings: Record<string, string | null>;
}

const GREEN = '#159f79';

function formatDate(date: string): string {
    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        timeZone: 'Asia/Jakarta',
    }).format(new Date(date));
}

function formatCountdown(seconds: number | null): string {
    if (seconds === null) {
        return '--:--';
    }

    const minutes = Math.floor(seconds / 60);
    const secs = seconds % 60;

    return `${String(minutes).padStart(2, '0')}:${String(secs).padStart(2, '0')}`;
}

function isPaidStatus(status: string): boolean {
    return ['PAID', 'SUCCESS', 'SETTLED', 'COMPLETED'].includes(
        status.toUpperCase(),
    );
}

function isPendingStatus(status: string): boolean {
    return ['PENDING', 'WAITING', 'UNPAID'].includes(
        status.toUpperCase(),
    );
}

function SectionTitle({ children }: { children: React.ReactNode }) {
    return (
        <h2 className="text-sm font-bold text-gray-800">
            {children}
        </h2>
    );
}

function OrderInfo({ order }: { order: Order }) {
    return (
        <div className="mt-7 rounded-2xl bg-[#f5faf7] p-5">
            <p className="text-xs font-medium text-gray-400">
                Nomor Pesanan
            </p>

            <p className="mt-1 text-xl font-bold text-[#159f79]">
                {order.orderNumber}
            </p>

            <div className="mt-4 grid gap-3 sm:grid-cols-2">
                <div>
                    <p className="text-xs text-gray-400">Nama Pemesan</p>
                    <p className="text-sm font-semibold text-gray-700">
                        {order.customerName}
                    </p>
                </div>

                <div>
                    <p className="text-xs text-gray-400">Email</p>
                    <p className="break-all text-sm font-semibold text-gray-700">
                        {order.customerEmail}
                    </p>
                </div>
            </div>
        </div>
    );
}

function OrderItems({ items }: { items: OrderItem[] }) {
    return (
        <div className="mt-6">
            <SectionTitle>Detail Pesanan</SectionTitle>

            <div className="mt-3 space-y-3">
                {items.map((item, index) => (
                    <div
                        key={`${item.productName}-${item.visitDate}-${index}`}
                        className="flex items-center justify-between gap-4 rounded-xl border border-gray-100 p-4"
                    >
                        <div className="min-w-0">
                            <p className="font-semibold text-gray-800">
                                {item.productName}
                            </p>

                            <p className="mt-1 text-xs text-gray-500">
                                {formatDate(item.visitDate)} × {item.quantity}
                            </p>
                        </div>

                        <p className="shrink-0 font-semibold text-gray-800">
                            {formatRupiah(item.subtotal)}
                        </p>
                    </div>
                ))}
            </div>
        </div>
    );
}

function OrderTotal({
    subtotal,
    discountAmount,
    totalAmount,
}: Pick<Order, 'subtotal' | 'discountAmount' | 'totalAmount'>) {
    return (
        <div className="mt-6 border-t border-gray-100 pt-5">
            <div className="flex justify-between text-sm text-gray-500">
                <span>Subtotal</span>
                <span>{formatRupiah(subtotal)}</span>
            </div>

            {discountAmount > 0 && (
                <div className="mt-2 flex justify-between text-sm text-green-600">
                    <span>Diskon</span>
                    <span>- {formatRupiah(discountAmount)}</span>
                </div>
            )}

            <div className="mt-4 flex justify-between">
                <span className="text-lg font-bold text-gray-800">
                    Total
                </span>

                <span className="text-xl font-bold text-[#159f79]">
                    {formatRupiah(totalAmount)}
                </span>
            </div>
        </div>
    );
}

function QrisPayment({
    payment,
    totalAmount,
    remainingSeconds,
}: {
    payment: PaymentData | null;
    totalAmount: number;
    remainingSeconds: number | null;
}) {
    const [qrImage, setQrImage] = useState<string | null>(null);

    const isExpired =
        remainingSeconds !== null &&
        remainingSeconds <= 0;

    useEffect(() => {
        let cancelled = false;

        if (!payment?.qrCode || isExpired || isPaidStatus(payment.status)) {
            setQrImage(null);
            return;
        }

        QRCode.toDataURL(payment.qrCode, {
            width: 320,
            margin: 2,
            errorCorrectionLevel: 'M',
        })
            .then((url) => {
                if (!cancelled) {
                    setQrImage(url);
                }
            })
            .catch((error) => {
                if (!cancelled) {
                    setQrImage(null);
                }

                console.error('Gagal membuat QRIS:', error);
            });

        return () => {
            cancelled = true;
        };
    }, [payment?.qrCode, payment?.status, isExpired]);

    if (isPaidStatus(payment?.status ?? '')) {
        return (
            <div className="mt-7 rounded-3xl border border-green-200 bg-green-50 p-7 text-center">
                <div className="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-green-100">
                    <span className="text-3xl text-green-600">✓</span>
                </div>

                <h2 className="mt-4 text-xl font-bold text-green-700">
                    Pembayaran Berhasil
                </h2>

                <p className="mt-2 text-sm leading-6 text-gray-600">
                    Pembayaran Anda telah berhasil dikonfirmasi.
                    Pesanan sedang diproses secara otomatis.
                </p>

                {payment?.paymentNumber && (
                    <div className="mt-5 rounded-xl bg-white p-3">
                        <p className="text-xs text-gray-400">
                            Nomor Pembayaran
                        </p>
                        <p className="mt-1 text-xs font-semibold text-gray-700">
                            {payment.paymentNumber}
                        </p>
                    </div>
                )}
            </div>
        );
    }

    return (
        <div className="mt-7 rounded-3xl border border-[#159f79]/20 bg-[#159f79]/5 p-5 sm:p-7">
            <div className="text-center">
                <p className="text-lg font-bold text-gray-800">
                    Pembayaran QRIS
                </p>

                <p className="mt-1 text-sm text-gray-500">
                    Scan QR menggunakan aplikasi pembayaran yang mendukung QRIS.
                </p>
            </div>

            <div className="mt-5 flex justify-center">
                <div
                    className={`rounded-full px-5 py-2 text-sm font-bold ${isExpired
                            ? 'bg-red-100 text-red-600'
                            : 'bg-white text-[#159f79]'
                        }`}
                >
                    {isExpired
                        ? 'QRIS Kedaluwarsa'
                        : `Berlaku ${formatCountdown(remainingSeconds)}`}
                </div>
            </div>

            <div className="mt-5 flex justify-center">
                {qrImage && !isExpired ? (
                    <div className="rounded-2xl bg-white p-4 shadow-sm">
                        <img
                            src={qrImage}
                            alt="QRIS Pembayaran"
                            className="h-[280px] w-[280px] sm:h-[320px] sm:w-[320px]"
                        />
                    </div>
                ) : (
                    <div className="flex h-[280px] w-[280px] items-center justify-center rounded-2xl bg-white text-center sm:h-[320px] sm:w-[320px]">
                        <p className="px-8 text-sm text-gray-400">
                            {isExpired
                                ? 'QRIS sudah kedaluwarsa.'
                                : 'QRIS sedang dipersiapkan...'}
                        </p>
                    </div>
                )}
            </div>

            <div className="mt-5 text-center">
                <p className="text-xs text-gray-400">
                    Total Pembayaran
                </p>

                <p className="mt-1 text-2xl font-bold text-[#159f79]">
                    {formatRupiah(totalAmount)}
                </p>
            </div>

            {payment?.paymentNumber && (
                <div className="mt-5 rounded-xl bg-white p-3 text-center">
                    <p className="text-xs text-gray-400">
                        Nomor Pembayaran
                    </p>

                    <p className="mt-1 text-xs font-semibold text-gray-700">
                        {payment.paymentNumber}
                    </p>
                </div>
            )}
        </div>
    );
}

function PaymentStatus({
    status,
    isPaid,
}: {
    status: string;
    isPaid: boolean;
}) {
    if (isPaid) {
        return (
            <div className="mt-5 rounded-2xl bg-green-50 p-4 text-center">
                <p className="text-xs text-green-600">
                    Status Pembayaran
                </p>

                <p className="mt-1 font-bold text-green-700">
                    PAID
                </p>

                <p className="mt-2 text-xs text-gray-500">
                    Pembayaran telah berhasil dikonfirmasi oleh sistem.
                </p>
            </div>
        );
    }

    return (
        <div className="mt-5 rounded-2xl bg-orange-50 p-4 text-center">
            <p className="text-xs text-orange-500">
                Status Pembayaran
            </p>

            <p className="mt-1 font-bold text-orange-600">
                {status}
            </p>

            <p className="mt-2 text-xs text-gray-500">
                Setelah pembayaran berhasil, sistem akan memproses pesanan
                secara otomatis.
            </p>
        </div>
    );
}

export default function Payment({
    order,
    settings,
}: PaymentProps) {
    const [remainingSeconds, setRemainingSeconds] = useState<number | null>(
        null,
    );

    const paymentStatus =
        order.payment?.status ?? order.paymentStatus;

    const isPaid = isPaidStatus(paymentStatus);
    const isPending = isPendingStatus(paymentStatus);

    /*
     * Polling status pembayaran.
     *
     * Espay mengirim callback ke Laravel secara asynchronous.
     * Halaman ini melakukan reload data order setiap 5 detik selama
     * pembayaran masih pending, sehingga status PAID dapat tampil
     * tanpa customer melakukan refresh manual.
     */
    useEffect(() => {
        if (!isPending || isPaid) {
            return;
        }

        const poll = window.setInterval(() => {
            router.reload({
                only: ['order'],
                preserveScroll: true,
                preserveState: true,
            });
        }, 5000);

        return () => window.clearInterval(poll);
    }, [isPending, isPaid]);

    /*
     * Countdown QRIS.
     */
    useEffect(() => {
        if (!order.payment?.expiredAt || isPaid) {
            setRemainingSeconds(null);
            return;
        }

        const updateCountdown = () => {
            const expiredAt = new Date(
                order.payment!.expiredAt!,
            ).getTime();

            const seconds = Math.max(
                0,
                Math.floor((expiredAt - Date.now()) / 1000),
            );

            setRemainingSeconds(seconds);
        };

        updateCountdown();

        const timer = window.setInterval(updateCountdown, 1000);

        return () => window.clearInterval(timer);
    }, [order.payment?.expiredAt, isPaid]);

    return (
        <PublicLayout settings={settings}>
            <main className="min-h-screen bg-[#f5faf7] px-4 py-10">
                <div className="mx-auto max-w-3xl">
                    <div className="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8">
                        {/* Header */}
                        <div className="flex justify-center">
                            <div
                                className={`flex h-16 w-16 items-center justify-center rounded-full ${isPaid
                                        ? 'bg-green-100'
                                        : 'bg-[#159f79]/10'
                                    }`}
                            >
                                <span
                                    className={`text-3xl ${isPaid
                                            ? 'text-green-600'
                                            : 'text-[#159f79]'
                                        }`}
                                >
                                    ✓
                                </span>
                            </div>
                        </div>

                        <h1 className="mt-5 text-center text-2xl font-bold text-gray-800">
                            {isPaid
                                ? 'Pembayaran Berhasil'
                                : 'Lanjut ke Pembayaran'}
                        </h1>

                        <p className="mt-2 text-center text-sm text-gray-500">
                            {isPaid
                                ? 'Pesanan Anda telah berhasil dibayar.'
                                : 'Silakan lakukan pembayaran untuk menyelesaikan pesanan.'}
                        </p>

                        <OrderInfo order={order} />

                        <OrderItems items={order.items} />

                        <OrderTotal
                            subtotal={order.subtotal}
                            discountAmount={order.discountAmount}
                            totalAmount={order.totalAmount}
                        />

                        <QrisPayment
                            payment={order.payment}
                            totalAmount={order.totalAmount}
                            remainingSeconds={remainingSeconds}
                        />

                        <PaymentStatus
                            status={paymentStatus}
                            isPaid={isPaid}
                        />

                        {isPending && !isPaid && (
                            <p className="mt-4 text-center text-xs text-gray-400">
                                Status pembayaran diperbarui otomatis setiap 5 detik.
                            </p>
                        )}
                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}
