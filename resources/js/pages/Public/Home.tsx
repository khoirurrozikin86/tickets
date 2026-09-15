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
                min-h-[190px]
                items-end
                justify-center
                sm:min-h-[210px]
            "
        >
            {/* Main soft glow */}

            <div
                className="
                    absolute
                    bottom-4
                    h-36
                    w-36
                    rounded-full
                    bg-emerald-300/25
                    blur-3xl
                "
            />

            {/* Soft ground */}

            <div
                className="
                    absolute
                    bottom-5
                    h-12
                    w-36
                    rounded-full
                    bg-emerald-900/10
                    blur-xl
                "
            />

            {/* Decorative circle */}

            <div
                className="
                    absolute
                    bottom-5
                    h-36
                    w-36
                    rounded-full
                    border
                    border-emerald-300/25
                    bg-white/20
                    backdrop-blur-sm
                "
            />

            {/* Dashed ring */}

            <div
                className="
                    absolute
                    bottom-2
                    h-40
                    w-40
                    rounded-full
                    border
                    border-dashed
                    border-emerald-300/25
                    animate-[osilRing_22s_linear_infinite]
                "
            />

            {/* OSIL */}

            <img
                src="/images/osil.png"
                alt="OSIL Dusun Semilir"
                className="
                    relative
                    z-10
                    w-32
                    object-contain
                    drop-shadow-[0_16px_24px_rgba(6,78,59,0.20)]
                    animate-[osilFloat_4s_ease-in-out_infinite]
                    sm:w-36
                "
            />

            {/* Sparkle */}

            <span
                className="
                    absolute
                    right-[12%]
                    top-8
                    z-20
                    text-emerald-500/80
                    animate-[osilSparkle_2.2s_ease-in-out_infinite]
                "
            >
                ✦
            </span>

            <span
                className="
                    absolute
                    left-[13%]
                    top-16
                    z-20
                    text-lime-500/70
                    animate-[osilSparkle_2.8s_ease-in-out_infinite_reverse]
                "
            >
                ✦
            </span>
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
                    relative
                    overflow-hidden
                    bg-emerald-50
                    py-20
                    sm:py-24
                    lg:py-28
                "
            >
                {/* =====================================================
                    BACKGROUND
                ===================================================== */}

                <div
                    className="
                        pointer-events-none
                        absolute
                        -left-48
                        top-20
                        h-96
                        w-96
                        rounded-full
                        bg-emerald-200/30
                        blur-[110px]
                    "
                />

                <div
                    className="
                        pointer-events-none
                        absolute
                        -right-48
                        top-72
                        h-96
                        w-96
                        rounded-full
                        bg-lime-200/25
                        blur-[110px]
                    "
                />

                <div
                    className="
                        pointer-events-none
                        absolute
                        bottom-[-180px]
                        left-1/2
                        h-96
                        w-96
                        -translate-x-1/2
                        rounded-full
                        bg-emerald-300/15
                        blur-[120px]
                    "
                />

                {/* =====================================================
                    CONTENT
                ===================================================== */}

                <div
                    className="
                        relative
                        mx-auto
                        max-w-7xl
                        px-6
                        lg:px-8
                    "
                >
                    {/* =================================================
                        SECTION HEADER
                    ================================================= */}

                    <div
                        className="
                            mx-auto
                            max-w-2xl
                            text-center
                            animate-[fadeUp_.8s_ease-out_both]
                        "
                    >
                        {/* Label */}

                        <div
                            className="
                                inline-flex
                                items-center
                                gap-2
                                rounded-full
                                border
                                border-emerald-200/80
                                bg-white/60
                                px-4
                                py-2
                                text-[11px]
                                font-bold
                                uppercase
                                tracking-[0.16em]
                                text-emerald-700
                                shadow-sm
                                backdrop-blur-sm
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
                                    bg-emerald-100
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
                                mt-6
                                text-4xl
                                font-black
                                leading-tight
                                tracking-[-0.025em]
                                text-emerald-950
                                sm:text-5xl
                                lg:text-[3.4rem]
                            "
                        >
                            Pilih Tiketmu
                        </h2>

                        {/* Accent */}

                        <div
                            className="
                                mx-auto
                                mt-5
                                flex
                                items-center
                                justify-center
                                gap-1.5
                            "
                        >
                            <span
                                className="
                                    h-1
                                    w-12
                                    rounded-full
                                    bg-emerald-500
                                "
                            />

                            <span
                                className="
                                    h-1
                                    w-4
                                    rounded-full
                                    bg-emerald-300
                                "
                            />

                            <span
                                className="
                                    h-1
                                    w-1.5
                                    rounded-full
                                    bg-emerald-200
                                "
                            />
                        </div>

                        {/* Description */}

                        <p
                            className="
                                mx-auto
                                mt-6
                                max-w-xl
                                text-sm
                                leading-7
                                text-emerald-950/60
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
                                mt-14
                                grid
                                gap-6
                                sm:mt-16
                                md:grid-cols-2
                                lg:grid-cols-3
                                lg:gap-7
                            "
                        >
                            {products.map((product, index) => (
                                <article
                                    key={product.id}
                                    className="
                                        group
                                        relative
                                        animate-[cardReveal_.7s_cubic-bezier(.22,1,.36,1)_both]
                                    "
                                    style={{
                                        animationDelay: `${index * 120}ms`,
                                    }}
                                >
                                    {/* Hover glow */}

                                    <div
                                        className="
                                            pointer-events-none
                                            absolute
                                            -inset-1
                                            rounded-[2rem]
                                            bg-emerald-400/15
                                            opacity-0
                                            blur-xl
                                            transition-opacity
                                            duration-500
                                            group-hover:opacity-100
                                        "
                                    />

                                    {/* Card */}

                                    <div
                                        className="
                                            relative
                                            h-full
                                            transition-transform
                                            duration-500
                                            ease-out
                                            group-hover:-translate-y-1.5
                                        "
                                    >
                                        <ProductCard
                                            product={product}
                                        />
                                    </div>
                                </article>
                            ))}
                        </div>
                    ) : (
                        /* =================================================
                           EMPTY STATE
                        ================================================= */

                        <div
                            className="
                                mx-auto
                                mt-14
                                max-w-lg
                                animate-[fadeUp_.7s_ease-out_both]
                            "
                        >
                            <div
                                className="
                                    rounded-[2rem]
                                    border
                                    border-emerald-200/80
                                    bg-white/65
                                    px-6
                                    py-10
                                    text-center
                                    shadow-[0_15px_50px_rgba(6,78,59,0.06)]
                                    backdrop-blur-sm
                                "
                            >
                                <div
                                    className="
                                        mx-auto
                                        flex
                                        h-16
                                        w-16
                                        items-center
                                        justify-center
                                        rounded-2xl
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
                        OSIL
                    ================================================= */}

                    <section
                        className="
                            relative
                            mt-20
                            overflow-hidden
                            rounded-[2rem]
                            border
                            border-emerald-200/70
                            bg-gradient-to-br
                            from-white/80
                            via-emerald-50/80
                            to-lime-50/70
                            shadow-[0_18px_55px_rgba(6,78,59,0.07)]
                            backdrop-blur-sm
                            sm:mt-24
                        "
                    >
                        {/* Background shapes */}

                        <div
                            className="
                                pointer-events-none
                                absolute
                                -left-24
                                -top-24
                                h-52
                                w-52
                                rounded-full
                                bg-emerald-200/30
                                blur-3xl
                            "
                        />

                        <div
                            className="
                                pointer-events-none
                                absolute
                                -right-24
                                -bottom-24
                                h-56
                                w-56
                                rounded-full
                                bg-lime-200/30
                                blur-3xl
                            "
                        />

                        <div
                            className="
                                relative
                                grid
                                items-center
                                gap-4
                                px-6
                                py-8
                                sm:grid-cols-[1fr_auto]
                                sm:px-10
                                sm:py-9
                            "
                        >
                            {/* =================================================
                                TEXT
                            ================================================= */}

                            <div
                                className="
                                    relative
                                    z-10
                                    max-w-xl
                                "
                            >
                                <span
                                    className="
                                        inline-flex
                                        rounded-full
                                        bg-emerald-700/10
                                        px-3
                                        py-1
                                        text-[10px]
                                        font-bold
                                        uppercase
                                        tracking-[0.16em]
                                        text-emerald-700
                                    "
                                >
                                    Teman Liburanmu
                                </span>

                                <h3
                                    className="
                                        mt-3
                                        text-2xl
                                        font-black
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
                            </div>

                            {/* =================================================
                                OSIL
                            ================================================= */}

                            <div
                                className="
                                    relative
                                    z-10
                                    flex
                                    justify-center
                                    sm:justify-end
                                "
                            >
                                <OsilMascot />
                            </div>
                        </div>
                    </section>

                    {/* =================================================
                        SECTION END
                    ================================================= */}

                    <div
                        className="
                            mt-16
                            flex
                            items-center
                            justify-center
                            gap-3
                        "
                    >
                        <span
                            className="
                                h-px
                                w-16
                                bg-gradient-to-r
                                from-transparent
                                to-emerald-300
                            "
                        />

                        <span
                            className="
                                h-1.5
                                w-1.5
                                rounded-full
                                bg-emerald-500
                                animate-pulse
                            "
                        />

                        <span
                            className="
                                h-px
                                w-16
                                bg-gradient-to-l
                                from-transparent
                                to-emerald-300
                            "
                        />
                    </div>
                </div>

                {/* =====================================================
                    ANIMATIONS
                ===================================================== */}

                <style>{`
                    @keyframes fadeUp {
                        from {
                            opacity: 0;
                            transform: translateY(24px);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0);
                        }
                    }

                    @keyframes cardReveal {
                        from {
                            opacity: 0;
                            transform: translateY(28px) scale(.98);
                        }

                        to {
                            opacity: 1;
                            transform: translateY(0) scale(1);
                        }
                    }

                    @keyframes osilFloat {
                        0%,
                        100% {
                            transform: translateY(0) rotate(0deg);
                        }

                        50% {
                            transform: translateY(-9px) rotate(1deg);
                        }
                    }

                    @keyframes osilRing {
                        from {
                            transform: rotate(0deg);
                        }

                        to {
                            transform: rotate(360deg);
                        }
                    }

                    @keyframes osilSparkle {
                        0%,
                        100% {
                            opacity: .25;
                            transform: scale(.8) rotate(0deg);
                        }

                        50% {
                            opacity: 1;
                            transform: scale(1.12) rotate(18deg);
                        }
                    }

                    @media (prefers-reduced-motion: reduce) {
                        *,
                        *::before,
                        *::after {
                            animation-duration: 0.01ms !important;
                            animation-iteration-count: 1 !important;
                            transition-duration: 0.01ms !important;
                        }
                    }
                `}</style>
            </section>
        </PublicLayout>
    );
}