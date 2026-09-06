import React, { FormEvent, useState } from 'react';
import { router } from '@inertiajs/react';
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
                name,
                email,
                phone,
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
            <main className="min-h-screen bg-[#f5faf7] px-4 py-8 sm:px-6 lg:px-8">
                <div className="mx-auto max-w-6xl">

                    {/* Breadcrumb */}
                    <div className="mb-6 text-sm text-gray-500">
                        <span>Beranda</span>
                        <span className="mx-2">/</span>
                        <span>Pilih Tiket</span>
                        <span className="mx-2">/</span>
                        <span className="font-medium text-[#159f79]">
                            Checkout
                        </span>
                    </div>

                    {/* Header */}
                    <div className="mb-7">
                        <h1 className="text-2xl font-bold text-gray-800 sm:text-3xl">
                            Checkout
                        </h1>

                        <p className="mt-2 text-sm text-gray-500">
                            Lengkapi data pemesan untuk melanjutkan pembayaran tiket.
                        </p>
                    </div>

                    <form onSubmit={handleSubmit}>
                        <div className="grid grid-cols-1 gap-6 lg:grid-cols-[1fr_390px]">

                            {/* =========================
                                LEFT - DATA PEMESAN
                            ========================== */}
                            <div className="space-y-6">

                                <div className="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                                    <div className="mb-6">
                                        <h2 className="text-lg font-bold text-gray-800">
                                            Data Pemesan
                                        </h2>

                                        <p className="mt-1 text-sm text-gray-500">
                                            Data ini digunakan untuk mengirim e-tiket.
                                        </p>
                                    </div>

                                    <div className="space-y-5">

                                        {/* Nama */}
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
                                                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#159f79] focus:ring-2 focus:ring-[#159f79]/10"
                                            />
                                        </div>

                                        {/* Email */}
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
                                                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#159f79] focus:ring-2 focus:ring-[#159f79]/10"
                                            />

                                            <p className="mt-2 text-xs text-gray-400">
                                                E-tiket akan dikirim ke alamat email ini.
                                            </p>
                                        </div>

                                        {/* Phone */}
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
                                                value={phone}
                                                onChange={(event) =>
                                                    setPhone(event.target.value)
                                                }
                                                placeholder="08xxxxxxxxxx"
                                                autoComplete="tel"
                                                className="w-full rounded-xl border border-gray-200 bg-white px-4 py-3 text-sm outline-none transition focus:border-[#159f79] focus:ring-2 focus:ring-[#159f79]/10"
                                            />

                                            <p className="mt-2 text-xs text-gray-400">
                                                Gunakan nomor yang aktif untuk menerima informasi tiket.
                                            </p>
                                        </div>
                                    </div>
                                </div>

                                {/* Syarat */}
                                <div className="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">
                                    <h2 className="text-lg font-bold text-gray-800">
                                        Syarat & Ketentuan
                                    </h2>

                                    <div className="mt-4 rounded-xl bg-[#f5faf7] p-4 text-sm leading-6 text-gray-600">
                                        <ul className="list-disc space-y-1 pl-5">
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

                                    <label className="mt-5 flex cursor-pointer items-start gap-3">
                                        <input
                                            type="checkbox"
                                            checked={agree}
                                            onChange={(event) =>
                                                setAgree(event.target.checked)
                                            }
                                            className="mt-1 h-4 w-4 rounded border-gray-300 accent-[#159f79]"
                                        />

                                        <span className="text-sm leading-6 text-gray-600">
                                            Saya telah membaca dan menyetujui{' '}
                                            <span className="font-semibold text-[#159f79]">
                                                Syarat & Ketentuan
                                            </span>{' '}
                                            pembelian tiket.
                                        </span>
                                    </label>
                                </div>
                            </div>

                            {/* =========================
                                RIGHT - RINGKASAN
                            ========================== */}
                            <div>
                                <div className="sticky top-6 rounded-2xl bg-white p-6 shadow-sm ring-1 ring-black/5">

                                    <h2 className="text-lg font-bold text-gray-800">
                                        Detail Tiket
                                    </h2>

                                    {/* Product */}
                                    <div className="mt-5 rounded-xl bg-[#f5faf7] p-4">
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <p className="text-xs font-medium uppercase tracking-wide text-gray-400">
                                                    Tiket
                                                </p>

                                                <h3 className="mt-1 text-base font-bold text-gray-800">
                                                    {product.name}
                                                </h3>
                                            </div>

                                            <span className="rounded-full bg-[#159f79]/10 px-3 py-1 text-xs font-bold text-[#159f79]">
                                                {dayType}
                                            </span>
                                        </div>
                                    </div>

                                    {/* Date */}
                                    <div className="mt-5">
                                        <p className="text-xs font-medium text-gray-400">
                                            Tanggal Kunjungan
                                        </p>

                                        <p className="mt-1 text-sm font-bold text-gray-800">
                                            {formatLongDate(date)}
                                        </p>
                                    </div>

                                    {/* Quantity */}
                                    <div className="mt-4 flex items-center justify-between border-b border-gray-100 pb-5">
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

                                    {/* Summary */}
                                    <div className="mt-5">
                                        <h3 className="mb-4 text-base font-bold text-gray-800">
                                            Ringkasan Pembayaran
                                        </h3>

                                        <div className="space-y-3 text-sm">
                                            <div className="flex justify-between gap-4">
                                                <span className="text-gray-500">
                                                    Subtotal
                                                </span>

                                                <span className="font-semibold text-gray-800">
                                                    {formatRupiah(subtotal)}
                                                </span>
                                            </div>

                                            {discount && discountAmount > 0 && (
                                                <div className="flex justify-between gap-4">
                                                    <span className="text-gray-500">
                                                        Diskon ({discount.code})
                                                    </span>

                                                    <span className="font-semibold text-red-500">
                                                        - {formatRupiah(discountAmount)}
                                                    </span>
                                                </div>
                                            )}

                                            <div className="border-t border-gray-100 pt-4">
                                                <div className="flex items-end justify-between gap-4">
                                                    <span className="font-bold text-gray-700">
                                                        Total
                                                    </span>

                                                    <span className="text-xl font-extrabold text-[#159f79]">
                                                        {formatRupiah(total)}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {/* Button */}
                                    <button
                                        type="submit"
                                        disabled={loading || !agree}
                                        className="mt-6 w-full rounded-full bg-[#159f79] px-5 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-[#128765] disabled:cursor-not-allowed disabled:opacity-50"
                                    >
                                        {loading
                                            ? 'Memproses...'
                                            : 'Lanjut ke Pembayaran'}
                                    </button>

                                    <p className="mt-4 text-center text-xs leading-5 text-gray-400">
                                        Pastikan data pemesan dan tanggal kunjungan
                                        sudah benar sebelum melanjutkan.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </main>
        </PublicLayout>
    );
}