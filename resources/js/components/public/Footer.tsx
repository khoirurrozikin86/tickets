interface FooterProps {
    settings: Record<string, string | null | undefined>;
}

/* =========================================================
   ICONS
========================================================= */

function WhatsAppIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5"
            fill="currentColor"
            aria-hidden="true"
        >
            <path d="M20.52 3.48A11.86 11.86 0 0012.06 0C5.5.0.17 5.33.17 11.89c0 2.09.55 4.13 1.59 5.93L.07 24l6.32-1.66a11.88 11.88 0 005.67 1.44h.01c6.56 0 11.89-5.33 11.89-11.89a11.84 11.84 0 00-3.44-8.41ZM12.06 21.84h-.01a9.89 9.89 0 01-5.04-1.38l-.36-.21-3.75.98 1-3.65-.23-.38a9.89 9.89 0 01-1.51-5.3c0-5.49 4.47-9.96 9.97-9.96a9.9 9.9 0 017.05 2.92 9.9 9.9 0 012.91 7.05c0 5.49-4.47 9.96-9.97 9.96Zm5.46-7.47c-.3-.15-1.78-.88-2.05-.98-.28-.1-.48-.15-.68.15-.2.3-.78.98-.96 1.18-.18.2-.35.23-.65.08-.3-.15-1.26-.46-2.4-1.48-.89-.79-1.49-1.76-1.67-2.06-.18-.3-.02-.46.13-.61.14-.14.3-.35.45-.53.15-.18.2-.3.3-.5.1-.2.05-.38-.03-.53-.08-.15-.68-1.64-.93-2.24-.24-.59-.49-.51-.68-.52-.18-.01-.38-.01-.58-.01-.2 0-.53.08-.81.38-.28.3-1.06 1.04-1.06 2.54s1.09 2.95 1.24 3.15c.15.2 2.14 3.27 5.19 4.58.73.32 1.3.51 1.74.65.73.23 1.39.2 1.91.12.58-.09 1.78-.73 2.03-1.44.25-.71.25-1.32.18-1.44-.08-.13-.28-.2-.58-.35Z" />
        </svg>
    );
}

function MailIcon() {
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
            <rect x="3" y="5" width="18" height="14" rx="2" />
            <path d="m3 7 9 6 9-6" />
        </svg>
    );
}

function MapPinIcon() {
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
            <path d="M20 10c0 5-8 12-8 12S4 15 4 10a8 8 0 1 1 16 0Z" />
            <circle cx="12" cy="10" r="2.5" />
        </svg>
    );
}

function FacebookIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5 fill-current"
            aria-hidden="true"
        >
            <path d="M13.5 21v-8h2.7l.4-3h-3.1V8.1c0-.9.3-1.6 1.6-1.6h1.7V3.8c-.3 0-.9-.1-2.2-.1-2.2 0-3.7 1.3-3.7 3.8V10H8.4v3h2.5v8h2.6Z" />
        </svg>
    );
}

function InstagramIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5"
            fill="none"
            stroke="currentColor"
            strokeWidth="1.8"
            aria-hidden="true"
        >
            <rect x="3" y="3" width="18" height="18" rx="5" />
            <circle cx="12" cy="12" r="4" />
            <circle
                cx="17.5"
                cy="6.5"
                r="1"
                fill="currentColor"
                stroke="none"
            />
        </svg>
    );
}

function TikTokIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5 fill-current"
            aria-hidden="true"
        >
            <path d="M16.5 3c.4 2.1 1.6 3.6 3.5 4.2v3.1c-1.4-.1-2.7-.6-3.7-1.3v6.4c0 4-2.5 6.4-6.1 6.4-3.1 0-5.7-2.2-5.7-5.3 0-3.4 2.8-5.6 6.2-5.6.4 0 .8 0 1.2.1v3.2c-.4-.1-.8-.2-1.2-.2-1.4 0-2.9.9-2.9 2.5 0 1.4 1.1 2.2 2.4 2.2 1.7 0 2.7-1.1 2.7-3.3V3h3.6Z" />
        </svg>
    );
}

