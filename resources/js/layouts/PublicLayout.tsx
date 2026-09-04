import { Link } from '@inertiajs/react';
import { ReactNode } from 'react';

interface PublicLayoutProps {
    children: ReactNode;
}

export default function PublicLayout({ children }: PublicLayoutProps) {
    return (
        <div className="min-h-screen bg-[#F0FFF7] text-gray-800">

            {/* Header */}
            <header className="sticky top-0 z-50 border-b border-green-100 bg-white/90 backdrop-blur">
                <div className="mx-auto flex max-w-7xl items-center justify-between px-6 py-4">

                    {/* Logo */}
                    <Link
                        href="/"
                        className="flex items-center gap-3"
                    >
                        <div className="flex h-11 w-11 items-center justify-center rounded-2xl bg-green-600 text-xl font-bold text-white">
                            DS
                        </div>

                        <div>
                            <div className="font-bold text-green-800">
                                Dusun Semilir
                            </div>

                            <div className="text-xs text-gray-500">
                                Official Ticketing
                            </div>
                        </div>
                    </Link>

                    {/* Navigation */}
                    <nav className="hidden items-center gap-8 md:flex">

                        <Link
                            href="/"
                            className="text-sm font-medium text-gray-700 transition hover:text-green-600"
                        >
                            Beranda
                        </Link>

                        <a
                            href="#tiket"
                            className="text-sm font-medium text-gray-700 transition hover:text-green-600"
                        >
                            Tiket
                        </a>

                        <a
                            href="#informasi"
                            className="text-sm font-medium text-gray-700 transition hover:text-green-600"
                        >
                            Informasi
                        </a>

                        <Link
                            href="/cek-pesanan"
                            className="text-sm font-medium text-gray-700 transition hover:text-green-600"
                        >
                            Cek Pesanan
                        </Link>
                    </nav>

                    {/* Cart */}
                    <Link
                        href="/checkout"
                        className="rounded-full bg-orange-500 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-orange-600"
                    >
                        Keranjang
                    </Link>

                </div>
            </header>

            {/* Content */}
            <main>
                {children}
            </main>

            {/* Footer */}
            <footer className="mt-20 bg-green-900 text-white">
                <div className="mx-auto max-w-7xl px-6 py-12">

                    <div className="grid gap-8 md:grid-cols-3">

                        <div>
                            <h3 className="text-lg font-bold">
                                Dusun Semilir
                            </h3>

                            <p className="mt-3 text-sm leading-6 text-green-100">
                                Nikmati pengalaman wisata bersama keluarga
                                dan orang-orang tersayang di Dusun Semilir.
                            </p>
                        </div>

                        <div>
                            <h3 className="font-semibold">
                                Informasi
                            </h3>

                            <div className="mt-3 space-y-2 text-sm text-green-100">
                                <p>Jam Operasional</p>
                                <p>Lokasi</p>
                                <p>Ketentuan Tiket</p>
                            </div>
                        </div>

                        <div>
                            <h3 className="font-semibold">
                                Bantuan
                            </h3>

                            <div className="mt-3 space-y-2 text-sm text-green-100">
                                <p>WhatsApp</p>
                                <p>Email</p>
                                <p>Cek Pesanan</p>
                            </div>
                        </div>

                    </div>

                    <div className="mt-10 border-t border-green-800 pt-6 text-sm text-green-200">
                        © {new Date().getFullYear()} Dusun Semilir. All rights reserved.
                    </div>

                </div>
            </footer>

        </div>
    );
}