import { Link, usePage } from '@inertiajs/react';

interface NavbarProps {
    logo?: string | null;
    siteName?: string;
}

export default function Navbar({
    logo,
    siteName = 'Dusun Semilir',
}: NavbarProps) {
    const { url } = usePage();

    const isHome = url === '/' || url === '';
    const isReservation = url.startsWith('/reservasi');

    return (
        <header
            className="
                fixed
                inset-x-0
                top-0
                z-50
                px-3
                pt-3
                sm:px-5
                sm:pt-4
            "
        >
            <div className="mx-auto max-w-7xl">
                <nav
                    className="
                        flex
                        items-center
                        justify-between
                        rounded-2xl

                        border
                        border-white/70

                        bg-white/80

                        px-3
                        py-2.5

                        shadow-[0_8px_30px_rgba(15,23,42,0.08)]

                        backdrop-blur-xl

                        transition-all
                        duration-300

                        sm:px-4
                        sm:py-3
                    "
                >
                    {/* =================================================
                        BRAND
                    ================================================= */}

                    <Link
                        href="/"
                        className="
                            group
                            flex
                            min-w-0
                            items-center
                            gap-3
                        "
                    >
                        {/* Logo */}

                        {logo ? (
                            <div
                                className="
                                    flex
                                    h-10
                                    w-10
                                    shrink-0
                                    items-center
                                    justify-center
                                    overflow-hidden
                                    rounded-xl

                                    border
                                    border-slate-200/80

                                    bg-white

                                    shadow-sm

                                    transition-transform
                                    duration-300

                                    group-hover:scale-[1.03]
                                "
                            >
                                <img
                                    src={logo}
                                    alt={siteName}
                                    className="
                                        h-full
                                        w-full
                                        object-contain
                                        p-1
                                    "
                                />
                            </div>
                        ) : (
                            <div
                                className="
                                    flex
                                    h-10
                                    w-10
                                    shrink-0
                                    items-center
                                    justify-center
                                    rounded-xl

                                    bg-emerald-600

                                    text-sm
                                    font-extrabold
                                    text-white

                                    shadow-sm

                                    transition-transform
                                    duration-300

                                    group-hover:scale-[1.03]
                                "
                            >
                                DS
                            </div>
                        )}

                        {/* Brand Text */}

                        <div className="hidden min-w-0 sm:block">
                            <span
                                className="
                                    block
                                    truncate
                                    text-sm
                                    font-extrabold
                                    leading-tight
                                    tracking-[-0.01em]
                                    text-slate-900
                                "
                            >
                                {siteName}
                            </span>

                            <span
                                className="
                                    mt-0.5
                                    block
                                    text-[10px]
                                    font-medium
                                    tracking-wide
                                    text-emerald-700/70
                                "
                            >
                                Wisata Keluarga
                            </span>
                        </div>
                    </Link>

                    {/* =================================================
                        NAVIGATION
                    ================================================= */}

                    <div
                        className="
                            flex
                            items-center
                            gap-1.5
                            sm:gap-2
                        "
                    >
                        {/* =================================================
                            BERANDA
                        ================================================= */}

                        <Link
                            href="/"
                            className={`
                                hidden
                                rounded-xl
                                px-4
                                py-2.5
                                text-sm
                                font-semibold
                                transition-all
                                duration-300
                                sm:block

                                ${isHome
                                    ? `
                                            bg-emerald-50
                                            text-emerald-700
                                        `
                                    : `
                                            text-slate-600
                                            hover:bg-slate-50
                                            hover:text-emerald-700
                                        `
                                }
                            `}
                        >
                            Beranda
                        </Link>

                        {/* =================================================
                            CEK RESERVASI
                        ================================================= */}

                        <Link
                            href="/reservasi"
                            className={`
                                group
                                flex
                                items-center
                                gap-2
                                rounded-xl
                                px-4
                                py-2.5
                                text-sm
                                font-bold
                                transition-all
                                duration-300

                                ${isReservation
                                    ? `
                                            bg-emerald-700
                                            text-white
                                            shadow-[0_5px_18px_rgba(6,95,70,0.18)]
                                        `
                                    : `
                                            bg-emerald-600
                                            text-white
                                            shadow-[0_5px_18px_rgba(6,95,70,0.14)]

                                            hover:-translate-y-0.5
                                            hover:bg-emerald-700
                                            hover:shadow-[0_8px_24px_rgba(6,95,70,0.20)]
                                        `
                                }
                            `}
                        >
                            {/* Search */}

                            <svg
                                viewBox="0 0 24 24"
                                className="
                                    h-4
                                    w-4
                                    shrink-0
                                    transition-transform
                                    duration-300
                                    group-hover:scale-105
                                "
                                fill="none"
                                stroke="currentColor"
                                strokeWidth="2"
                                strokeLinecap="round"
                                strokeLinejoin="round"
                            >
                                <circle
                                    cx="11"
                                    cy="11"
                                    r="7"
                                />

                                <path d="m20 20-4-4" />
                            </svg>

                            <span>
                                Cek Reservasi
                            </span>
                        </Link>
                    </div>
                </nav>
            </div>
        </header>
    );
}