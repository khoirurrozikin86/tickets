import PublicLayout from '../../layouts/PublicLayout';
import { Head, router } from '@inertiajs/react';

type Settings = Record<string, string | null>;

type ReservationItem = {
    productName: string;
    visitDate: string;
    quantity: number;
    unitPrice: number;
    subtotal: number;
};

type Ticket = {
    ticketNumber: string;
    productName: string;
    visitDate: string;
    status: string;
};

type Payment = {
    paymentNumber: string;
    gateway: string;
    method: string;
    channel: string;
    amount: number;
    status: string;
    expiredAt?: string | null;
    paidAt?: string | null;
};

type Reservation = {
    orderNumber: string;
    orderToken: string;

    customerName: string;
    customerEmail: string;
    customerPhone: string;

    status: string;
    paymentStatus: string;

    subtotal: number;
    discountAmount: number;
    totalAmount: number;

    createdAt?: string | null;
    expiresAt?: string | null;
    paidAt?: string | null;

    items: ReservationItem[];
    tickets: Ticket[];
    payments: Payment[];
};

type Props = {
    settings: Settings;
    reservation: Reservation;
};

function formatRupiah(value: number): string {
    return new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);
}

function formatDate(date?: string | null): string {
    if (!date) return '-';

    const value = date.substring(0, 10);
    const [year, month, day] = value.split('-');

    if (!year || !month || !day) {
        return date;
    }

    return new Intl.DateTimeFormat('id-ID', {
        day: '2-digit',
        month: 'long',
        year: 'numeric',
    }).format(
        new Date(
            Number(year),
            Number(month) - 1,
            Number(day),
        ),
    );
}

function formatStatus(status: string): string {
    const labels: Record<string, string> = {
        PENDING: 'Menunggu Pembayaran',
        PAID: 'Sudah Dibayar',
        SUCCESS: 'Berhasil',
        COMPLETED: 'Selesai',
        CANCELLED: 'Dibatalkan',
        EXPIRED: 'Kadaluarsa',
        ACTIVE: 'Aktif',
        USED: 'Sudah Digunakan',
    };

    return labels[status] ?? status;
}

function statusClass(status: string): string {
    switch (status) {
        case 'PAID':
        case 'SUCCESS':
        case 'COMPLETED':
        case 'ACTIVE':
            return 'bg-[#e7f7f1] text-[#159f79]';

        case 'PENDING':
            return 'bg-amber-50 text-amber-600';

        case 'CANCELLED':
        case 'EXPIRED':
            return 'bg-red-50 text-red-500';

        case 'USED':
            return 'bg-gray-100 text-gray-500';

        default:
            return 'bg-gray-100 text-gray-600';
    }
}

