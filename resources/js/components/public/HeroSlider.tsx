import { Link } from '@inertiajs/react';
import type { MouseEvent } from 'react';
import { useEffect, useRef, useState } from 'react';

export interface Banner {
    id: number;
    title: string;
    subtitle?: string | null;
    image: string;
    button_text?: string | null;
    button_url?: string | null;
    sort_order?: number;
}

interface HeroSliderProps {
    banners: Banner[];
}

/*
|--------------------------------------------------------------------------
| Configuration
|--------------------------------------------------------------------------
*/

const SLIDE_DURATION = 6500;
const TRANSITION_DURATION = 450;

/*
|--------------------------------------------------------------------------
| Hero Slider
|--------------------------------------------------------------------------
*/

export default function HeroSlider({
    banners,
}: HeroSliderProps) {
    const [current, setCurrent] = useState(0);
    const [isChanging, setIsChanging] = useState(false);

    const timerRef = useRef<ReturnType<typeof setTimeout> | null>(
        null,
    );

    /*
    |--------------------------------------------------------------------------
    | Cleanup Timer
    |--------------------------------------------------------------------------
    */

    const clearTimer = () => {
        if (timerRef.current) {
            clearTimeout(timerRef.current);
            timerRef.current = null;
        }
    };

    /*
    |--------------------------------------------------------------------------
    | Change Slide
    |--------------------------------------------------------------------------
    */

    const changeSlide = (index: number) => {
        if (
            banners.length <= 1 ||
            index === current ||
            index < 0 ||
            index >= banners.length ||
            isChanging
        ) {
            return;
        }

        clearTimer();

        setIsChanging(true);

        timerRef.current = setTimeout(() => {
            setCurrent(index);
            setIsChanging(false);
            timerRef.current = null;
        }, TRANSITION_DURATION);
    };

    /*
    |--------------------------------------------------------------------------
    | Auto Slide
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        if (banners.length <= 1) {
            return;
        }

        const interval = setInterval(() => {
            setIsChanging(true);

            timerRef.current = setTimeout(() => {
                setCurrent((previous) => {
                    return (previous + 1) % banners.length;
                });

                setIsChanging(false);
                timerRef.current = null;
            }, TRANSITION_DURATION);
        }, SLIDE_DURATION);

        return () => {
            clearInterval(interval);
            clearTimer();
        };
    }, [banners.length]);

    /*
    |--------------------------------------------------------------------------
    | Preload Next Image
    |--------------------------------------------------------------------------
    |
    | Hanya gambar berikutnya yang dipersiapkan.
    | Tidak semua banner langsung dimuat sekaligus.
    |
    */

    useEffect(() => {
        if (banners.length <= 1) {
            return;
        }

        const nextIndex = (current + 1) % banners.length;
        const nextImage = banners[nextIndex]?.image;

        if (!nextImage) {
            return;
        }

        const image = new Image();
        image.src = nextImage;
    }, [current, banners]);

    /*
    |--------------------------------------------------------------------------
    | Empty Banner
    |--------------------------------------------------------------------------
    */

    if (!banners.length) {
        return (
            <section
                className="
                    relative
                    min-h-[540px]
                    overflow-hidden
                    bg-emerald-950
                    sm:min-h-[600px]
                    lg:min-h-[650px]
                "
            >
                <div
                    className="
                        relative
                        flex
                        min-h-[540px]
                        items-center
                        justify-center
                        px-6
                        text-center
                        sm:min-h-[600px]
                        lg:min-h-[650px]
                    "
                >
                    <div className="max-w-2xl">
                        <div
                            className="
                                inline-flex
                                items-center
                                gap-2
                                text-xs
                                font-semibold
                                uppercase
                                tracking-[0.16em]
                                text-emerald-300
                            "
                        >
                            <span
                                className="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    bg-emerald-300
                                "
                            />

                            Dusun Semilir
                        </div>

                        <h1
                            className="
                                mt-5
                                text-4xl
                                font-black
                                leading-tight
                                tracking-tight
                                text-white
                                sm:text-5xl
                            "
                        >
                            Nikmati Pengalaman

                            <span className="block text-emerald-300">
                                yang Tak Terlupakan
                            </span>
                        </h1>

                        <div
                            className="
                                mx-auto
                                mt-6
                                h-1
                                w-14
                                rounded-full
                                bg-emerald-400
                            "
                        />
                    </div>
                </div>

                <HeroBottomWave />
            </section>
        );
    }

    const banner = banners[current];

    /*
    |--------------------------------------------------------------------------
    | Same Page Hash Navigation
    |--------------------------------------------------------------------------
    */

    const handleBannerClick = (
        event: MouseEvent<HTMLAnchorElement>,
    ) => {
        const url = banner.button_url;

        if (!url || !url.includes('#')) {
            return;
        }

        const [path, hash] = url.split('#');

        const currentPath = window.location.pathname;

        const isSamePage =
            !path ||
            path === '/' ||
            path === currentPath;

        if (!isSamePage || !hash) {
            return;
        }

        const target = document.getElementById(hash);

        if (!target) {
            return;
        }

        event.preventDefault();

        target.scrollIntoView({
            behavior: 'smooth',
            block: 'start',
        });

        window.history.pushState(
            null,
            '',
            `#${hash}`,
        );
    };

    return (
        <section
            className="
                relative
                min-h-[540px]
                overflow-hidden
                bg-emerald-950
                sm:min-h-[600px]
                lg:min-h-[650px]
            "
        >
            {/* =====================================================
                ACTIVE IMAGE
            ===================================================== */}

            <div className="absolute inset-0">
                <img
                    key={banner.id}
                    src={banner.image}
                    alt={banner.title}
                    fetchPriority={
                        current === 0 ? 'high' : 'auto'
                    }
                    loading={
                        current === 0 ? 'eager' : 'lazy'
                    }
                    decoding="async"
                    className={`
                        h-full
                        w-full
                        object-cover
                        transition-opacity
                        duration-[450ms]
                        ease-out
                        ${isChanging
                            ? 'opacity-0'
                            : 'opacity-100'
                        }
                    `}
                />
            </div>

            {/* =====================================================
                DARK OVERLAY
            ===================================================== */}

            <div
                className="
                    pointer-events-none
                    absolute
                    inset-0
                    bg-gradient-to-r
                    from-emerald-950/70
                    via-emerald-950/30
                    to-transparent
                "
            />

            {/* =====================================================
                BOTTOM OVERLAY
            ===================================================== */}

            <div
                className="
                    pointer-events-none
                    absolute
                    inset-x-0
                    bottom-0
                    h-52
                    bg-gradient-to-t
                    from-emerald-950/75
                    to-transparent
                "
            />

            {/* =====================================================
                HERO CONTENT
            ===================================================== */}

            <div
                className="
                    relative
                    z-10
                    flex
                    min-h-[540px]
                    items-center
                    sm:min-h-[600px]
                    lg:min-h-[650px]
                "
            >
                <div
                    className="
                        mx-auto
                        w-full
                        max-w-7xl
                        px-6
                        sm:px-10
                        lg:px-16
                    "
                >
                    <div
                        key={banner.id}
                        className={`
                            max-w-xl
                            transition-all
                            duration-[450ms]
                            ease-out
                            ${isChanging
                                ? 'translate-y-2 opacity-0'
                                : 'translate-y-0 opacity-100'
                            }
                        `}
                    >
                        {/* =================================================
                            LABEL
                        ================================================= */}

                        <div
                            className="
                                mb-5
                                flex
                                items-center
                                gap-2.5
                                text-xs
                                font-semibold
                                tracking-[0.12em]
                                text-white/85
                            "
                        >
                            <span
                                className="
                                    h-1.5
                                    w-1.5
                                    rounded-full
                                    bg-emerald-300
                                "
                            />

                            Dusun Semilir
                        </div>

                        {/* =================================================
                            TITLE
                        ================================================= */}

                        <h1
                            className="
                                max-w-2xl
                                text-[2.5rem]
                                font-black
                                leading-[1.05]
                                tracking-tight
                                text-white
                                sm:text-5xl
                                lg:text-[3.5rem]
                            "
                        >
                            {banner.title}
                        </h1>

                        {/* =================================================
                            ACCENT
                        ================================================= */}

                        <div
                            className="
                                mt-5
                                flex
                                items-center
                                gap-2
                            "
                        >
                            <span
                                className="
                                    h-[3px]
                                    w-11
                                    rounded-full
                                    bg-emerald-400
                                "
                            />

                            <span
                                className="
                                    h-[3px]
                                    w-3
                                    rounded-full
                                    bg-emerald-200/80
                                "
                            />
                        </div>

                        {/* =================================================
                            SUBTITLE
                        ================================================= */}

                        {banner.subtitle && (
                            <p
                                className="
                                    mt-5
                                    max-w-md
                                    text-sm
                                    leading-6
                                    text-white/80
                                    sm:text-[15px]
                                    sm:leading-7
                                "
                            >
                                {banner.subtitle}
                            </p>
                        )}

                        {/* =================================================
                            BUTTON
                        ================================================= */}

                        {banner.button_text &&
                            banner.button_url && (
                                <div className="mt-7">
                                    <Link
                                        href={banner.button_url}
                                        onClick={
                                            handleBannerClick
                                        }
                                        className="
                                            inline-flex
                                            items-center
                                            gap-3
                                            rounded-full
                                            bg-emerald-500
                                            px-5
                                            py-2.5
                                            text-sm
                                            font-bold
                                            text-white
                                            shadow-lg
                                            transition
                                            duration-200
                                            hover:bg-emerald-400
                                        "
                                    >
                                        <span>
                                            {
                                                banner.button_text
                                            }
                                        </span>

                                        <span
                                            className="
                                                flex
                                                h-6
                                                w-6
                                                items-center
                                                justify-center
                                                rounded-full
                                                bg-white/15
                                            "
                                        >
                                            <svg
                                                viewBox="0 0 24 24"
                                                className="h-3.5 w-3.5"
                                                fill="none"
                                                stroke="currentColor"
                                                strokeWidth="2"
                                                strokeLinecap="round"
                                                strokeLinejoin="round"
                                                aria-hidden="true"
                                            >
                                                <path d="M5 12h14" />
                                                <path d="m13 6 6 6-6 6" />
                                            </svg>
                                        </span>
                                    </Link>
                                </div>
                            )}
                    </div>
                </div>
            </div>

            {/* =====================================================
                SLIDER INDICATOR
            ===================================================== */}

            {banners.length > 1 && (
                <div
                    className="
                        absolute
                        bottom-14
                        left-1/2
                        z-30
                        flex
                        -translate-x-1/2
                        items-center
                        gap-2
                    "
                >
                    {banners.map((item, index) => (
                        <button
                            key={item.id}
                            type="button"
                            onClick={() => changeSlide(index)}
                            disabled={isChanging}
                            aria-label={`Slide ${index + 1}`}
                            aria-current={
                                index === current
                                    ? 'true'
                                    : undefined
                            }
                            className={`
                                h-1.5
                                rounded-full
                                transition-all
                                duration-300
                                disabled:cursor-default
                                ${index === current
                                    ? 'w-10 bg-white/80'
                                    : 'w-3 bg-white/35'
                                }
                            `}
                        />
                    ))}
                </div>
            )}

            {/* =====================================================
                BOTTOM WAVE
            ===================================================== */}

            <HeroBottomWave />
        </section>
    );
}

/*
|--------------------------------------------------------------------------
| Bottom Wave
|--------------------------------------------------------------------------
*/

function HeroBottomWave() {
    return (
        <div
            className="
                pointer-events-none
                absolute
                inset-x-0
                bottom-0
                z-20
                h-28
                overflow-hidden
                sm:h-32
            "
        >
            <svg
                className="
                    absolute
                    bottom-0
                    left-0
                    h-full
                    w-full
                "
                viewBox="0 0 1440 180"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                    d="
                        M0 105
                        C180 155 330 160 510 120
                        C690 80 800 70 970 110
                        C1150 150 1280 160 1440 100
                        L1440 180
                        L0 180
                        Z
                    "
                    fill="rgba(2,68,57,0.92)"
                />

                <path
                    d="
                        M0 130
                        C200 165 360 170 560 135
                        C750 100 880 100 1050 135
                        C1220 170 1320 165 1440 130
                        L1440 180
                        L0 180
                        Z
                    "
                    fill="rgb(236,253,245)"
                />
            </svg>
        </div>
    );
}