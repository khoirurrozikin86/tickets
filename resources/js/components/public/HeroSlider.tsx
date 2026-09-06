import { Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';

export interface Banner {
    id: number;
    title: string | null;
    subtitle: string | null;
    image: string | null;
    button_text: string | null;
    button_url: string | null;
    sort_order: number;
}

interface HeroSliderProps {
    banners: Banner[];
}

export default function HeroSlider({
    banners,
}: HeroSliderProps) {

    const [current, setCurrent] = useState(0);


    useEffect(() => {

        if (banners.length <= 1) {
            return;
        }

        const interval = setInterval(() => {

            setCurrent((prev) =>
                prev === banners.length - 1
                    ? 0
                    : prev + 1
            );

        }, 5000);

        return () => clearInterval(interval);

    }, [banners.length]);


    if (!banners.length) {
        return (
            <section className="relative flex min-h-[620px] items-center justify-center overflow-hidden bg-emerald-950">
                <div className="text-center text-white">
                    <h1 className="text-4xl font-bold">
                        Dusun Semilir
                    </h1>

                    <p className="mt-3 text-white/70">
                        Selamat datang di Dusun Semilir
                    </p>
                </div>
            </section>
        );
    }


    const banner = banners[current];


    return (
        <section className="relative min-h-[680px] overflow-hidden bg-emerald-950">

            {/* Background */}
            {banner.image && (
                <img
                    src={banner.image}
                    alt={banner.title || 'Banner'}
                    className="absolute inset-0 h-full w-full object-cover transition-opacity duration-700"
                />
            )}


            {/* Overlay */}
            <div className="absolute inset-0 bg-gradient-to-r from-emerald-950/85 via-emerald-900/50 to-emerald-900/10" />


            {/* Content */}
            <div className="relative mx-auto flex min-h-[680px] max-w-7xl items-center px-6 pt-20 lg:px-8">

                <div className="max-w-2xl text-white">

                    {banner.title && (
                        <h1 className="text-4xl font-black leading-tight tracking-tight sm:text-5xl lg:text-6xl">
                            {banner.title}
                        </h1>
                    )}


                    {banner.subtitle && (
                        <p className="mt-5 max-w-xl text-base leading-7 text-white/80 sm:text-lg">
                            {banner.subtitle}
                        </p>
                    )}


                    {banner.button_text && banner.button_url && (
                        <div className="mt-8">

                            <Link
                                href={banner.button_url}
                                className="inline-flex items-center rounded-2xl bg-orange-500 px-6 py-3.5 text-sm font-bold text-white shadow-xl shadow-orange-900/20 transition hover:-translate-y-1 hover:bg-orange-600"
                            >
                                {banner.button_text}

                                <span className="ml-2">
                                    →
                                </span>
                            </Link>

                        </div>
                    )}

                </div>

            </div>


            {/* Dots */}
            {banners.length > 1 && (
                <div className="absolute bottom-8 left-1/2 flex -translate-x-1/2 gap-2">

                    {banners.map((item, index) => (

                        <button
                            key={item.id}
                            type="button"
                            onClick={() => setCurrent(index)}
                            className={`h-2 rounded-full transition-all ${current === index
                                ? 'w-8 bg-white'
                                : 'w-2 bg-white/50'
                                }`}
                            aria-label={`Banner ${index + 1}`}
                        />

                    ))}

                </div>
            )}

        </section>
    );
}