export default function ReservationDetail({
    settings,
    reservation,
}: Props) {
    return (
        <PublicLayout settings={settings}>
            <Head title={`Reservasi ${reservation.orderNumber}`} />

            <main className="min-h-screen bg-[#f4fbf7] px-4 py-10 sm:py-14">
                <div className="mx-auto max-w-4xl">

                    {/* BACK */}
                    <button
                        type="button"
                        onClick={() => router.get('/reservasi')}
                        className="mb-5 text-sm font-bold text-[#159f79] hover:text-[#128765]"
                    >
                        ← Kembali ke Pencarian
                    </button>

                    {/* HEADER */}
                    <section className="rounded-3xl bg-white p-6 shadow-lg sm:p-8">
                        <div className="flex flex-col justify-between gap-5 sm:flex-row sm:items-center">

                            <div>
                                <p className="text-xs font-semibold uppercase tracking-wider text-gray-400">
                                    Nomor Reservasi
                                </p>

                                <h1 className="mt-1 break-all text-2xl font-black text-gray-800">
                                    {reservation.orderNumber}
                                </h1>
                            </div>

                            <span
                                className={`w-fit rounded-full px-4 py-2 text-xs font-bold ${statusClass(
                                    reservation.paymentStatus,
                                )}`}
                            >
                                {formatStatus(
                                    reservation.paymentStatus,
                                )}
                            </span>
                        </div>
                    </section>

                    {/* CUSTOMER */}
                    <section className="mt-5 rounded-3xl bg-white p-6 shadow-lg sm:p-8">
                        <h2 className="text-lg font-black text-gray-800">
                            Data Pemesan
                        </h2>

                        <div className="mt-5 grid gap-5 sm:grid-cols-3">
                            <div>
                                <p className="text-xs text-gray-400">
                                    Nama
                                </p>
                                <p className="mt-1 text-sm font-semibold text-gray-700">
                                    {reservation.customerName || '-'}
                                </p>
                            </div>

                            <div>
                                <p className="text-xs text-gray-400">
                                    Email
                                </p>
                                <p className="mt-1 break-all text-sm font-semibold text-gray-700">
                                    {reservation.customerEmail || '-'}
                                </p>
                            </div>

                            <div>
                                <p className="text-xs text-gray-400">
                                    WhatsApp
                                </p>
                                <p className="mt-1 text-sm font-semibold text-gray-700">
                                    {reservation.customerPhone || '-'}
                                </p>
                            </div>
                        </div>
                    </section>

                    {/* ITEMS */}
                    <section className="mt-5 rounded-3xl bg-white p-6 shadow-lg sm:p-8">
                        <h2 className="text-lg font-black text-gray-800">
                            Detail Tiket
                        </h2>

                        <div className="mt-5 space-y-3">
                            {reservation.items.map((item, index) => (
                                <div
                                    key={`${item.productName}-${index}`}
                                    className="rounded-2xl bg-gray-50 p-4"
                                >
                                    <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                        <div>
                                            <p className="font-bold text-gray-800">
                                                {item.productName}
                                            </p>

                                            <p className="mt-1 text-sm text-gray-500">
                                                📅{' '}
                                                {formatDate(
                                                    item.visitDate,
                                                )}
                                            </p>

                                            <p className="mt-1 text-xs text-gray-400">
                                                {item.quantity} tiket
                                            </p>
                                        </div>

                                        <p className="font-bold text-[#159f79]">
                                            {formatRupiah(
                                                item.subtotal,
                                            )}
                                        </p>
                                    </div>
                                </div>
                            ))}
                        </div>

                        <div className="mt-6 border-t border-gray-100 pt-5">
                            <div className="flex justify-between text-sm text-gray-500">
                                <span>Subtotal</span>
                                <span>
                                    {formatRupiah(
                                        reservation.subtotal,
                                    )}
                                </span>
                            </div>

                            {reservation.discountAmount > 0 && (
                                <div className="mt-2 flex justify-between text-sm text-gray-500">
                                    <span>Diskon</span>
                                    <span className="text-[#159f79]">
                                        -{' '}
                                        {formatRupiah(
                                            reservation.discountAmount,
                                        )}
                                    </span>
                                </div>
                            )}

                            <div className="mt-4 flex justify-between border-t border-gray-100 pt-4">
                                <span className="font-black text-gray-800">
                                    Total
                                </span>

                                <span className="text-xl font-black text-[#159f79]">
                                    {formatRupiah(
                                        reservation.totalAmount,
                                    )}
                                </span>
                            </div>
                        </div>
                    </section>

                    {/* PAYMENT */}
                    <section className="mt-5 rounded-3xl bg-white p-6 shadow-lg sm:p-8">
                        <h2 className="text-lg font-black text-gray-800">
                            Pembayaran
                        </h2>

                        {reservation.payments.length > 0 ? (
                            <div className="mt-5 space-y-3">
                                {reservation.payments.map((payment) => (
                                    <div
                                        key={payment.paymentNumber}
                                        className="rounded-2xl border border-gray-100 p-4"
                                    >
                                        <div className="flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                                            <div>
                                                <p className="text-xs text-gray-400">
                                                    Nomor Pembayaran
                                                </p>

                                                <p className="mt-1 font-bold text-gray-800">
                                                    {payment.paymentNumber}
                                                </p>

                                                <p className="mt-1 text-xs text-gray-500">
                                                    {payment.method} •{' '}
                                                    {payment.channel}
                                                </p>
                                            </div>

                                            <div className="sm:text-right">
                                                <p className="font-bold text-[#159f79]">
                                                    {formatRupiah(
                                                        payment.amount,
                                                    )}
                                                </p>

                                                <span
                                                    className={`mt-2 inline-flex rounded-full px-3 py-1 text-xs font-bold ${statusClass(
                                                        payment.status,
                                                    )}`}
                                                >
                                                    {formatStatus(
                                                        payment.status,
                                                    )}
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <p className="mt-4 text-sm text-gray-400">
                                Belum ada data pembayaran.
                            </p>
                        )}
                    </section>

                    {/* E-TICKET */}
                    <section className="mt-5 rounded-3xl bg-white p-6 shadow-lg sm:p-8">
                        <div className="flex items-center justify-between">
                            <h2 className="text-lg font-black text-gray-800">
                                E-Tiket
                            </h2>

                            {reservation.tickets.length > 0 && (
                                <span className="rounded-full bg-[#e7f7f1] px-3 py-1 text-xs font-bold text-[#159f79]">
                                    {reservation.tickets.length} Tiket
                                </span>
                            )}
                        </div>

                        {reservation.tickets.length > 0 ? (
                            <div className="mt-5 space-y-3">
                                {reservation.tickets.map((ticket) => (
                                    <div
                                        key={ticket.ticketNumber}
                                        className="rounded-2xl border border-gray-100 p-4"
                                    >
                                        <div className="flex flex-col justify-between gap-3 sm:flex-row sm:items-center">
                                            <div>
                                                <p className="font-bold text-gray-800">
                                                    {ticket.productName}
                                                </p>

                                                <p className="mt-1 text-xs text-gray-400">
                                                    {ticket.ticketNumber}
                                                </p>

                                                <p className="mt-1 text-xs text-gray-500">
                                                    📅{' '}
                                                    {formatDate(
                                                        ticket.visitDate,
                                                    )}
                                                </p>
                                            </div>

                                            <span
                                                className={`w-fit rounded-full px-4 py-2 text-xs font-bold ${statusClass(
                                                    ticket.status,
                                                )}`}
                                            >
                                                {formatStatus(
                                                    ticket.status,
                                                )}
                                            </span>
                                        </div>
                                    </div>
                                ))}
                            </div>
                        ) : (
                            <div className="mt-5 rounded-2xl bg-gray-50 p-6 text-center">
                                <p className="font-bold text-gray-700">
                                    E-tiket belum tersedia
                                </p>

                                <p className="mt-1 text-xs leading-5 text-gray-400">
                                    E-tiket akan tersedia setelah pembayaran
                                    berhasil dikonfirmasi.
                                </p>
                            </div>
                        )}
                    </section>

                </div>
            </main>
        </PublicLayout>
    );
}