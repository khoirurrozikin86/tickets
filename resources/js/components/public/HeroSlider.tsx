import { Link } from '@inertiajs/react';
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

const SLIDE_DURATION = 6500;
const TRANSITION_DURATION = 500;

export default function HeroSlider({ banners }: HeroSliderProps) {
    const [current, setCurrent] = useState(0);
    const [isChanging, setIsChanging] = useState(false);

    const transitionTimer = useRef<ReturnType<typeof setTimeout> | null>(
        null,
    );

    /*
    |--------------------------------------------------------------------------
    | Change Slide
    |--------------------------------------------------------------------------
    */

    const changeSlide = (index: number) => {
        if (
            index === current ||
            isChanging ||
            index < 0 ||
            index >= banners.length
        ) {
            return;
        }

        setIsChanging(true);

        if (transitionTimer.current) {
            clearTimeout(transitionTimer.current);
        }

        transitionTimer.current = setTimeout(() => {
            setCurrent(index);
            setIsChanging(false);
        }, TRANSITION_DURATION);
    };

    /*
    |--------------------------------------------------------------------------
    | Auto Slider
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        if (banners.length <= 1) {
            return;
        }

        const interval = setInterval(() => {
            setIsChanging(true);

            transitionTimer.current = setTimeout(() => {
                setCurrent((previous) => {
                    return (previous + 1) % banners.length;
                });

                setIsChanging(false);
            }, TRANSITION_DURATION);
        }, SLIDE_DURATION);

        return () => {
            clearInterval(interval);

            if (transitionTimer.current) {
                clearTimeout(transitionTimer.current);
            }
        };
    }, [banners.length]);

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
                    isolate
                    min-h-[560px]
                    overflow-hidden
                    bg-gradient-to-b
                    from-emerald-900
                    via-emerald-950
                    to-emerald-900
                    sm:min-h-[620px]
                "
            >
                {/* Soft Background */}

                <div
                    className="
                        pointer-events-none
                        absolute
                        -left-40
                        top-1/4
                        h-96
                        w-96
                        rounded-full
                        bg-emerald-400/20
                        blur-[110px]
                    "
                />

                <div
                    className="
                        pointer-events-none
                        absolute
                        -right-40
                        bottom-20
                        h-96
                        w-96
                        rounded-full
                        bg-lime-300/10
                        blur-[110px]
                    "
                />

                {/* Content */}

                <div
                    className="
                        relative
                        z-10
                        flex
                        min-h-[560px]
                        items-center
                        justify-center
                        px-6
                        text-center
                        sm:min-h-[620px]
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
                                tracking-[0.18em]
                                text-emerald-300
                            "
                        >
                            <span className="h-1.5 w-1.5 rounded-full bg-emerald-300" />

                            Dusun Semilir
                        </div>

                        <h1
                            className="
                                mt-6
                                text-4xl
                                font-black
                                leading-[1.05]
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
                                w-16
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

    return (
        <section
            className="
                relative
                isolate
                min-h-[570px]
                overflow-hidden
                bg-emerald-950
                sm:min-h-[630px]
                lg:min-h-[680px]
            "
        >
            {/* =====================================================
                SLIDES
            ===================================================== */}

            <div className="absolute inset-0">
                {banners.map((item, index) => (
                    <div
                        key={item.id}
                        className={`
                            absolute
                            inset-0
                            overflow-hidden
                            transition-all
                            duration-[1200ms]
                            ease-out
                            ${index === current
                                ? 'scale-100 opacity-100'
                                : 'scale-[1.035] opacity-0'
                            }
                        `}
                    >
                        <img
                            src={item.image}
                            alt={item.title}
                            className={`
                                h-full
                                w-full
                                object-cover
                                transition-transform
                                duration-[7500ms]
                                ease-out
                                ${index === current
                                    ? 'scale-[1.035]'
                                    : 'scale-100'
                                }
                            `}
                        />
                    </div>
                ))}
            </div>

            {/* =====================================================
                GREEN IMAGE OVERLAY
            ===================================================== */}

            {/* Left readability */}

            <div
                className="
                    absolute
                    inset-0
                    bg-gradient-to-r
                    from-emerald-950/65
                    via-emerald-950/30
                    to-transparent
                "
            />

            {/* Bottom green atmosphere */}

            <div
                className="
                    absolute
                    inset-x-0
                    bottom-0
                    h-64
                    bg-gradient-to-t
                    from-emerald-950/80
                    via-emerald-950/30
                    to-transparent
                "
            />

            {/* Very subtle green tint */}

            <div
                className="
                    pointer-events-none
                    absolute
                    inset-0
                    bg-emerald-700/[0.045]
                "
            />

            {/* =====================================================
                SOFT LIGHT
            ===================================================== */}

            <div
                className="
                    pointer-events-none
                    absolute
                    -left-40
                    top-1/3
                    h-96
                    w-96
                    rounded-full
                    bg-emerald-400/10
                    blur-[120px]
                "
            />

            <div
                className="
                    pointer-events-none
                    absolute
                    -right-40
                    bottom-20
                    h-96
                    w-96
                    rounded-full
                    bg-lime-300/10
                    blur-[120px]
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
                    min-h-[570px]
                    items-center
                    sm:min-h-[630px]
                    lg:min-h-[680px]
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
                            duration-700
                            ease-[cubic-bezier(.22,1,.36,1)]
                            ${isChanging
                                ? 'translate-y-5 opacity-0 blur-[3px]'
                                : 'translate-y-0 opacity-100 blur-0'
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
                                    shadow-[0_0_12px_rgba(110,231,183,.8)]
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
                                text-[2.7rem]
                                font-black
                                leading-[1.04]
                                tracking-[-0.025em]
                                text-white
                                drop-shadow-[0_4px_20px_rgba(0,0,0,.25)]
                                sm:text-5xl
                                lg:text-[3.6rem]
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
                                    w-12
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
                                    drop-shadow-md
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

                        {banner.button_text && banner.button_url && (
                            <div className="mt-7">
                                <Link
                                    href={banner.button_url}
                                    className="
                                        group/button
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
                                        shadow-[0_8px_28px_rgba(6,78,59,.30)]
                                        transition-all
                                        duration-300
                                        hover:-translate-y-0.5
                                        hover:bg-emerald-400
                                    "
                                >
                                    <span>
                                        {banner.button_text}
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
                                            transition-transform
                                            duration-300
                                            group-hover/button:translate-x-1
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
                                index === current ? 'true' : undefined
                            }
                            className={`
                                relative
                                h-1.5
                                overflow-hidden
                                rounded-full
                                transition-all
                                duration-500
                                disabled:cursor-default
                                ${index === current
                                    ? 'w-12 bg-white/80'
                                    : 'w-4 bg-white/35 hover:bg-white/60'
                                }
                            `}
                        >
                            {index === current && (
                                <span
                                    key={current}
                                    className="
                                        absolute
                                        inset-y-0
                                        left-0
                                        w-full
                                        origin-left
                                        rounded-full
                                        bg-emerald-400
                                        animate-[progress_6.5s_linear_forwards]
                                    "
                                />
                            )}
                        </button>
                    ))}
                </div>
            )}

            {/* =====================================================
                GREEN BOTTOM WAVE
            ===================================================== */}

            <HeroBottomWave />

            {/* =====================================================
                ANIMATION
            ===================================================== */}

            <style>{`
                @keyframes progress {
                    from {
                        transform: scaleX(0);
                    }

                    to {
                        transform: scaleX(1);
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
    );
}

/*
|--------------------------------------------------------------------------
| Bottom Wave
|--------------------------------------------------------------------------
|
| Tidak ada garis lurus tambahan.
| Hanya tiga layer lengkungan hijau.
|
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
                h-36
                overflow-hidden
                sm:h-40
            "
        >
            {/* =====================================================
                LAYER 1 — DARK EMERALD
            ===================================================== */}

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
                    fill="rgba(2,68,57,0.94)"
                />
            </svg>

            {/* =====================================================
                LAYER 2 — EMERALD
            ===================================================== */}

            <svg
                className="
                    absolute
                    bottom-0
                    left-0
                    h-[88%]
                    w-full
                "
                viewBox="0 0 1440 180"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
                <path
                    d="
                        M0 125
                        C180 165 350 155 530 115
                        C720 72 860 85 1020 125
                        C1190 165 1300 150 1440 115
                        L1440 180
                        L0 180
                        Z
                    "
                    fill="rgba(5,150,105,0.78)"
                />
            </svg>

            {/* =====================================================
                LAYER 3 — FINAL MINT
            ===================================================== */}

            <svg
                className="
                    absolute
                    bottom-0
                    left-0
                    h-[55%]
                    w-full
                "
                viewBox="0 0 1440 180"
                preserveAspectRatio="none"
                aria-hidden="true"
            >
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