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
| Home
|--------------------------------------------------------------------------
*/

export default function Home({
    banners,
    products,
    settings,
}: HomeProps) {
    const groupGallery = Array.from({ length: 6 }, (_, index) => {
        const slot = index + 1;
        const image = settings[`group_gallery_${slot}`];

        return image ? { image, slot } : null;
    }).filter((photo): photo is { image: string; slot: number } => photo !== null);

    const bookingNumber = settings.group_booking_whatsapp
        ?.replace(/\D/g, '')
        .replace(/^0/, '62');
    const bookingUrl = bookingNumber
        ? `https://wa.me/${bookingNumber}?text=${encodeURIComponent('halo admin dusem, mau beli tiket untuk rombongan')}`
        : null;

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
                                items-stretch
                                sm:mt-14
                                md:grid-cols-2
                                lg:grid-cols-2
                            "
                        >
                            {products.map((product, index) => (
                                <article
                                    key={product.id}
                                    className="
                                        h-full
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

                    {settings.event_enabled === '1' && (
                        <section
                            id="event"
                            className="mx-auto mt-16 max-w-6xl scroll-mt-28 sm:mt-20"
                        >
                            <h2 className="text-center text-2xl font-bold text-slate-900 sm:text-3xl">
                                {settings.event_title || 'Event & Aktivitas'}
                            </h2>

                            <div className={`mt-8 grid items-center gap-7 ${settings.event_image ? 'md:grid-cols-2 md:gap-12' : ''}`}>
                                {settings.event_image && (
                                    <img
                                        src={settings.event_image}
                                        alt={settings.event_title || 'Event Dusun Semilir'}
                                        loading="lazy"
                                        className="aspect-[4/3] w-full rounded-xl object-cover"
                                    />
                                )}

                                {settings.event_description && (
                                    <p className="whitespace-pre-line text-sm leading-7 text-slate-600 sm:text-base sm:leading-8">
                                        {settings.event_description}
                                    </p>
                                )}
                            </div>
                        </section>
                    )}

                    {/* =================================================
                        OSIL SECTION
                    ================================================= */}

                    <section className="mt-16 sm:mt-20">
                        <div className="mx-auto mb-7 flex max-w-5xl flex-col items-center text-center">
                            <h2 className="text-2xl font-bold text-slate-900 sm:text-3xl">
                                Bawa Rombongan?
                            </h2>

                            {bookingUrl && (
                                <a
                                    href={bookingUrl}
                                    target="_blank"
                                    rel="noopener noreferrer"
                                    className="mt-4 inline-flex min-h-12 items-center justify-center rounded-full bg-emerald-600 px-7 py-3 text-sm font-bold text-white shadow-sm transition-colors hover:bg-emerald-700 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-700"
                                >
                                    Pesan Rombongan via WhatsApp
                                </a>
                            )}
                        </div>

                        {groupGallery.length > 0 && (
                            <div className="mx-auto grid max-w-5xl grid-cols-2 gap-2 sm:auto-rows-[190px] sm:grid-cols-3 sm:gap-3">
                                {groupGallery.map((photo) => (
                                    <img
                                        key={photo.slot}
                                        src={photo.image}
                                        alt={`Suasana rombongan di Dusun Semilir ${photo.slot}`}
                                        loading="lazy"
                                        className="aspect-[4/3] h-full w-full rounded-sm object-cover sm:aspect-auto"
                                    />
                                ))}
                            </div>
                        )}
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