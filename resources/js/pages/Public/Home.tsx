import { Head, Link } from '@inertiajs/react';
import { useEffect, useState } from 'react';

const slides = [
    {
        image: '/images/hero-slider-1.jpg',
        eyebrow: 'LIBURAN SERU PENUH CERITA',
        title: 'Dusun Semilir',
        description:
            'Wisata alam, wahana, kuliner, dan pengalaman seru untuk seluruh keluarga.',
        button: 'Beli Tiket Sekarang',
    },
    {
        image: '/images/hero-slider-2.jpg',
        eyebrow: 'JELAJAHI BERBAGAI WAHANA',
        title: 'Nikmati Setiap Momennya',
        description:
            'Dari wahana bermain, spot foto instagramable, hingga kuliner khas dalam satu destinasi.',
        button: 'Lihat Pilihan Tiket',
    },
    {
        image: '/images/hero-slider-3.jpg',
        eyebrow: 'PESAN TIKET LEBIH MUDAH',
        title: 'Semua Jadi Lebih Praktis',
        description:
            'Pilih tanggal kunjungan, lakukan pembayaran, dan dapatkan e-ticket QR Code langsung di tangan.',
        button: 'Pesan Sekarang',
    },
];

const tickets = [
    {
        name: 'Tiket Combo',
        category: 'COMBO',
        badge: 'HEMAT',
        description: 'Kombinasi wahana pilihan dengan harga lebih hemat.',
        image: '/images/ticket-combo.jpg',
        price: 'Rp 150.000',
    },
    {
        name: 'Tiket Reguler',
        category: 'REGULER',
        badge: 'POPULER',
        description: 'Jelajahi keindahan Dusun Semilir dengan tiket reguler.',
        image: '/images/ticket-reguler.jpg',
        price: 'Rp 30.000',
    },
    {
        name: 'Tiket Terusan',
        category: 'TERUSAN',
        badge: '',
        description: 'Akses lebih banyak wahana dengan satu tiket.',
        image: '/images/ticket-terusan.jpg',
        price: 'Rp 120.000',
    },
];

function SocialIcon({ children }: { children: React.ReactNode }) {
    return (
        <span className="flex h-11 w-11 items-center justify-center rounded-full bg-white text-lg font-black text-slate-800 shadow-sm transition duration-300 hover:-translate-y-1 hover:scale-105">
            {children}
        </span>
    );
}