function YoutubeIcon() {
    return (
        <svg
            viewBox="0 0 24 24"
            className="h-5 w-5"
            fill="none"
            xmlns="http://www.w3.org/2000/svg"
            aria-hidden="true"
        >
            <path
                d="M21.58 7.19C21.35 6.33 20.67 5.65 19.81 5.42C18.25 5 12 5 12 5S5.75 5 4.19 5.42C3.33 5.65 2.65 6.33 2.42 7.19C2 8.75 2 12 2 12S2 15.25 2.42 16.81C2.65 17.67 3.33 18.35 4.19 18.58C5.75 19 12 19 12 19S18.25 19 19.81 18.58C20.67 18.35 21.35 17.67 21.58 16.81C22 15.25 22 12 22 12S22 8.75 21.58 7.19Z"
                fill="white"
            />

            <path
                d="M10 8.5L16 12L10 15.5V8.5Z"
                fill="#FF0000"
            />
        </svg>
    );
}

/* =========================================================
   SOCIAL BUTTON
========================================================= */

interface SocialItem {
    name: string;
    url: string;
    icon: React.ReactNode;
    className: string;
}

function SocialButton({ social }: { social: SocialItem }) {
    const isYoutube = social.name === 'YouTube';

    return (
        <a
            href={social.url}
            target="_blank"
            rel="noopener noreferrer"
            aria-label={social.name}
            title={social.name}
            className={`
                flex
                shrink-0
                items-center
                justify-center
                text-white
                shadow-sm
                transition-all
                duration-300
                hover:-translate-y-1
                hover:shadow-lg
                ${isYoutube
                    ? 'h-10 w-12 rounded-[11px]'
                    : 'h-10 w-10 rounded-full'
                }
                ${social.className}
            `}
        >
            {social.icon}
        </a>
    );
}

/* =========================================================
   FOOTER
========================================================= */

