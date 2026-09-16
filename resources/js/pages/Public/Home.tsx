import HeroSlider, {
    Banner,
} from '../../components/public/HeroSlider';

import ProductCard, {
    Product,
} from '../../components/public/ProductCard';

import PublicLayout from '../../layouts/PublicLayout';

interface HomeProps {
    banners: Banner[];
    products: Product[];
    settings: Record<string, string | null | undefined>;
}

/*
|--------------------------------------------------------------------------
| Ticket Icon
|--------------------------------------------------------------------------
*/

function TicketIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            strokeLinecap="round"
            strokeLinejoin="round"
            aria-hidden="true"
        >
            <path d="M4 7a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v2.5a2.5 2.5 0 0 0 0 5V17a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2v-2.5a2.5 2.5 0 0 0 0-5V7Z" />

            <path
                d="M9 8v8"
                strokeDasharray="2 2"
            />
        </svg>
    );
}

/*
|--------------------------------------------------------------------------
| Sparkle Icon
|--------------------------------------------------------------------------
*/

function SparkleIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-4 w-4"
            fill="currentColor"
            aria-hidden="true"
        >
            <path d="m12 2 1.8 6.2L20 10l-6.2 1.8L12 18l-1.8-6.2L4 10l6.2-1.8L12 2Z" />

            <path d="m19 16 .8 2.2L22 19l-2.2.8L19 22l-.8-2.2L16 19l2.2-.8L19 16Z" />
        </svg>
    );
}

/*
|--------------------------------------------------------------------------
| OSIL Mascot
|--------------------------------------------------------------------------
*/

function OsilMascot() {
    return (
        <div
            className="
                relative
                flex
                min-h-[180px]
                items-end
                justify-center
                sm:min-h-[200px]
            "
        >
            {/* Simple ground shadow */}

            <div
                className="
                    absolute
                    bottom-4
                    h-8
                    w-28
                    rounded-full
                    bg-emerald-900/10
                    blur-md
                "
            />

            {/* OSIL */}

            <img
                src="/images/osil.png"
                alt="OSIL Dusun Semilir"
                loading="lazy"
                className="
                    relative
                    z-10
                    w-32
                    object-contain
                    drop-shadow-md
                    animate-[osilFloat_4s_ease-in-out_infinite]
                    sm:w-36
                "
            />
        </div>
    );
}

/*
|--------------------------------------------------------------------------
| Home
|--------------------------------------------------------------------------
*/