export default function Home() {
    const [activeSlide, setActiveSlide] = useState(0);

    useEffect(() => {
        const timer = window.setInterval(() => {
            setActiveSlide((current) => (current + 1) % slides.length);
        }, 5500);

        return () => window.clearInterval(timer);
    }, []);

    const previousSlide = () => {
        setActiveSlide((current) => (current - 1 + slides.length) % slides.length);
    };

    const nextSlide = () => {
        setActiveSlide((current) => (current + 1) % slides.length);
    };

    return (
        <>
            <Head title="Dusun Semilir - Ticket Online" />

            <style>{`
                @keyframes ds-fade-up {
                    from { opacity: 0; transform: translateY(22px); }
                    to { opacity: 1; transform: translateY(0); }
                }
                @keyframes ds-float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-10px); }
                }
                @keyframes ds-pulse {
                    0%, 100% { transform: scale(1); opacity: .45; }
                    50% { transform: scale(1.12); opacity: .75; }
                }
                @keyframes ds-shine {
                    0% { transform: translateX(-140%) skewX(-18deg); }
                    100% { transform: translateX(360%) skewX(-18deg); }
                }
                .ds-fade-up { animation: ds-fade-up .7s cubic-bezier(.22,1,.36,1) both; }
                .ds-float { animation: ds-float 5s ease-in-out infinite; }
                .ds-pulse { animation: ds-pulse 4s ease-in-out infinite; }
                .ds-ticket:hover .ds-shine { animation: ds-shine .8s ease; }
                .ds-shine {
                    position: absolute;
                    inset: 0 auto 0 -60%;
                    width: 38%;
                    background: linear-gradient(90deg, transparent, rgba(255,255,255,.55), transparent);
                    pointer-events: none;
                    z-index: 5;
                }
            `}</style>

            <div className="min-h-screen overflow-x-hidden bg-[#f3fbf6] text-slate-800">
                <div className="hidden bg-[#006b4f] text-white sm:block">
                    <div className="mx-auto flex h-9 max-w-6xl items-center justify-between px-5 text-[11px] font-semibold">
                        <span>📍 Wisata Alam Terbaik di Jawa Tengah</span>
                        <div className="flex items-center gap-5">
                            <span>📍 Bawen, Semarang</span>
                            <span>◷ 08.00 - 17.00 WIB</span>
                            <span>◎ ID</span>
                        </div>
                    </div>
                </div>

                <header className="sticky top-0 z-50 px-3 pt-3 sm:px-5">
                    <nav className="mx-auto flex h-[66px] max-w-6xl items-center justify-between rounded-2xl border border-white/80 bg-white/90 px-4 shadow-[0_12px_40px_rgba(0,80,55,.12)] backdrop-blur-xl sm:px-6">
                        <Link href="/" className="group flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e9f7ef] p-1.5 ring-1 ring-emerald-100 transition duration-300 group-hover:rotate-2 group-hover:scale-105">
                                <img src="/images/semilir_logo.png" alt="Dusun Semilir" className="h-full w-full object-contain" />
                            </div>
                            <div>
                                <div className="text-sm font-black tracking-tight text-[#075b49]">Dusun Semilir</div>
                                <div className="text-[9px] font-bold uppercase tracking-[.18em] text-slate-500">Ticket Online</div>
                            </div>
                        </Link>

                        <div className="hidden items-center gap-1 rounded-full bg-emerald-50 p-1 md:flex">
                            <Link href="/" className="rounded-full bg-white px-5 py-2 text-sm font-bold text-[#08704f] shadow-sm">
                                Beranda
                            </Link>
                            <a href="#tiket" className="rounded-full px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-white hover:text-[#08704f]">
                                Tiket
                            </a>
                        </div>

                        <div className="flex items-center gap-2">
                            <Link href="/reservation" className="hidden rounded-xl px-4 py-2.5 text-sm font-bold text-slate-600 transition hover:bg-emerald-50 hover:text-emerald-800 sm:block">
                                Cek Reservasi
                            </Link>
                            <Link href="/checkout" className="rounded-xl bg-[#ff650f] px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#ed5707]">
                                Beli Tiket
                            </Link>
                        </div>
                    </nav>
                </header>

                <main>
                    {/* HERO SLIDER */}
                    <section className="relative mx-auto max-w-6xl px-5 pb-14 pt-8 sm:px-6">
                        <div className="pointer-events-none absolute -left-20 top-16 h-64 w-64 rounded-full bg-emerald-200/35 blur-3xl ds-pulse" />

                        <div className="relative overflow-hidden rounded-[30px] bg-[#075b49] shadow-[0_28px_70px_rgba(0,90,65,.18)]">
                            {slides.map((slide, index) => (
                                <div
                                    key={slide.title}
                                    className={`absolute inset-0 transition-opacity duration-700 ${index === activeSlide ? 'opacity-100' : 'pointer-events-none opacity-0'
                                        }`}
                                >
                                    <img
                                        src={slide.image}
                                        alt={slide.title}
                                        className="h-[430px] w-full object-cover sm:h-[500px]"
                                    />
                                    <div className="absolute inset-0 bg-gradient-to-r from-[#003f32]/90 via-[#003f32]/45 to-transparent" />
                                </div>
                            ))}

                            <div className="relative flex min-h-[430px] items-center px-7 py-12 sm:min-h-[500px] sm:px-12 lg:px-16">
                                <div key={activeSlide} className="ds-fade-up max-w-xl text-white">
                                    <div className="mb-4 inline-flex rounded-full bg-white/15 px-4 py-2 text-xs font-black uppercase tracking-[.18em] backdrop-blur">
                                        🌿 {slides[activeSlide].eyebrow}
                                    </div>

                                    <h1 className="text-4xl font-black leading-[1.03] tracking-tight sm:text-5xl lg:text-6xl">
                                        {slides[activeSlide].title}
                                    </h1>

                                    <p className="mt-5 max-w-lg text-base leading-7 text-white/85 sm:text-lg">
                                        {slides[activeSlide].description}
                                    </p>

                                    <div className="mt-7 flex flex-wrap gap-3">
                                        <Link href="/checkout" className="rounded-2xl bg-[#ff650f] px-6 py-3.5 font-extrabold text-white shadow-xl shadow-orange-900/20 transition hover:-translate-y-1 hover:bg-[#ed5707]">
                                            {slides[activeSlide].button} →
                                        </Link>
                                        <a href="#tiket" className="rounded-2xl border border-white/70 bg-white/10 px-6 py-3.5 font-extrabold text-white backdrop-blur transition hover:-translate-y-1 hover:bg-white hover:text-[#075b49]">
                                            Lihat Tiket
                                        </a>
                                    </div>

                                    <div className="mt-7 flex flex-wrap gap-5 text-sm font-semibold text-white/85">
                                        <span>✓ Aman & Terpercaya</span>
                                        <span>✓ QR E-Ticket</span>
                                        <span>✓ Easy Booking</span>
                                    </div>
                                </div>
                            </div>

                            {/* arrows */}
                            <button
                                type="button"
                                onClick={previousSlide}
                                aria-label="Slide sebelumnya"
                                className="absolute left-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-xl text-white backdrop-blur transition hover:bg-white hover:text-[#075b49]"
                            >
                                ←
                            </button>
                            <button
                                type="button"
                                onClick={nextSlide}
                                aria-label="Slide berikutnya"
                                className="absolute right-4 top-1/2 flex h-11 w-11 -translate-y-1/2 items-center justify-center rounded-full bg-white/20 text-xl text-white backdrop-blur transition hover:bg-white hover:text-[#075b49]"
                            >
                                →
                            </button>

                            {/* dots */}
                            <div className="absolute bottom-5 left-1/2 flex -translate-x-1/2 gap-2">
                                {slides.map((slide, index) => (
                                    <button
                                        key={slide.title}
                                        type="button"
                                        onClick={() => setActiveSlide(index)}
                                        aria-label={`Buka slide ${index + 1}`}
                                        className={`h-2.5 rounded-full transition-all ${index === activeSlide ? 'w-8 bg-white' : 'w-2.5 bg-white/45'
                                            }`}
                                    />
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* TICKETS */}
                    <section id="tiket" className="scroll-mt-24 bg-gradient-to-b from-[#eefaf3] to-[#e9f7f0] px-5 py-16 sm:px-6">
                        <div className="mx-auto max-w-6xl">
                            <div className="mx-auto max-w-2xl text-center">
                                <div className="text-xs font-black uppercase tracking-[.25em] text-[#08704f]">Pilih tiket favoritmu</div>
                                <h2 className="mt-3 text-4xl font-black tracking-tight text-[#075b49] sm:text-5xl">Ticket Dusem</h2>
                                <p className="mt-3 text-slate-500">Nikmati berbagai pilihan tiket untuk pengalaman liburan yang tak terlupakan.</p>
                            </div>

                            <div className="mx-auto mt-10 grid max-w-5xl grid-cols-1 justify-items-center gap-6 md:grid-cols-3">
                                {tickets.map((ticket, index) => (
                                    <Link
                                        key={ticket.name}
                                        href={`/checkout?category=${ticket.category}`}
                                        className="ds-ticket ds-fade-up group relative w-full max-w-[310px] overflow-hidden rounded-[22px] border border-white bg-white shadow-[0_12px_35px_rgba(0,100,70,.10)] transition duration-500 hover:-translate-y-2 hover:shadow-[0_22px_50px_rgba(0,100,70,.17)]"
                                        style={{ animationDelay: `${150 + index * 100}ms` }}
                                    >
                                        <span className="ds-shine" />
                                        <div className="relative h-40 overflow-hidden">
                                            <img src={ticket.image} alt={ticket.name} className="h-full w-full object-cover transition duration-700 group-hover:scale-110" />
                                            <div className="absolute inset-0 bg-gradient-to-t from-black/55 via-transparent to-transparent" />
                                            {ticket.badge && (
                                                <span className="absolute left-3 top-3 rounded-full bg-[#ff650f] px-3 py-1.5 text-[10px] font-black uppercase text-white shadow">
                                                    {ticket.badge}
                                                </span>
                                            )}
                                            <span className="absolute bottom-3 left-4 rounded-full bg-white px-3 py-1 text-[10px] font-black text-[#08704f]">
                                                {ticket.category}
                                            </span>
                                        </div>
                                        <div className="p-5">
                                            <h3 className="text-lg font-black text-[#075b49]">{ticket.name}</h3>
                                            <p className="mt-2 min-h-[48px] text-sm leading-5 text-slate-500">{ticket.description}</p>
                                            <div className="mt-4">
                                                <div className="text-xs font-semibold text-slate-400">Mulai dari</div>
                                                <div className="mt-0.5 text-xl font-black text-[#ff650f]">{ticket.price}</div>
                                            </div>
                                            <div className="mt-4 rounded-xl bg-[#08704f] py-3 text-center text-sm font-black text-white transition group-hover:bg-[#075b49]">
                                                Pilih Tiket →
                                            </div>
                                        </div>
                                    </Link>
                                ))}
                            </div>
                        </div>
                    </section>

                    {/* BENEFITS */}
                    <section className="border-y border-emerald-100 bg-white px-5 py-8 sm:px-6">
                        <div className="mx-auto grid max-w-5xl grid-cols-2 gap-5 md:grid-cols-4">
                            {[
                                ['🎟️', '100%', 'Tiket Online'],
                                ['🛡️', 'Aman', '& Terpercaya'],
                                ['▦', 'QR E-Ticket', 'Langsung Masuk'],
                                ['🎧', 'Easy Booking', 'Pesan Lebih Mudah'],
                            ].map(([icon, title, subtitle]) => (
                                <div key={title} className="flex items-center justify-center gap-3">
                                    <div className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-50 text-lg">{icon}</div>
                                    <div>
                                        <div className="text-sm font-black text-slate-700">{title}</div>
                                        <div className="text-[11px] text-slate-500">{subtitle}</div>
                                    </div>
                                </div>
                            ))}
                        </div>
                    </section>

                    {/* OSIL */}
                    <section className="relative overflow-hidden bg-[#e9f8ee] px-5 py-14 sm:px-6">
                        <div className="mx-auto grid max-w-6xl items-center gap-8 lg:grid-cols-[.85fr_1.15fr]">
                            <div className="flex justify-center">
                                <img src="/images/osil.png" alt="OSIL" className="ds-float h-48 w-auto object-contain drop-shadow-xl sm:h-60" />
                            </div>
                            <div>
                                <div className="text-xs font-black uppercase tracking-[.2em] text-[#08704f]">Teman liburanmu</div>
                                <h2 className="mt-2 text-3xl font-black leading-tight text-[#075b49] sm:text-4xl">
                                    Liburan Lebih Seru
                                    <span className="block">Bersama <span className="text-[#ff650f]">OSIL!</span></span>
                                </h2>
                                <p className="mt-4 max-w-xl leading-7 text-slate-600">
                                    OSIL siap menemani perjalanan liburanmu di Dusun Semilir. Jangan lupa abadikan momen dan nikmati semua keseruannya.
                                </p>
                                <Link href="/checkout" className="mt-6 inline-flex rounded-xl bg-[#ff650f] px-5 py-3 font-black text-white shadow-lg transition hover:-translate-y-1 hover:bg-[#ed5707]">
                                    Beli Tiket Sekarang →
                                </Link>
                            </div>
                        </div>
                    </section>

                    {/* RESERVATION */}
                    <section className="bg-white px-5 py-8 sm:px-6">
                        <div className="mx-auto max-w-2xl rounded-2xl border border-emerald-100 bg-white px-5 py-4 text-center shadow-[0_8px_30px_rgba(0,80,55,.08)]">
                            <p className="text-sm text-slate-600">Telah melakukan reservasi? cek status reservasi di sini</p>
                            <Link href="/reservation" className="mt-1 inline-block font-black text-[#ff650f] transition hover:text-[#ed5707]">
                                Klik di sini →
                            </Link>
                        </div>
                    </section>
                </main>

                {/* FOOTER */}
                <footer className="bg-[#ffddb0]">
                    <div className="mx-auto grid max-w-6xl gap-10 px-5 py-11 sm:px-6 md:grid-cols-3">
                        <div>
                            <h3 className="text-sm font-black text-slate-800">Layanan Pelanggan</h3>
                            <div className="mt-4 space-y-4">
                                <a href="https://wa.me/628112747724" className="flex items-center gap-3 transition hover:translate-x-1">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-full bg-[#25d366] text-white">◔</span>
                                    <span>
                                        <span className="block text-sm font-semibold text-slate-700">WhatsApp</span>
                                        <span className="block text-xs text-slate-600">+62 8112747724</span>
                                    </span>
                                </a>
                                <a href="mailto:book@dusunsemilir.com" className="flex items-center gap-3 transition hover:translate-x-1">
                                    <span className="flex h-9 w-9 items-center justify-center rounded-full bg-[#ef4444] text-white">✉</span>
                                    <span>
                                        <span className="block text-sm font-semibold text-slate-700">Email</span>
                                        <span className="block text-xs text-slate-600">book@dusunsemilir.com</span>
                                    </span>
                                </a>
                            </div>
                        </div>

                        <div className="md:text-center">
                            <h3 className="text-sm font-black text-slate-800">Follow Us</h3>
                            <div className="mt-4 flex gap-3 md:justify-center">
                                <SocialIcon>f</SocialIcon>
                                <SocialIcon>◎</SocialIcon>
                                <SocialIcon>♪</SocialIcon>
                                <SocialIcon>▶</SocialIcon>
                            </div>
                        </div>

                        <div className="md:text-right">
                            <h3 className="text-sm font-black text-slate-800">Alamat</h3>
                            <div className="mt-4 flex gap-3 md:justify-end">
                                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/80 text-lg text-emerald-600">⌖</span>
                                <p className="max-w-xs text-xs leading-5 text-slate-600 md:text-right">
                                    Jl. Soekarno - Hatta No.49, Ngemple,
                                    <br />
                                    Bawen, Ngemplak, Kabupaten Semarang,
                                    <br />
                                    Jawa Tengah 50661
                                </p>
                            </div>
                        </div>
                    </div>

                    <div className="border-t border-[#f0c98f] py-4 text-center text-xs text-slate-500">
                        Hak Cipta © 2026 Dusun Semilir Bawen
                    </div>
                </footer>
            </div>
        </>
    );
}
