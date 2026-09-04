import { Head, Link } from '@inertiajs/react';
import { FormEvent, useMemo, useState } from 'react';

type Reservation = {
    order: string;
    date: string;
    ticket: string;
    amount: string;
    status: 'Berhasil' | 'Kadaluwarsa' | 'Menunggu';
};

const demoReservations: Reservation[] = [
    {
        order: 'ORD-76328A37',
        date: 'Senin, 17 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Berhasil',
    },
    {
        order: 'ORD-D53CA384',
        date: 'Sabtu, 15 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Berhasil',
    },
    {
        order: 'ORD-09524A8E',
        date: 'Sabtu, 15 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Kadaluwarsa',
    },
    {
        order: 'ORD-2C96C3AB',
        date: 'Sabtu, 15 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Kadaluwarsa',
    },
    {
        order: 'ORD-0A183506',
        date: 'Sabtu, 15 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Kadaluwarsa',
    },
    {
        order: 'ORD-9D54B09E',
        date: 'Sabtu, 15 Agustus 2026',
        ticket: 'Tiket Wonderlight',
        amount: 'IDR 1',
        status: 'Kadaluwarsa',
    },
];

export default function Reservation() {
    const [method, setMethod] = useState<'email' | 'phone'>('email');
    const [keyword, setKeyword] = useState('stylus.smg@gmail.com');
    const [searched, setSearched] = useState(true);
    const [selected, setSelected] = useState<Reservation | null>(null);

    const results = useMemo(() => {
        if (!searched) return [];
        return demoReservations;
    }, [searched]);

    const submitSearch = (event: FormEvent) => {
        event.preventDefault();
        setSearched(Boolean(keyword.trim()));
        setSelected(null);
    };

    return (
        <>
            <Head title="Cek Reservasi - Dusun Semilir" />

            <style>{`
                @keyframes reservation-fade {
                    from { opacity: 0; transform: translateY(18px); }
                    to { opacity: 1; transform: translateY(0); }
                }

                @keyframes reservation-float {
                    0%, 100% { transform: translateY(0); }
                    50% { transform: translateY(-9px); }
                }

                @keyframes reservation-pulse {
                    0%, 100% { opacity: .35; transform: scale(1); }
                    50% { opacity: .65; transform: scale(1.08); }
                }

                .reservation-fade {
                    animation: reservation-fade .65s cubic-bezier(.22,1,.36,1) both;
                }

                .reservation-float {
                    animation: reservation-float 5s ease-in-out infinite;
                }

                .reservation-pulse {
                    animation: reservation-pulse 4s ease-in-out infinite;
                }
            `}</style>

            <div className="min-h-screen overflow-x-hidden bg-[#f4fbf7] text-slate-800">
                {/* Top strip */}
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

                {/* Navbar */}
                <header className="sticky top-0 z-50 px-3 pt-3 sm:px-5">
                    <nav className="mx-auto flex h-[66px] max-w-6xl items-center justify-between rounded-2xl border border-white/80 bg-white/90 px-4 shadow-[0_12px_40px_rgba(0,80,55,.12)] backdrop-blur-xl sm:px-6">
                        <Link href="/" className="group flex items-center gap-3">
                            <div className="flex h-11 w-11 items-center justify-center rounded-xl bg-[#e9f7ef] p-1.5 ring-1 ring-emerald-100 transition group-hover:rotate-2 group-hover:scale-105">
                                <img
                                    src="/images/logo-dusun-semilir.svg"
                                    alt="Dusun Semilir"
                                    className="h-full w-full object-contain"
                                />
                            </div>

                            <div>
                                <div className="text-sm font-black tracking-tight text-[#075b49]">
                                    Dusun Semilir
                                </div>
                                <div className="text-[9px] font-bold uppercase tracking-[.18em] text-slate-500">
                                    Ticket Online
                                </div>
                            </div>
                        </Link>

                        <div className="hidden items-center gap-1 rounded-full bg-emerald-50 p-1 md:flex">
                            <Link
                                href="/"
                                className="rounded-full px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-white hover:text-[#08704f]"
                            >
                                Beranda
                            </Link>
                            <a
                                href="/#tiket"
                                className="rounded-full px-5 py-2 text-sm font-semibold text-slate-600 transition hover:bg-white hover:text-[#08704f]"
                            >
                                Tiket
                            </a>
                            <span className="rounded-full bg-white px-5 py-2 text-sm font-bold text-[#08704f] shadow-sm">
                                Cek Reservasi
                            </span>
                        </div>

                        <Link
                            href="/checkout"
                            className="rounded-xl bg-[#ff650f] px-4 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#ed5707]"
                        >
                            Beli Tiket
                        </Link>
                    </nav>
                </header>

                <main>
                    {/* Hero */}
                    <section className="relative overflow-hidden">
                        <div className="absolute inset-0">
                            <img
                                src="/images/hero-slider-2.jpg"
                                alt=""
                                className="h-full w-full object-cover"
                            />
                            <div className="absolute inset-0 bg-gradient-to-r from-white via-white/95 to-white/20" />
                            <div className="absolute inset-0 bg-gradient-to-t from-white/85 via-transparent to-transparent" />
                        </div>

                        <div className="reservation-pulse absolute -left-20 top-20 h-72 w-72 rounded-full bg-emerald-300/30 blur-3xl" />

                        <div className="relative mx-auto max-w-6xl px-5 pb-28 pt-10 sm:px-6 sm:pt-14">
                            <div className="reservation-fade max-w-xl">
                                <div className="flex items-center gap-2 text-sm font-semibold text-slate-500">
                                    <Link href="/" className="text-[#ff650f] hover:underline">
                                        Beranda
                                    </Link>
                                    <span>›</span>
                                    <span>Cek Reservasi</span>
                                </div>

                                <h1 className="mt-5 text-4xl font-black tracking-tight text-[#075b49] sm:text-5xl">
                                    Cek Reservasi
                                </h1>

                                <p className="mt-3 max-w-md text-base leading-7 text-slate-600 sm:text-lg">
                                    Cari dan lihat status reservasi tiket Anda dengan cepat dan mudah.
                                </p>

                                <div className="mt-6 flex flex-wrap gap-3 text-xs font-bold text-[#08704f]">
                                    <span className="rounded-full bg-white/80 px-4 py-2 shadow-sm backdrop-blur">
                                        ✓ Status pembayaran
                                    </span>
                                    <span className="rounded-full bg-white/80 px-4 py-2 shadow-sm backdrop-blur">
                                        ✓ Detail tiket
                                    </span>
                                    <span className="rounded-full bg-white/80 px-4 py-2 shadow-sm backdrop-blur">
                                        ✓ E-ticket
                                    </span>
                                </div>
                            </div>
                        </div>
                    </section>

                    {/* Search + results */}
                    <section className="relative z-10 mx-auto -mt-20 max-w-6xl px-5 pb-16 sm:px-6">
                        <div className="grid gap-6 lg:grid-cols-[1.65fr_.85fr]">
                            {/* Main card */}
                            <div className="reservation-fade rounded-[28px] border border-white bg-white p-5 shadow-[0_20px_60px_rgba(0,90,65,.12)] sm:p-7">
                                <div className="flex flex-wrap gap-2 rounded-xl bg-slate-100 p-1.5">
                                    <button
                                        type="button"
                                        onClick={() => setMethod('email')}
                                        className={`flex-1 rounded-lg px-4 py-2.5 text-sm font-bold transition ${method === 'email'
                                            ? 'bg-white text-[#ff650f] shadow-sm'
                                            : 'text-slate-500 hover:text-slate-800'
                                            }`}
                                    >
                                        ✉ Email
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => setMethod('phone')}
                                        className={`flex-1 rounded-lg px-4 py-2.5 text-sm font-bold transition ${method === 'phone'
                                            ? 'bg-white text-[#ff650f] shadow-sm'
                                            : 'text-slate-500 hover:text-slate-800'
                                            }`}
                                    >
                                        ☎ No. Telepon
                                    </button>
                                </div>

                                <form onSubmit={submitSearch} className="mt-4 flex flex-col gap-3 sm:flex-row">
                                    <div className="relative flex-1">
                                        <span className="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">
                                            {method === 'email' ? '✉' : '☎'}
                                        </span>
                                        <input
                                            type={method === 'email' ? 'email' : 'tel'}
                                            value={keyword}
                                            onChange={(event) => setKeyword(event.target.value)}
                                            placeholder={
                                                method === 'email'
                                                    ? 'Masukkan email'
                                                    : 'Masukkan nomor telepon'
                                            }
                                            className="h-12 w-full rounded-xl border border-slate-200 bg-white pl-11 pr-4 text-sm font-medium outline-none transition focus:border-[#08704f] focus:ring-4 focus:ring-emerald-50"
                                        />
                                    </div>

                                    <button
                                        type="submit"
                                        className="h-12 rounded-xl bg-[#ff650f] px-7 font-extrabold text-white shadow-lg shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-[#ed5707]"
                                    >
                                        🔍 Cari Reservasi
                                    </button>
                                </form>

                                <div className="mt-7 flex items-center justify-between gap-3">
                                    <div>
                                        <h2 className="text-lg font-black text-[#075b49]">
                                            Hasil Reservasi
                                            {searched && (
                                                <span className="ml-2 text-sm font-semibold text-slate-400">
                                                    ({results.length})
                                                </span>
                                            )}
                                        </h2>
                                        <p className="mt-1 text-xs text-slate-400">
                                            Pilih reservasi untuk melihat detail.
                                        </p>
                                    </div>

                                    {results.length > 1 && (
                                        <button
                                            type="button"
                                            className="text-xs font-bold text-slate-500 hover:text-[#08704f]"
                                        >
                                            Urutkan ↕
                                        </button>
                                    )}
                                </div>

                                <div className="mt-4 space-y-2.5">
                                    {results.map((reservation, index) => (
                                        <button
                                            key={reservation.order}
                                            type="button"
                                            onClick={() => setSelected(reservation)}
                                            className={`reservation-fade group flex w-full items-center gap-4 rounded-2xl border p-4 text-left transition duration-300 ${selected?.order === reservation.order
                                                ? 'border-emerald-300 bg-emerald-50/60 shadow-sm'
                                                : 'border-slate-200 bg-white hover:-translate-y-0.5 hover:border-emerald-200 hover:shadow-md'
                                                }`}
                                            style={{ animationDelay: `${index * 70}ms` }}
                                        >
                                            <span className="flex h-11 w-11 shrink-0 items-center justify-center rounded-full bg-emerald-100 text-xl">
                                                🎟️
                                            </span>

                                            <span className="min-w-0 flex-1">
                                                <span className="block truncate text-sm font-black text-slate-800">
                                                    {reservation.order}
                                                </span>
                                                <span className="mt-1 block text-xs text-slate-500">
                                                    📅 {reservation.date}
                                                </span>
                                                <span className="mt-1 block text-xs text-slate-400">
                                                    {reservation.ticket}
                                                </span>
                                            </span>

                                            <span className="shrink-0 text-right">
                                                <span className="block text-sm font-black text-[#ff650f]">
                                                    {reservation.amount}
                                                </span>
                                                <span
                                                    className={`mt-1 inline-flex rounded-full px-2.5 py-1 text-[10px] font-black ${reservation.status === 'Berhasil'
                                                        ? 'bg-emerald-100 text-emerald-700'
                                                        : reservation.status === 'Kadaluwarsa'
                                                            ? 'bg-red-100 text-red-600'
                                                            : 'bg-amber-100 text-amber-700'
                                                        }`}
                                                >
                                                    {reservation.status === 'Berhasil' ? '✓' : '×'}{' '}
                                                    {reservation.status}
                                                </span>
                                            </span>

                                            <span className="text-xl text-slate-300 transition group-hover:translate-x-1 group-hover:text-[#08704f]">
                                                ›
                                            </span>
                                        </button>
                                    ))}
                                </div>

                                {!searched && (
                                    <div className="mt-5 rounded-xl bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                                        Masukkan email atau nomor telepon untuk mencari reservasi.
                                    </div>
                                )}

                                <div className="mt-5 rounded-xl bg-emerald-50 px-4 py-3 text-xs font-medium text-emerald-700">
                                    ℹ Tidak menemukan reservasi? Pastikan email/nomor telepon yang Anda
                                    masukkan sudah benar.
                                </div>
                            </div>

                            {/* Detail / information card */}
                            <aside className="reservation-fade rounded-[28px] border border-white bg-white p-7 shadow-[0_20px_60px_rgba(0,90,65,.12)]">
                                {selected ? (
                                    <div>
                                        <div className="flex items-center justify-between">
                                            <span className="rounded-full bg-emerald-100 px-3 py-1.5 text-xs font-black text-emerald-700">
                                                Detail Reservasi
                                            </span>
                                            <button
                                                type="button"
                                                onClick={() => setSelected(null)}
                                                className="text-xs font-bold text-slate-400 hover:text-slate-700"
                                            >
                                                Tutup
                                            </button>
                                        </div>

                                        <div className="mt-7 flex justify-center">
                                            <div className="reservation-float flex h-24 w-24 items-center justify-center rounded-[28px] bg-emerald-100 text-5xl">
                                                🎟️
                                            </div>
                                        </div>

                                        <h3 className="mt-6 text-xl font-black text-[#075b49]">
                                            {selected.order}
                                        </h3>

                                        <div className="mt-5 space-y-3">
                                            <div className="rounded-xl bg-slate-50 p-3">
                                                <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    Tiket
                                                </div>
                                                <div className="mt-1 text-sm font-bold text-slate-700">
                                                    {selected.ticket}
                                                </div>
                                            </div>
                                            <div className="rounded-xl bg-slate-50 p-3">
                                                <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    Tanggal Kunjungan
                                                </div>
                                                <div className="mt-1 text-sm font-bold text-slate-700">
                                                    {selected.date}
                                                </div>
                                            </div>
                                            <div className="rounded-xl bg-slate-50 p-3">
                                                <div className="text-[10px] font-bold uppercase tracking-wider text-slate-400">
                                                    Total
                                                </div>
                                                <div className="mt-1 text-lg font-black text-[#ff650f]">
                                                    {selected.amount}
                                                </div>
                                            </div>
                                        </div>

                                        <div className="mt-5 rounded-xl bg-emerald-50 p-4 text-sm font-bold text-emerald-700">
                                            Status: {selected.status}
                                        </div>

                                        <Link
                                            href={`/reservation/${selected.order}`}
                                            className="mt-5 block rounded-xl bg-[#08704f] px-5 py-3 text-center text-sm font-black text-white transition hover:bg-[#075b49]"
                                        >
                                            Lihat Detail Lengkap →
                                        </Link>
                                    </div>
                                ) : (
                                    <div className="flex h-full min-h-[470px] flex-col items-center justify-center text-center">
                                        <div className="reservation-float flex h-28 w-28 items-center justify-center rounded-full bg-emerald-50 text-5xl">
                                            🔎
                                        </div>

                                        <h3 className="mt-7 text-xl font-black text-[#075b49]">
                                            Lihat Detail Reservasi Anda
                                        </h3>

                                        <p className="mt-3 max-w-xs text-sm leading-6 text-slate-500">
                                            Pilih salah satu reservasi dari daftar di samping untuk melihat
                                            detail lengkap, termasuk informasi tiket, tanggal kunjungan, dan
                                            status pembayaran.
                                        </p>

                                        <div className="mt-7 w-full space-y-4 text-left">
                                            {[
                                                ['💬', 'Mudah & Cepat', 'Cari reservasi hanya dengan email atau nomor telepon'],
                                                ['▣', 'Informasi Lengkap', 'Lihat detail tiket dan status pembayaran'],
                                                ['🛡️', 'Aman & Terpercaya', 'Data reservasi Anda aman bersama kami'],
                                            ].map(([icon, title, text]) => (
                                                <div key={title} className="flex gap-3">
                                                    <span className="flex h-10 w-10 shrink-0 items-center justify-center rounded-full bg-emerald-100">
                                                        {icon}
                                                    </span>
                                                    <div>
                                                        <div className="text-sm font-black text-slate-700">{title}</div>
                                                        <div className="mt-1 text-xs leading-5 text-slate-500">{text}</div>
                                                    </div>
                                                </div>
                                            ))}
                                        </div>
                                    </div>
                                )}
                            </aside>
                        </div>
                    </section>
                </main>

                {/* Footer */}
                <footer className="bg-[#ffddb0]">
                    <div className="mx-auto grid max-w-6xl gap-10 px-5 py-11 sm:px-6 md:grid-cols-3">
                        <div>
                            <h3 className="text-sm font-black text-slate-800">Layanan Pelanggan</h3>

                            <div className="mt-4 space-y-4">
                                <a
                                    href="https://wa.me/628112747724"
                                    className="flex items-center gap-3 transition hover:translate-x-1"
                                >
                                    <span className="flex h-9 w-9 items-center justify-center rounded-full bg-[#25d366] text-white">
                                        ◔
                                    </span>
                                    <span>
                                        <span className="block text-sm font-semibold text-slate-700">WhatsApp</span>
                                        <span className="block text-xs text-slate-600">+62 8112747724</span>
                                    </span>
                                </a>

                                <a
                                    href="mailto:book@dusunsemilir.com"
                                    className="flex items-center gap-3 transition hover:translate-x-1"
                                >
                                    <span className="flex h-9 w-9 items-center justify-center rounded-full bg-[#ef4444] text-white">
                                        ✉
                                    </span>
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
                                <span className="flex h-11 w-11 items-center justify-center rounded-full bg-[#1877f2] text-lg font-black text-white shadow-sm">f</span>
                                <span className="flex h-11 w-11 items-center justify-center rounded-full bg-[#e1306c] text-lg font-black text-white shadow-sm">◎</span>
                                <span className="flex h-11 w-11 items-center justify-center rounded-full bg-black text-lg font-black text-white shadow-sm">♪</span>
                                <span className="flex h-11 w-11 items-center justify-center rounded-full bg-[#ff0000] text-lg font-black text-white shadow-sm">▶</span>
                            </div>
                        </div>

                        <div className="md:text-right">
                            <h3 className="text-sm font-black text-slate-800">Alamat</h3>
                            <div className="mt-4 flex gap-3 md:justify-end">
                                <span className="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-white/80 text-lg text-emerald-600">
                                    ⌖
                                </span>
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