export default function Home({
    banners,
    products,
    settings,
}: HomeProps) {
    return (
        <PublicLayout settings={settings}>
            {/* =========================================================
                HERO
            ========================================================= */}

            <HeroSlider banners={banners} />

            {/* =========================================================
                TICKET SECTION
            ========================================================= */}

            <section
                id="tickets"
                className="
                    scroll-mt-28
                    bg-emerald-50
                    py-16
                    sm:py-20
                    lg:py-24
                "
            >
                <div
                    className="
                        mx-auto
                        max-w-7xl
                        px-5
                        sm:px-6
                        lg:px-8
                    "
                >
                    {/* =================================================
                        SECTION HEADER
                    ================================================= */}

                    <div className="mx-auto max-w-2xl text-center">
                        {/* Label */}

                        <div
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                bg-emerald-100
                                px-3
                                py-1.5
                                text-xs
                                font-semibold
                                text-emerald-700
                            "
                        >
                            <span
                                className="
                                    flex
                                    h-6
                                    w-6
                                    items-center
                                    justify-center
                                    rounded-full
                                    bg-white
                                    text-emerald-600
                                "
                            >
                                <TicketIcon />
                            </span>

                            Ticket Dusun Semilir

                            <span
                                className="
                                    text-emerald-500
                                    animate-pulse
                                "
                            >
                                <SparkleIcon />
                            </span>
                        </div>

                        {/* Heading */}

                        <h2
                            className="
                                mt-5
                                text-3xl
                                font-bold
                                tracking-tight
                                text-emerald-950
                                sm:text-4xl
                            "
                        >
                            Pilih Tiketmu
                        </h2>

                        {/* Description */}

                        <p
                            className="
                                mx-auto
                                mt-4
                                max-w-xl
                                text-sm
                                leading-6
                                text-slate-600
                                sm:text-base
                            "
                        >
                            Pilih tiket yang sesuai dengan kebutuhan
                            liburanmu bersama keluarga dan orang
                            tersayang.
                        </p>
                    </div>

                    {/* =================================================
                        PRODUCTS
                    ================================================= */}

                    {products.length > 0 ? (
                        <div
                            className="
                                mt-12
                                grid
                                gap-6
                                sm:mt-14
                                md:grid-cols-2
                                lg:grid-cols-3
                            "
                        >
                            {products.map((product, index) => (
                                <article
                                    key={product.id}
                                    className="
                                        animate-[cardFadeIn_.45s_ease-out_both]
                                        transition-transform
                                        duration-300
                                        hover:-translate-y-1
                                    "
                                    style={{
                                        animationDelay: `${Math.min(
                                            index * 80,
                                            240,
                                        )}ms`,
                                    }}
                                >
                                    <ProductCard
                                        product={product}
                                    />
                                </article>
                            ))}
                        </div>
                    ) : (
                        /* =================================================
                           EMPTY STATE
                        ================================================= */

                        <div className="mx-auto mt-12 max-w-lg">
                            <div
                                className="
                                    rounded-2xl
                                    border
                                    border-emerald-200
                                    bg-white
                                    px-6
                                    py-10
                                    text-center
                                    shadow-sm
                                "
                            >
                                <div
                                    className="
                                        mx-auto
                                        flex
                                        h-14
                                        w-14
                                        items-center
                                        justify-center
                                        rounded-xl
                                        bg-emerald-100
                                        text-emerald-600
                                    "
                                >
                                    <TicketIcon />
                                </div>

                                <h3
                                    className="
                                        mt-5
                                        text-base
                                        font-bold
                                        text-emerald-950
                                    "
                                >
                                    Tiket belum tersedia
                                </h3>

                                <p
                                    className="
                                        mx-auto
                                        mt-2
                                        max-w-sm
                                        text-sm
                                        leading-6
                                        text-slate-500
                                    "
                                >
                                    Silakan kembali lagi beberapa saat
                                    kemudian untuk melihat tiket yang
                                    tersedia.
                                </p>
                            </div>
                        </div>
                    )}

                    {/* =================================================
                        OSIL SECTION
                    ================================================= */}

                    <section
                        className="
        mt-16
        overflow-hidden
        rounded-2xl
        border
        border-emerald-200
        bg-gradient-to-br
        from-emerald-100
        via-emerald-50
        to-green-100
        shadow-sm
        sm:mt-20
    "
                    >
                        <div
                            className="
            grid
            items-center
            gap-6
            px-6
            py-8
            sm:grid-cols-[1fr_auto]
            sm:px-10
            sm:py-10
        "
                        >
                            <div className="max-w-xl">
                                <span
                                    className="
                    inline-flex
                    rounded-full
                    bg-emerald-600/10
                    px-3
                    py-1
                    text-[10px]
                    font-bold
                    uppercase
                    tracking-[0.14em]
                    text-emerald-700
                "
                                >
                                    Teman Liburanmu
                                </span>

                                <h3
                                    className="
                    mt-3
                    text-2xl
                    font-bold
                    leading-tight
                    tracking-tight
                    text-emerald-950
                    sm:text-3xl
                "
                                >
                                    Liburan Lebih Seru

                                    <span className="block text-emerald-600">
                                        Bersama OSIL
                                    </span>
                                </h3>

                                <p
                                    className="
                    mt-3
                    max-w-md
                    text-sm
                    leading-6
                    text-emerald-950/60
                "
                                >
                                    Siap menemani perjalananmu menikmati
                                    berbagai keseruan di Dusun Semilir.
                                </p>

                                <div
                                    className="
                    mt-5
                    h-1
                    w-12
                    rounded-full
                    bg-emerald-500
                "
                                />
                            </div>

                            <div className="flex justify-center sm:justify-end">
                                <OsilMascot />
                            </div>
                        </div>
                    </section>
                </div>
            </section>

            {/* =========================================================
                ANIMATIONS
            ========================================================= */}

            <style>{`
                @keyframes cardFadeIn {
                    from {
                        opacity: 0;
                        transform: translateY(12px);
                    }

                    to {
                        opacity: 1;
                        transform: translateY(0);
                    }
                }

                @keyframes osilFloat {
                    0%,
                    100% {
                        transform: translateY(0);
                    }

                    50% {
                        transform: translateY(-6px);
                    }
                }

                @media (prefers-reduced-motion: reduce) {
                    *,
                    *::before,
                    *::after {
                        animation: none !important;
                        transition: none !important;
                    }
                }
            `}</style>
        </PublicLayout>
    );
}