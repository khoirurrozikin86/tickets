import { Link } from '@inertiajs/react';

interface NavbarProps {
    logo?: string | null;
    siteName?: string;
}

export default function Navbar({
    logo,
    siteName = 'Dusun Semilir',
}: NavbarProps) {
    return (
        <header className="fixed top-0 left-0 right-0 z-50 px-4 pt-4">
            <div className="mx-auto max-w-7xl">
                <nav className="flex items-center justify-between rounded-2xl border border-white/30 bg-white/85 px-5 py-3 shadow-lg shadow-black/5 backdrop-blur-xl">

                    {/* Logo */}
                    <Link
                        href="/"
                        className="flex items-center gap-3"
                    >
                        {logo ? (
                            <img
                                src={logo}
                                alt={siteName}
                                className="h-10 w-auto object-contain"
                            />
                        ) : (
                            <div className="flex h-10 w-10 items-center justify-center rounded-xl bg-emerald-700 font-bold text-white">
                                DS
                            </div>
                        )}

                        <span className="hidden text-sm font-bold text-emerald-950 sm:block">
                            {siteName}
                        </span>
                    </Link>


                    {/* Navigation */}
                    <div className="flex items-center gap-2 sm:gap-3">

                        <Link
                            href="/"
                            className="hidden rounded-xl px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-emerald-50 hover:text-emerald-700 sm:block"
                        >
                            Beranda
                        </Link>

                        <Link
                            href="/reservasi"
                            className="hidden rounded-xl px-4 py-2 text-sm font-medium text-gray-700 transition hover:bg-emerald-50 hover:text-emerald-700 sm:block"
                        >
                            Cek Reservasi
                        </Link>

                        <Link
                            href="/#ticket"
                            className="rounded-xl bg-emerald-700 px-4 py-2.5 text-sm font-semibold text-white shadow-md transition hover:-translate-y-0.5 hover:bg-emerald-800"
                        >
                            Beli Tiket
                        </Link>

                    </div>

                </nav>
            </div>
        </header>
    );
}