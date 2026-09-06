import React, { FormEvent, useState } from 'react';
import { Head, router } from '@inertiajs/react';
import PublicLayout from '../../layouts/PublicLayout';
import { formatRupiah } from '../../lib/format';

interface Product {
    id: number;
    name: string;
    slug: string;
}

interface Discount {
    code: string;
    name: string;
    type: string;
    value: number;
}

interface CheckoutProps {
    product: Product;
    date: string;
    dayType: string;
    quantity: number;
    unitPrice: number;
    subtotal: number;
    discount: Discount | null;
    discountAmount: number;
    total: number;
    settings: Record<string, string | null>;
}

export default function Checkout({
    product,
    date,
    dayType,
    quantity,
    unitPrice,
    subtotal,
    discount,
    discountAmount,
    total,
    settings,
}: CheckoutProps) {
    const [name, setName] = useState('');
    const [email, setEmail] = useState('');
    const [phone, setPhone] = useState('');
    const [agree, setAgree] = useState(false);
    const [loading, setLoading] = useState(false);

    function formatLongDate(value: string) {
        const dateObject = new Date(`${value}T00:00:00`);

        return dateObject.toLocaleDateString('id-ID', {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        });
    }

    function getDayTypeLabel(type: string) {
        switch (type) {
            case 'WEEKEND':
                return 'Weekend';

            case 'HOLIDAY':
                return 'Hari Libur';

            default:
                return 'Weekday';
        }
    }

    function handleSubmit(event: FormEvent<HTMLFormElement>) {
        event.preventDefault();

        if (!name.trim()) {
            alert('Nama pemesan wajib diisi.');
            return;
        }

        if (!email.trim()) {
            alert('Email wajib diisi.');
            return;
        }

        if (!phone.trim()) {
            alert('Nomor WhatsApp / Telepon wajib diisi.');
            return;
        }

        if (!agree) {
            alert('Silakan setujui syarat dan ketentuan terlebih dahulu.');
            return;
        }

        setLoading(true);

        router.post(
            '/checkout',
            {
                product: product.slug,
                date,
                quantity,
                name: name.trim(),
                email: email.trim(),
                phone: phone.trim(),
                voucher: discount?.code ?? '',
            },
            {
                onFinish: () => {
                    setLoading(false);
                },
            }
        );
    }

    return (
        <PublicLayout settings={settings}>
            <Head title="Checkout" />

            <main className="min-h-screen bg-[#f5faf7]">
                <div className="mx-auto w-full max-w-6xl px-4 py-5 sm:px-6 sm:py-8 lg:px-8">

                    {/* =====================================================
                        BREADCRUMB
                    ====================================================== */}
                    <nav className="mb-5 flex items-center gap-2 overflow-x-auto whitespace-nowrap text-xs text-gray-400 sm:mb-7 sm:text-sm">
                        <span>Beranda</span>

                        <span>/</span>

                        <span>Pilih Tiket</span>

                        <span>/</span>

                        <span className="font-semibold text-[#159f79]">
                            Checkout
                        </span>
                    </nav>

                    {/* =====================================================
                        HEADER
                    ====================================================== */}
                    <div className="mb-6 sm:mb-8">
                        <h1 className="text-2xl font-extrabold tracking-tight text-gray-800 sm:text-3xl">
                            Checkout
                        </h1>

                        <p className="mt-1.5 max-w-2xl text-sm leading-6 text-gray-500 sm:mt-2">
                            Lengkapi data pemesan untuk melanjutkan pembayaran tiket.
                        </p>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 gap-5 lg:grid-cols-[minmax(0,1fr)_370px] lg:gap-7">

                            {/* =================================================
                                LEFT CONTENT
                            ================================================== */}
                            <div className="min-w-0 space-y-5">

                                {/* DATA PEMESAN */}
                                <section className="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-6">
                                    <div className="mb-5 sm:mb-6">
                                        <div className="flex items-center gap-3">
                                            <div className="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-[#159f79]/10 text-[#159f79]">
                                                <svg
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                    stroke="currentColor"
                                                    strokeWidth="1.8"
                                                    className="h-5 w-5"
                                                >
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M15 19a4 4 0 00-8 0"
                                                    />
                                                    <circle
                                                        cx="11"
                                                        cy="8"
                                                        r="3"
                                                    />
                                                    <path
                                                        strokeLinecap="round"
                                                        strokeLinejoin="round"
                                                        d="M19 19a4 4 0 00-3-3.87M16 5.13a3 3 0 010 5.74"
                                                    />
                                                </svg>
                                            </div>

                                            <div>
                                                <h2 className="text-base font-bold text-gray-800 sm:text-lg">
                                                    Data Pemesan
                                                </h2>

                                                <p className="mt-0.5 text-xs text-gray-400 sm:text-sm">
                                                    Data ini digunakan untuk mengirim e-tiket.
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="space-y-4 sm:space-y-5">

                                        {/* NAMA */}
                                        <div>
                                            <label
                                                htmlFor="name"
                                                className="mb-2 block text-sm font-semibold text-gray-700"
                                            >
                                                Nama Lengkap
                                                <span className="ml-1 text-red-500">
                                                    *
                                                </span>
                                            </label>

                                            <input
                                                id="name"
                                                type="text"
                                                value={name}
                                                onChange={(event) =>
                                                    setName(event.target.value)
                                                }
                                                placeholder="Masukkan nama lengkap"
                                                autoComplete="name"
                                                className="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#159f79] focus:ring-4 focus:ring-[#159f79]/10"
                                            />
                                        </div>

                                        {/* EMAIL */}
                                        <div>
                                            <label
                                                htmlFor="email"
                                                className="mb-2 block text-sm font-semibold text-gray-700"
                                            >
                                                Email
                                                <span className="ml-1 text-red-500">
                                                    *
                                                </span>
                                            </label>

                                            <input
                                                id="email"
                                                type="email"
                                                value={email}
                                                onChange={(event) =>
                                                    setEmail(event.target.value)
                                                }
                                                placeholder="contoh@email.com"
                                                autoComplete="email"
                                                className="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#159f79] focus:ring-4 focus:ring-[#159f79]/10"
                                            />

                                            <p className="mt-2 text-xs leading-5 text-gray-400">
                                                E-tiket akan dikirim ke alamat email ini.
                                            </p>
                                        </div>

                                        {/* PHONE */}
                                        <div>
                                            <label
                                                htmlFor="phone"
                                                className="mb-2 block text-sm font-semibold text-gray-700"
                                            >
                                                Nomor WhatsApp / Telepon
                                                <span className="ml-1 text-red-500">
                                                    *
                                                </span>
                                            </label>

                                            <input
                                                id="phone"
                                                type="tel"
                                                inputMode="tel"
                                                value={phone}
                                                onChange={(event) =>
                                                    setPhone(event.target.value)
                                                }
                                                placeholder="08xxxxxxxxxx"
                                                autoComplete="tel"
                                                className="h-12 w-full rounded-xl border border-gray-200 bg-white px-4 text-sm text-gray-800 outline-none transition placeholder:text-gray-400 focus:border-[#159f79] focus:ring-4 focus:ring-[#159f79]/10"
                                            />

                                            <p className="mt-2 text-xs leading-5 text-gray-400">
                                                Gunakan nomor yang aktif untuk menerima informasi tiket.
                                            </p>
                                        </div>
                                    </div>
                                </section>

                                {/* =================================================
                                    TANGGAL KUNJUNGAN
                                ================================================== */}
                                <section className="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-6">
                                    <div className="flex items-center justify-between gap-3">
                                        <div className="min-w-0">
                                            <p className="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                Tanggal Kunjungan
                                            </p>

                                            <p className="mt-1 text-base font-bold text-gray-800 sm:text-lg">
                                                {formatLongDate(date)}
                                            </p>
                                        </div>

                                        <span className="shrink-0 rounded-full bg-[#159f79]/10 px-3 py-1.5 text-[11px] font-bold text-[#159f79] sm:text-xs">
                                            {getDayTypeLabel(dayType)}
                                        </span>
                                    </div>
                                </section>

                                {/* =================================================
                                    SYARAT & KETENTUAN
                                ================================================== */}
                                <section className="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-6">
                                    <div className="mb-4">
                                        <h2 className="text-base font-bold text-gray-800 sm:text-lg">
                                            Syarat & Ketentuan
                                        </h2>
                                    </div>

                                    <div className="rounded-xl bg-[#f5faf7] p-4 text-xs leading-5 text-gray-600 sm:p-5 sm:text-sm sm:leading-6">
                                        <ul className="list-disc space-y-2 pl-4 sm:pl-5">
                                            <li>
                                                Tiket hanya dapat digunakan sesuai tanggal kunjungan.
                                            </li>

                                            <li>
                                                Pastikan data pemesan sudah benar sebelum melakukan pembayaran.
                                            </li>

                                            <li>
                                                Tiket yang sudah dibeli mengikuti ketentuan pembatalan yang berlaku.
                                            </li>

                                            <li>
                                                Tunjukkan QR Code e-tiket saat memasuki area Dusun Semilir.
                                            </li>
                                        </ul>
                                    </div>

                                    <label className="mt-4 flex cursor-pointer items-start gap-3 rounded-xl p-1 sm:mt-5">
                                        <input
                                            type="checkbox"
                                            checked={agree}
                                            onChange={(event) =>
                                                setAgree(event.target.checked)
                                            }
                                            className="mt-1 h-4 w-4 shrink-0 cursor-pointer rounded border-gray-300 accent-[#159f79]"
                                        />

                                        <span className="text-xs leading-5 text-gray-600 sm:text-sm sm:leading-6">
                                            Saya telah membaca dan menyetujui{' '}
                                            <span className="font-semibold text-[#159f79]">
                                                Syarat & Ketentuan
                                            </span>{' '}
                                            pembelian tiket.
                                        </span>
                                    </label>
                                </section>
                            </div>

                            {/* =================================================
                                RIGHT - SUMMARY
                            ================================================== */}
                            <aside className="min-w-0 lg:block">
                                <div className="rounded-2xl bg-white p-4 shadow-sm ring-1 ring-black/5 sm:p-6 lg:sticky lg:top-6">

                                    {/* TITLE */}
                                    <div className="flex items-center justify-between gap-3">
                                        <h2 className="text-base font-bold text-gray-800 sm:text-lg">
                                            Detail Tiket
                                        </h2>

                                        <span className="rounded-full bg-[#159f79]/10 px-2.5 py-1 text-[10px] font-bold uppercase text-[#159f79] sm:text-xs">
                                            {getDayTypeLabel(dayType)}
                                        </span>
                                    </div>

                                    {/* PRODUCT */}
                                    <div className="mt-4 rounded-xl bg-[#f5faf7] p-4">
                                        <p className="text-[10px] font-semibold uppercase tracking-wider text-gray-400 sm:text-xs">
                                            Tiket
                                        </p>

                                        <div className="mt-1 flex items-start justify-between gap-3">
                                            <h3 className="min-w-0 text-base font-bold text-gray-800 sm:text-lg">
                                                {product.name}
                                            </h3>
                                        </div>
                                    </div>

                                    {/* DATE */}
                                    <div className="mt-5 border-b border-gray-100 pb-5">
                                        <p className="text-xs font-medium text-gray-400">
                                            Tanggal Kunjungan
                                        </p>

                                        <p className="mt-1.5 text-sm font-bold leading-5 text-gray-800">
                                            {formatLongDate(date)}
                                        </p>
                                    </div>

                                    {/* QUANTITY */}
                                    <div className="mt-5 border-b border-gray-100 pb-5">
                                        <div className="flex items-center justify-between gap-4">
                                            <div>
                                                <p className="text-xs font-medium text-gray-400">
                                                    Jumlah Tiket
                                                </p>

                                                <p className="mt-1 text-sm font-bold text-gray-800">
                                                    {quantity} Tiket
                                                </p>
                                            </div>

                                            <div className="text-right">
                                                <p className="text-xs text-gray-400">
                                                    Harga / tiket
                                                </p>

                                                <p className="mt-1 text-sm font-semibold text-gray-700">
                                                    {formatRupiah(unitPrice)}
                                                </p>
                                            </div>
                                        </div>
                                    </div>

                                    {/* PAYMENT SUMMARY */}
                                    <div className="mt-5">
                                        <h3 className="mb-4 text-sm font-bold text-gray-800 sm:text-base">
                                            Ringkasan Pembayaran
                                        </h3>

                                        <div className="space-y-3 text-sm">

                                            {/* SUBTOTAL */}
                                            <div className="flex items-center justify-between gap-4">
                                                <span className="text-gray-500">
                                                    Subtotal
                                                </span>

                                                <span className="shrink-0 font-semibold text-gray-800">
                                                    {formatRupiah(subtotal)}
                                                </span>
                                            </div>

                                            {/* DISCOUNT */}
                                            {discount && discountAmount > 0 && (
                                                <div className="flex items-start justify-between gap-4">
                                                    <span className="min-w-0 text-gray-500">
                                                        Diskon{' '}
                                                        <span className="font-medium text-[#159f79]">
                                                            ({discount.code})
                                                        </span>
                                                    </span>

                                                    <span className="shrink-0 font-semibold text-red-500">
                                                        - {formatRupiah(discountAmount)}
                                                    </span>
                                                </div>
                                            )}

                                            {/* TOTAL */}
                                            <div className="mt-4 border-t border-gray-100 pt-4">
                                                <div className="flex items-end justify-between gap-4">
                                                    <div>
                                                        <p className="text-xs text-gray-400">
                                                            Total Pembayaran
                                                        </p>

                                                        <p className="mt-1 text-sm font-bold text-gray-700">
                                                            {quantity} Tiket
                                                        </p>
                                                    </div>

                                                    <span className="shrink-0 text-xl font-extrabold tracking-tight text-[#159f79] sm:text-2xl">
                                                        {formatRupiah(total)}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* BUTTON */}
                                    <button
                                        type="submit"
                                        disabled={loading || !agree}
                                        className="mt-6 flex h-12 w-full items-center justify-center rounded-full bg-[#159f79] px-5 text-sm font-bold text-white shadow-md transition hover:bg-[#128765] active:scale-[0.99] disabled:cursor-not-allowed disabled:opacity-50 sm:h-13"
                                    >
                                        {loading ? (
                                            <>
                                                <svg
                                                    className="mr-2 h-4 w-4 animate-spin"
                                                    viewBox="0 0 24 24"
                                                    fill="none"
                                                >
                                                    <circle
                                                        className="opacity-30"
                                                        cx="12"
                                                        cy="12"
                                                        r="9"
                                                        stroke="currentColor"
                                                        strokeWidth="3"
                                                    />

                                                    <path
                                                        d="M21 12a9 9 0 00-9-9"
                                                        stroke="currentColor"
                                                        strokeWidth="3"
                                                        strokeLinecap="round"
                                                    />
                                                </svg>

                                                Memproses...
                                            </>
                                        ) : (
                                            'Lanjut ke Pembayaran'
                                        )}
                                    </button>

                                    <p className="mt-3 text-center text-[11px] leading-5 text-gray-400 sm:text-xs">
                                        Pastikan data pemesan dan tanggal kunjungan
                                        sudah benar sebelum melanjutkan.
                                    </p>
                                </div>
                            </aside>
                        </div>
                    </form>
                </div>
            </main>
        </PublicLayout>
    );
}