export default function Footer({
    settings,
}: FooterProps) {
    const phoneNumber = settings.phone
        ? settings.phone.replace(/\D/g, '')
        : '';

    const socialLinks: SocialItem[] = [
        {
            name: 'Instagram',
            url: settings.instagram ?? '',
            icon: <InstagramIcon />,
            className:
                'bg-gradient-to-br from-purple-500 via-pink-500 to-orange-400',
        },
        {
            name: 'Facebook',
            url: settings.facebook ?? '',
            icon: <FacebookIcon />,
            className: 'bg-[#1877F2]',
        },
        {
            name: 'TikTok',
            url: settings.tiktok ?? '',
            icon: <TikTokIcon />,
            className: 'bg-black',
        },
        {
            name: 'YouTube',
            url: settings.youtube ?? '',
            icon: <YoutubeIcon />,
            className: 'bg-[#FF0000]',
        },
    ].filter(
        (item): item is SocialItem => Boolean(item.url),
    );

    return (
        <footer className="relative overflow-hidden">
            {/* =====================================================
                MAIN FOOTER
            ===================================================== */}

            <div className="relative overflow-hidden bg-gradient-to-br from-white via-emerald-50/80 to-emerald-100/70">
                {/* Soft background decoration */}

                <div
                    className="
                        pointer-events-none
                        absolute
                        -left-40
                        -top-40
                        h-80
                        w-80
                        rounded-full
                        bg-emerald-200/25
                        blur-3xl
                    "
                />

                <div
                    className="
                        pointer-events-none
                        absolute
                        -right-32
                        -bottom-40
                        h-96
                        w-96
                        rounded-full
                        bg-lime-200/20
                        blur-3xl
                    "
                />

                {/* =================================================
                    CONTENT
                ================================================= */}

                <div className="relative mx-auto max-w-7xl px-6 py-12 sm:py-14 lg:px-8">
                    <div
                        className="
                            grid
                            gap-10
                            md:grid-cols-2
                            lg:grid-cols-[1.25fr_1fr_1.25fr_0.8fr]
                            lg:gap-12
                        "
                    >
                        {/* =================================================
                            BRAND
                        ================================================= */}

                        <div>
                            <h3 className="text-2xl font-extrabold tracking-tight text-emerald-950">
                                {settings.site_name || 'Dusun Semilir'}
                            </h3>

                            <p className="mt-1 text-sm font-semibold text-emerald-700">
                                Wisata Keluarga, Cerita Tak Terlupa
                            </p>

                            <p className="mt-5 max-w-sm text-sm leading-6 text-slate-600">
                                Nikmati pengalaman wisata yang seru,
                                nyaman, dan menyenangkan bersama keluarga.
                            </p>

                            <div className="mt-5 inline-flex items-center rounded-full border border-emerald-100 bg-white/70 px-4 py-2.5 text-xs font-semibold text-emerald-800 shadow-sm backdrop-blur-sm">
                                Liburan lebih seru bersama Dusun Semilir
                            </div>
                        </div>

                        {/* =================================================
                            CUSTOMER SERVICE
                        ================================================= */}

                        <div>
                            <h4 className="text-sm font-bold text-emerald-950">
                                Layanan Pelanggan
                            </h4>

                            <div className="mt-5 space-y-4">
                                {settings.phone && (
                                    <a
                                        href={`https://wa.me/${phoneNumber}`}
                                        target="_blank"
                                        rel="noopener noreferrer"
                                        className="group flex items-center gap-3"
                                    >
                                        <span
                                            className="
                                                flex h-10 w-10 shrink-0
                                                items-center justify-center
                                                rounded-full
                                                bg-emerald-500
                                                text-white
                                                shadow-sm
                                                transition-all duration-300
                                                group-hover:-translate-y-0.5
                                                group-hover:bg-emerald-600
                                            "
                                        >
                                            <WhatsAppIcon />
                                        </span>

                                        <span className="min-w-0">
                                            <span className="block text-sm font-semibold text-slate-800">
                                                WhatsApp
                                            </span>

                                            <span className="mt-0.5 block truncate text-xs text-slate-500">
                                                {settings.phone}
                                            </span>
                                        </span>
                                    </a>
                                )}

                                {settings.email && (
                                    <a
                                        href={`mailto:${settings.email}`}
                                        className="group flex items-center gap-3"
                                    >
                                        <span
                                            className="
                                                flex h-10 w-10 shrink-0
                                                items-center justify-center
                                                rounded-full
                                                bg-emerald-500
                                                text-white
                                                shadow-sm
                                                transition-all duration-300
                                                group-hover:-translate-y-0.5
                                                group-hover:bg-emerald-600
                                            "
                                        >
                                            <MailIcon />
                                        </span>

                                        <span className="min-w-0">
                                            <span className="block text-sm font-semibold text-slate-800">
                                                Email
                                            </span>

                                            <span className="mt-0.5 block truncate text-xs text-slate-500">
                                                {settings.email}
                                            </span>
                                        </span>
                                    </a>
                                )}
                            </div>
                        </div>

                        {/* =================================================
                            LOCATION
                        ================================================= */}

                        <div>
                            <h4 className="text-sm font-bold text-emerald-950">
                                Temukan Kami
                            </h4>

                            {settings.address && (
                                <div className="mt-5 flex items-start gap-3">
                                    <span
                                        className="
                                            flex h-10 w-10 shrink-0
                                            items-center justify-center
                                            rounded-full
                                            bg-emerald-500
                                            text-white
                                            shadow-sm
                                        "
                                    >
                                        <MapPinIcon />
                                    </span>

                                    <p className="max-w-sm text-sm leading-6 text-slate-600">
                                        {settings.address}
                                    </p>
                                </div>
                            )}
                        </div>

                        {/* =================================================
                            SOCIAL
                        ================================================= */}

                        <div>
                            <h4 className="text-sm font-bold text-emerald-950">
                                Follow Us
                            </h4>

                            <p className="mt-2 text-xs leading-5 text-slate-500">
                                Ikuti aktivitas dan informasi terbaru kami.
                            </p>

                            {socialLinks.length > 0 && (
                                <div className="mt-5 flex items-center gap-3">
                                    {socialLinks.map((social) => (
                                        <SocialButton
                                            key={social.name}
                                            social={social}
                                        />
                                    ))}
                                </div>
                            )}
                        </div>
                    </div>
                </div>

                {/* =================================================
                    SUBTLE GREEN LINE
                ================================================= */}

                <div className="h-1 bg-gradient-to-r from-emerald-300/60 via-emerald-500 to-emerald-300/60" />
            </div>

            {/* =====================================================
                COPYRIGHT
            ===================================================== */}

            <div className="bg-emerald-950">
                <div
                    className="
                        mx-auto
                        flex
                        max-w-7xl
                        flex-col
                        items-center
                        justify-between
                        gap-2
                        px-6
                        py-4
                        text-center
                        sm:flex-row
                        sm:text-left
                        lg:px-8
                    "
                >
                    <p className="text-xs text-white/60">
                        {settings.copyright ||
                            '© 2026 Dusun Semilir. All rights reserved.'}
                    </p>

                    <p className="text-xs text-white/60">
                        Dibuat dengan{' '}
                        <span className="text-emerald-300">♥</span>{' '}
                        untuk pengalaman liburan yang lebih baik.
                    </p>
                </div>
            </div>
        </footer>
    );
}