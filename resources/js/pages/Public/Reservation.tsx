import PublicLayout from '../../layouts/PublicLayout';
import { Head, router } from '@inertiajs/react';
import { FormEvent, useState } from 'react';

type Settings = Record<string, string | null>;

type ReservationItem = {
    productName: string;
    visitDate: string;
    quantity: number;
    unitPrice: number;
    subtotal: number;
};

type Reservation = {
    orderNumber: string;
    orderToken: string;

    customerName: string;

    status: string;
    paymentStatus: string;

    subtotal: number;
    discountAmount: number;
    totalAmount: number;

    createdAt?: string | null;
    expiresAt?: string | null;

    items: ReservationItem[];
};

type Props = {
    settings: Settings;
    reservations: Reservation[];
    error?: string | null;
    searched?: boolean;
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

function getStatusClass(status: string): string {
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

export default function Reservation({
    settings,
    reservations,
    error,
    searched = false,
}: Props) {
    const [keyword, setKeyword] = useState('');

    function handleSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        const value = keyword.trim();

        if (!value) {
            return;
        }

        router.post(
            '/reservasi',
            {
                keyword: value,
            },
            {
                preserveScroll: true,
            },
        );
    }

    function handleDetail(orderToken: string) {
        router.get(`/reservasi/${orderToken}`);
    }

    function handleReset() {
        setKeyword('');
        router.get('/reservasi');
    }

    return (
        <PublicLayout settings={settings}>
            <Head title="Cek Reservasi" />

            <main className="min-h-screen w-full overflow-x-hidden bg-[#f4fbf7] px-3 pb-10 pt-28 sm:px-5 sm:pb-14 sm:pt-28 lg:px-8">
                <div className="mx-auto w-full max-w-4xl">

                    {/* ================= HEADER ================= */}
                    <section className="mb-5 px-1 text-center sm:mb-8 sm:px-2">
                        <p className="mb-1 text-[10px] font-bold uppercase tracking-[0.16em] text-[#159f79] sm:mb-2 sm:text-xs sm:tracking-[0.2em]">
                            Dusun Semilir
                        </p>

                        <h1 className="text-[28px] font-black leading-tight tracking-tight text-gray-800 sm:text-4xl">
                            Cek Reservasi
                        </h1>

                        <p className="mx-auto mt-2 max-w-xl text-[12px] leading-5 text-gray-500 sm:mt-3 sm:text-sm sm:leading-6">
                            Cek status pesanan dan detail tiket Anda
                            menggunakan nomor order, email, atau nomor
                            WhatsApp.
                        </p>
                    </section>

                    {/* ================= SEARCH ================= */}
                    <section className="w-full rounded-2xl border border-[#e5f1ec] bg-white p-4 shadow-[0_8px_30px_rgba(21,159,121,0.08)] sm:rounded-3xl sm:p-7 lg:p-8">
                        <form onSubmit={handleSubmit}>
                            <label
                                htmlFor="reservation-keyword"
                                className="mb-2 block text-[12px] font-bold text-gray-700 sm:text-sm"
                            >
                                Nomor Order / Email / WhatsApp
                            </label>

                            <input
                                id="reservation-keyword"
                                type="text"
                                value={keyword}
                                onChange={(event) =>
                                    setKeyword(event.target.value)
                                }
                                placeholder="Contoh: ORD..., email, atau 0812..."
                                autoComplete="off"
                                className="box-border h-12 w-full min-w-0 rounded-full border border-gray-200 bg-white px-4 text-[13px] text-gray-700 outline-none transition placeholder:text-gray-400 focus:border-[#159f79] focus:ring-4 focus:ring-[#159f79]/10 sm:h-12 sm:px-5 sm:text-sm"
                            />

                            <button
                                type="submit"
                                disabled={!keyword.trim()}
                                className="mt-3 h-12 w-full rounded-full bg-[#159f79] px-4 text-[12px] font-bold text-white shadow-md transition hover:bg-[#128765] disabled:cursor-not-allowed disabled:opacity-50 sm:mt-3 sm:h-12 sm:text-sm"
                            >
                                CARI RESERVASI
                            </button>

                            <p className="mt-3 text-[10px] leading-4 text-gray-400 sm:mt-3 sm:text-xs sm:leading-5">
                                Satu email atau nomor WhatsApp dapat memiliki
                                beberapa reservasi.
                            </p>
                        </form>

                        {/* ERROR */}
                        {searched && error && (
                            <div className="mt-4 rounded-xl border border-red-100 bg-red-50 px-3.5 py-3 sm:mt-5 sm:rounded-2xl sm:px-5 sm:py-4">
                                <p className="text-[11px] font-bold text-red-600 sm:text-sm">
                                    Reservasi tidak ditemukan
                                </p>

                                <p className="mt-1 break-words text-[10px] leading-4 text-red-500 sm:text-xs sm:leading-5">
                                    {error}
                                </p>
                            </div>
                        )}
                    </section>

                    {/* ================= RESULTS ================= */}
                    {searched && reservations.length > 0 && (
                        <section className="mt-5 w-full sm:mt-6">
                            <div className="mb-3 px-1 sm:mb-4">
                                <p className="text-[9px] font-semibold uppercase tracking-wider text-[#159f79] sm:text-xs">
                                    Hasil Pencarian
                                </p>

                                <h2 className="mt-1 text-lg font-black text-gray-800 sm:text-xl">
                                    {reservations.length} Reservasi
                                </h2>
                            </div>

                            <div className="space-y-3 sm:space-y-4">
                                {reservations.map((reservation) => (
                                    <article
                                        key={reservation.orderToken}
                                        className="w-full overflow-hidden rounded-2xl border border-[#e5f1ec] bg-white p-4 shadow-[0_6px_24px_rgba(21,159,121,0.07)] transition hover:-translate-y-0.5 hover:shadow-lg sm:rounded-3xl sm:p-6"
                                    >
                                        <div className="min-w-0">

                                            {/* TOP */}
                                            <div className="flex min-w-0 items-start justify-between gap-2.5 sm:gap-4">
                                                <div className="min-w-0 flex-1">
                                                    <p className="text-[9px] font-semibold uppercase tracking-wider text-gray-400 sm:text-xs">
                                                        Nomor Reservasi
                                                    </p>

                                                    <h3 className="mt-1 break-all text-sm font-black leading-5 text-gray-800 sm:text-lg">
                                                        {reservation.orderNumber}
                                                    </h3>

                                                    <p className="mt-1 text-[9px] text-gray-400 sm:text-xs">
                                                        Dibuat{' '}
                                                        {formatDate(
                                                            reservation.createdAt,
                                                        )}
                                                    </p>
                                                </div>

                                                <span
                                                    className={`max-w-[46%] shrink-0 rounded-full px-2.5 py-1.5 text-center text-[8px] font-bold leading-3 sm:max-w-none sm:px-4 sm:py-2 sm:text-xs ${getStatusClass(
                                                        reservation.paymentStatus,
                                                    )}`}
                                                >
                                                    {formatStatus(
                                                        reservation.paymentStatus,
                                                    )}
                                                </span>
                                            </div>

                                            {/* ITEMS */}
                                            <div className="mt-3 space-y-2 sm:mt-5">
                                                {reservation.items.map(
                                                    (item, index) => (
                                                        <div
                                                            key={`${item.productName}-${item.visitDate}-${index}`}
                                                            className="flex min-w-0 flex-col gap-1.5 rounded-xl bg-gray-50 px-3 py-2.5 sm:flex-row sm:items-center sm:justify-between sm:gap-3 sm:rounded-2xl sm:px-4 sm:py-3"
                                                        >
                                                            <div className="min-w-0">
                                                                <p className="break-words text-[11px] font-bold leading-4 text-gray-800 sm:text-sm">
                                                                    {
                                                                        item.productName
                                                                    }
                                                                </p>

                                                                <p className="mt-0.5 break-words text-[9px] leading-4 text-gray-500 sm:text-xs">
                                                                    📅{' '}
                                                                    {formatDate(
                                                                        item.visitDate,
                                                                    )}{' '}
                                                                    •{' '}
                                                                    {
                                                                        item.quantity
                                                                    }{' '}
                                                                    tiket
                                                                </p>
                                                            </div>

                                                            <p className="shrink-0 text-[11px] font-bold text-[#159f79] sm:text-sm">
                                                                {formatRupiah(
                                                                    item.subtotal,
                                                                )}
                                                            </p>
                                                        </div>
                                                    ),
                                                )}
                                            </div>

                                            {/* BOTTOM */}
                                            <div className="mt-3 flex min-w-0 flex-col gap-3 border-t border-gray-100 pt-3 sm:mt-5 sm:flex-row sm:items-center sm:justify-between sm:gap-4 sm:pt-4">
                                                <div className="min-w-0">
                                                    <p className="text-[9px] text-gray-400 sm:text-xs">
                                                        Total Pembayaran
                                                    </p>

                                                    <p className="mt-0.5 text-base font-black text-[#159f79] sm:text-lg">
                                                        {formatRupiah(
                                                            reservation.totalAmount,
                                                        )}
                                                    </p>
                                                </div>

                                                <button
                                                    type="button"
                                                    onClick={() =>
                                                        handleDetail(
                                                            reservation.orderToken,
                                                        )
                                                    }
                                                    className="h-10 w-full shrink-0 rounded-full bg-[#159f79] px-4 text-[10px] font-bold text-white shadow-md transition hover:bg-[#128765] sm:h-auto sm:w-auto sm:px-6 sm:py-3 sm:text-sm"
                                                >
                                                    LIHAT DETAIL
                                                </button>
                                            </div>
                                        </div>
                                    </article>
                                ))}
                            </div>

                            {/* RESET */}
                            <div className="flex justify-center px-1 py-5 sm:py-6">
                                <button
                                    type="button"
                                    onClick={handleReset}
                                    className="text-[11px] font-bold text-[#159f79] transition hover:text-[#128765] sm:text-sm"
                                >
                                    ← Cari Reservasi Lain
                                </button>
                            </div>
                        </section>
                    )}

                    {/* ================= EMPTY ================= */}
                    {searched &&
                        !error &&
                        reservations.length === 0 && (
                            <div className="mt-5 rounded-2xl border border-[#e5f1ec] bg-white p-6 text-center shadow-sm sm:mt-6 sm:rounded-3xl sm:p-8">
                                <p className="text-sm font-bold text-gray-700 sm:text-base">
                                    Belum ada reservasi
                                </p>

                                <p className="mt-1 text-[10px] leading-4 text-gray-400 sm:text-sm sm:leading-5">
                                    Silakan lakukan pencarian menggunakan data
                                    reservasi Anda.
                                </p>
                            </div>
                        )}
                </div>
            </main>
        </PublicLayout>
    );
}
