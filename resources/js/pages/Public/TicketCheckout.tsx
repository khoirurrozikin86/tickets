import React, { useMemo, useState } from 'react';
import { Head } from '@inertiajs/react';

type Product = {
    id: number;
    name: string;
    category?: string;
    description?: string;
    image?: string | null;
};

type TicketOption = {
    id: number;
    name: string;
    weekdayPrice: number;
    weekendPrice: number;
    allDay?: boolean;
    active?: boolean;
};

type Promotion = {
    id: number;
    name: string;
    type: 'percentage' | 'fixed';
    value: number;
    startDate: string;
    endDate: string;
    active?: boolean;
};

type Customer = {
    name: string;
    phone: string;
    email: string;
    birthDate: string;
    province: string;
    city: string;
};

type Props = {
    product?: Product;
    ticketOptions?: TicketOption[];
    promotions?: Promotion[];
};

const rupiah = (value: number) =>
    new Intl.NumberFormat('id-ID', {
        style: 'currency',
        currency: 'IDR',
        maximumFractionDigits: 0,
    }).format(value);

const formatLongDate = (date: Date) =>
    new Intl.DateTimeFormat('id-ID', {
        weekday: 'long',
        day: 'numeric',
        month: 'long',
        year: 'numeric',
    }).format(date);

const dateKey = (date: Date) => {
    const y = date.getFullYear();
    const m = String(date.getMonth() + 1).padStart(2, '0');
    const d = String(date.getDate()).padStart(2, '0');
    return `${y}-${m}-${d}`;
};

const parseDate = (value: string) => {
    const [y, m, d] = value.split('-').map(Number);
    return new Date(y, m - 1, d);
};

const defaultTicketOptions: TicketOption[] = [
    {
        id: 1,
        name: 'Tiket Reguler',
        weekdayPrice: 80000,
        weekendPrice: 100000,
    },
    {
        id: 2,
        name: 'Tiket Terusan',
        weekdayPrice: 120000,
        weekendPrice: 140000,
    },
];

const defaultPromotions: Promotion[] = [
    {
        id: 1,
        name: 'Promo September',
        type: 'percentage',
        value: 15,
        startDate: '2026-09-01',
        endDate: '2026-09-30',
        active: true,
    },
];

const emptyCustomer: Customer = {
    name: '',
    phone: '',
    email: '',
    birthDate: '',
    province: '',
    city: '',
};

function Calendar({
    value,
    onChange,
}: {
    value: string;
    onChange: (value: string) => void;
}) {
    const initial = value ? parseDate(value) : new Date();
    const [month, setMonth] = useState(
        new Date(initial.getFullYear(), initial.getMonth(), 1),
    );

    const year = month.getFullYear();
    const monthIndex = month.getMonth();
    const firstDay = new Date(year, monthIndex, 1).getDay();
    const daysInMonth = new Date(year, monthIndex + 1, 0).getDate();
    const previousMonthDays = new Date(year, monthIndex, 0).getDate();

    const cells = Array.from({ length: 42 }, (_, index) => {
        const dayNumber = index - firstDay + 1;

        if (dayNumber < 1) {
            return {
                date: new Date(year, monthIndex - 1, previousMonthDays + dayNumber),
                current: false,
            };
        }

        if (dayNumber > daysInMonth) {
            return {
                date: new Date(year, monthIndex + 1, dayNumber - daysInMonth),
                current: false,
            };
        }

        return {
            date: new Date(year, monthIndex, dayNumber),
            current: true,
        };
    });

    const selected = value ? dateKey(parseDate(value)) : '';

    return (
        <div>
            <div className="mb-5 flex items-center justify-between">
                <button
                    type="button"
                    onClick={() => setMonth(new Date(year, monthIndex - 1, 1))}
                    className="grid h-10 w-10 place-items-center rounded-full text-xl text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-700"
                >
                    ‹
                </button>

                <div className="text-center">
                    <div className="text-lg font-extrabold text-slate-900">
                        {new Intl.DateTimeFormat('id-ID', {
                            month: 'long',
                        }).format(month)}
                    </div>
                    <div className="text-sm font-semibold text-slate-400">
                        {year}
                    </div>
                </div>

                <button
                    type="button"
                    onClick={() => setMonth(new Date(year, monthIndex + 1, 1))}
                    className="grid h-10 w-10 place-items-center rounded-full text-xl text-slate-400 transition hover:bg-emerald-50 hover:text-emerald-700"
                >
                    ›
                </button>
            </div>

            <div className="mb-2 grid grid-cols-7 text-center text-xs font-bold uppercase tracking-wide text-slate-400">
                {['Min', 'Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab'].map((day) => (
                    <div key={day} className="py-2">
                        {day}
                    </div>
                ))}
            </div>

            <div className="grid grid-cols-7 gap-y-1">
                {cells.map(({ date, current }, index) => {
                    const key = dateKey(date);
                    const isSelected = key === selected;
                    const isPast =
                        date <
                        new Date(
                            new Date().getFullYear(),
                            new Date().getMonth(),
                            new Date().getDate(),
                        );

                    return (
                        <button
                            key={`${key}-${index}`}
                            type="button"
                            disabled={isPast}
                            onClick={() => onChange(key)}
                            className={[
                                'mx-auto grid h-10 w-10 place-items-center rounded-full text-sm font-semibold transition',
                                current
                                    ? 'text-slate-700 hover:bg-emerald-50 hover:text-emerald-700'
                                    : 'text-slate-200',
                                isSelected
                                    ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/25 hover:bg-emerald-600 hover:text-white'
                                    : '',
                                isPast ? 'cursor-not-allowed opacity-40' : '',
                            ].join(' ')}
                        >
                            {date.getDate()}
                        </button>
                    );
                })}
            </div>
        </div>
    );
}

function Modal({
    children,
    onClose,
}: {
    children: React.ReactNode;
    onClose?: () => void;
}) {
    return (
        <div className="fixed inset-0 z-50 grid place-items-center bg-slate-950/60 p-4 backdrop-blur-sm">
            <div className="relative max-h-[90vh] w-full max-w-xl overflow-auto rounded-[28px] bg-white p-7 shadow-2xl">
                {onClose && (
                    <button
                        type="button"
                        onClick={onClose}
                        className="absolute right-5 top-5 grid h-9 w-9 place-items-center rounded-full bg-slate-100 text-slate-500 hover:bg-slate-200"
                    >
                        ×
                    </button>
                )}
                {children}
            </div>
        </div>
    );
}

function StepHeader({ step }: { step: number }) {
    const labels = ['Tanggal & Tiket', 'Data Pemesan', 'Konfirmasi', 'Struk', 'Pembayaran'];

    return (
        <div className="mb-8 flex items-center justify-center gap-2 overflow-x-auto pb-1">
            {labels.map((label, index) => {
                const number = index + 1;
                const active = step >= number;

                return (
                    <React.Fragment key={label}>
                        <div className="flex shrink-0 items-center gap-2">
                            <div
                                className={[
                                    'grid h-8 w-8 place-items-center rounded-full text-xs font-extrabold',
                                    active
                                        ? 'bg-emerald-600 text-white'
                                        : 'bg-slate-100 text-slate-400',
                                ].join(' ')}
                            >
                                {number}
                            </div>
                            <span
                                className={[
                                    'hidden text-xs font-bold sm:block',
                                    active ? 'text-emerald-700' : 'text-slate-400',
                                ].join(' ')}
                            >
                                {label}
                            </span>
                        </div>
                        {index < labels.length - 1 && (
                            <div
                                className={[
                                    'h-px w-8 shrink-0 sm:w-14',
                                    step > number ? 'bg-emerald-500' : 'bg-slate-200',
                                ].join(' ')}
                            />
                        )}
                    </React.Fragment>
                );
            })}
        </div>
    );
}

export default function TicketCheckout({
    product = {
        id: 1,
        name: 'Tiket Reguler',
        category: 'Reguler',
    },
    ticketOptions = defaultTicketOptions,
    promotions = defaultPromotions,
}: Props) {
    const [step, setStep] = useState(1);
    const [selectedDate, setSelectedDate] = useState('');
    const [quantity, setQuantity] = useState(0);
    const [customer, setCustomer] = useState<Customer>(emptyCustomer);
    const [showDateConfirmation, setShowDateConfirmation] = useState(false);
    const [showEmailConfirmation, setShowEmailConfirmation] = useState(false);
    const [paymentStarted, setPaymentStarted] = useState(false);

    const selectedDay = selectedDate ? parseDate(selectedDate) : null;
    const isWeekend = selectedDay
        ? selectedDay.getDay() === 0 || selectedDay.getDay() === 6
        : false;

    const selectedTicket =
        ticketOptions.find((ticket) => ticket.id === product.id) ??
        ticketOptions[0];

    const normalPrice = selectedTicket
        ? selectedTicket.allDay
            ? selectedTicket.weekdayPrice
            : isWeekend
              ? selectedTicket.weekendPrice
              : selectedTicket.weekdayPrice
        : 0;

    const promotion = useMemo(() => {
        if (!selectedDate) return null;

        return (
            promotions.find(
                (promo) =>
                    promo.active !== false &&
                    selectedDate >= promo.startDate &&
                    selectedDate <= promo.endDate,
            ) ?? null
        );
    }, [promotions, selectedDate]);

    const discountPerTicket = promotion
        ? promotion.type === 'percentage'
            ? Math.round(normalPrice * (promotion.value / 100))
            : Math.min(normalPrice, promotion.value)
        : 0;

    const finalPricePerTicket = Math.max(0, normalPrice - discountPerTicket);
    const subtotal = normalPrice * quantity;
    const discountTotal = discountPerTicket * quantity;
    const total = finalPricePerTicket * quantity;

    const canContinue = Boolean(selectedDate && quantity > 0);

    const updateCustomer = (field: keyof Customer, value: string) => {
        setCustomer((current) => ({ ...current, [field]: value }));
    };

    const customerValid =
        customer.name.trim() &&
        customer.phone.trim() &&
        /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(customer.email) &&
        customer.birthDate &&
        customer.province &&
        customer.city;

    const startCheckout = () => {
        if (!canContinue) return;
        setShowDateConfirmation(true);
    };

    const confirmDate = () => {
        setShowDateConfirmation(false);
        setStep(2);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const continueCustomer = () => {
        if (!customerValid) return;
        setShowEmailConfirmation(true);
    };

    const confirmCustomer = () => {
        setShowEmailConfirmation(false);
        setStep(4);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    const startPayment = () => {
        setPaymentStarted(true);
        setStep(5);
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    return (
        <>
            <Head title={`${product.name} - Dusun Semilir`} />

            <div className="min-h-screen bg-gradient-to-b from-emerald-50 via-white to-emerald-50/60 text-slate-900">
                <div className="pointer-events-none fixed inset-0 overflow-hidden">
                    <div className="absolute -left-32 top-20 h-80 w-80 rounded-full bg-emerald-300/20 blur-3xl" />
                    <div className="absolute -right-32 top-72 h-96 w-96 rounded-full bg-lime-300/20 blur-3xl" />
                </div>

                <header className="relative z-10 mx-auto max-w-7xl px-4 pt-5 sm:px-6">
                    <div className="flex items-center justify-between rounded-2xl bg-white/90 px-5 py-4 shadow-sm ring-1 ring-slate-200/70 backdrop-blur">
                        <div className="flex items-center gap-3">
                            <div className="grid h-11 w-11 place-items-center rounded-xl bg-emerald-700 text-xl text-white shadow-lg shadow-emerald-700/20">
                                🌿
                            </div>
                            <div>
                                <div className="text-base font-black tracking-tight text-emerald-800">
                                    DUSUN SEMILIR
                                </div>
                                <div className="text-[9px] font-bold tracking-[0.3em] text-slate-400">
                                    TIKET ONLINE
                                </div>
                            </div>
                        </div>
                        <div className="hidden rounded-full bg-orange-500 px-5 py-2.5 text-sm font-extrabold text-white shadow-lg shadow-orange-500/20 sm:block">
                            Beli Tiket
                        </div>
                    </div>
                </header>

                <main className="relative z-10 mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:py-12">
                    <StepHeader step={step} />

                    {step === 1 && (
                        <>
                            <div className="mb-7 text-center">
                                <span className="inline-flex rounded-full bg-emerald-100 px-4 py-2 text-xs font-extrabold text-emerald-700">
                                    {product.category ?? 'Tiket'}
                                </span>
                                <h1 className="mt-3 text-3xl font-black tracking-tight sm:text-4xl">
                                    {product.name}
                                </h1>
                                <p className="mx-auto mt-2 max-w-2xl text-sm leading-6 text-slate-500">
                                    Pilih tanggal kedatangan dan jumlah tiket. Harga serta
                                    promo akan menyesuaikan tanggal yang dipilih.
                                </p>
                            </div>

                            <div className="grid gap-6 lg:grid-cols-[minmax(0,0.9fr)_minmax(0,1.35fr)]">
                                <section className="rounded-[28px] bg-white p-5 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70 sm:p-7">
                                    <div className="mb-6 flex items-center justify-between">
                                        <div>
                                            <h2 className="text-xl font-black">
                                                Tanggal Kedatangan
                                            </h2>
                                            <p className="mt-1 text-xs text-slate-400">
                                                Pilih tanggal kunjungan
                                            </p>
                                        </div>
                                        {selectedDate && (
                                            <span className="rounded-full bg-emerald-50 px-3 py-1.5 text-xs font-bold text-emerald-700">
                                                Dipilih
                                            </span>
                                        )}
                                    </div>

                                    <Calendar
                                        value={selectedDate}
                                        onChange={setSelectedDate}
                                    />
                                </section>

                                <section className="rounded-[28px] bg-white p-5 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70 sm:p-7">
                                    <h2 className="text-center text-xl font-black">
                                        Pilihan Tiket
                                    </h2>

                                    {selectedDate && (
                                        <div className="mx-auto mt-3 max-w-md rounded-2xl bg-emerald-50 px-4 py-3 text-center">
                                            <div className="text-[11px] font-bold uppercase tracking-wide text-emerald-600">
                                                Tanggal kunjungan
                                            </div>
                                            <div className="mt-1 text-sm font-black text-emerald-900">
                                                {formatLongDate(selectedDay!)}
                                            </div>
                                        </div>
                                    )}

                                    <div className="mx-auto mt-6 max-w-2xl rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">
                                        <div className="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h3 className="text-lg font-black">
                                                    {selectedTicket?.name ?? product.name}
                                                </h3>
                                                <p className="mt-1 text-xs text-slate-500">
                                                    {selectedDate
                                                        ? isWeekend
                                                            ? 'Harga Weekend'
                                                            : 'Harga Weekday'
                                                        : 'Pilih tanggal untuk melihat harga'}
                                                </p>

                                                <div className="mt-3 flex flex-wrap items-end gap-2">
                                                    <span className="text-sm text-slate-400 line-through">
                                                        {selectedDate ? rupiah(normalPrice) : '—'}
                                                    </span>
                                                    <span className="text-xl font-black text-emerald-700">
                                                        {selectedDate
                                                            ? rupiah(finalPricePerTicket)
                                                            : 'Pilih tanggal'}
                                                    </span>
                                                    {promotion && (
                                                        <span className="rounded-full bg-orange-50 px-2.5 py-1 text-[10px] font-black text-orange-600">
                                                            {promotion.type === 'percentage'
                                                                ? `PROMO ${promotion.value}%`
                                                                : `HEMAT ${rupiah(promotion.value)}`}
                                                        </span>
                                                    )}
                                                </div>
                                            </div>

                                            <div className="flex items-center gap-4 self-end sm:self-auto">
                                                <button
                                                    type="button"
                                                    disabled={quantity === 0}
                                                    onClick={() =>
                                                        setQuantity((value) =>
                                                            Math.max(0, value - 1),
                                                        )
                                                    }
                                                    className="grid h-11 w-11 place-items-center rounded-full bg-slate-100 text-xl font-black text-slate-600 transition hover:bg-slate-200 disabled:opacity-40"
                                                >
                                                    −
                                                </button>
                                                <span className="w-5 text-center text-xl font-black">
                                                    {quantity}
                                                </span>
                                                <button
                                                    type="button"
                                                    disabled={!selectedDate}
                                                    onClick={() =>
                                                        setQuantity((value) =>
                                                            Math.min(20, value + 1),
                                                        )
                                                    }
                                                    className="grid h-11 w-11 place-items-center rounded-full bg-emerald-600 text-xl font-black text-white shadow-lg shadow-emerald-600/20 transition hover:bg-emerald-700 disabled:opacity-40"
                                                >
                                                    +
                                                </button>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mx-auto mt-6 max-w-2xl rounded-2xl bg-slate-50 p-5">
                                        <div className="space-y-3 text-sm">
                                            <div className="flex justify-between">
                                                <span className="text-slate-500">Harga tiket</span>
                                                <span className="font-bold">
                                                    {rupiah(normalPrice)} × {quantity}
                                                </span>
                                            </div>
                                            <div className="flex justify-between">
                                                <span className="text-slate-500">
                                                    Diskon{promotion ? ` (${promotion.value}%)` : ''}
                                                </span>
                                                <span className="font-bold text-orange-600">
                                                    - {rupiah(discountTotal)}
                                                </span>
                                            </div>
                                            <div className="border-t border-slate-200 pt-3">
                                                <div className="flex items-end justify-between">
                                                    <span className="font-black">Total</span>
                                                    <span className="text-2xl font-black text-emerald-700">
                                                        {rupiah(total)}
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="mx-auto mt-6 max-w-2xl">
                                        <button
                                            type="button"
                                            disabled={!canContinue}
                                            onClick={startCheckout}
                                            className="w-full rounded-2xl bg-orange-500 px-6 py-4 text-sm font-black text-white shadow-xl shadow-orange-500/20 transition hover:-translate-y-0.5 hover:bg-orange-600 disabled:cursor-not-allowed disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none"
                                        >
                                            CHECKOUT
                                        </button>
                                    </div>

                                    <p className="mt-5 text-center text-xs text-slate-400">
                                        Sudah melakukan reservasi?{' '}
                                        <button
                                            type="button"
                                            className="font-bold text-emerald-700 hover:underline"
                                        >
                                            Cek status reservasi
                                        </button>
                                    </p>
                                </section>
                            </div>
                        </>
                    )}

                    {step === 2 && (
                        <section className="mx-auto max-w-2xl">
                            <div className="mb-7 text-center">
                                <h1 className="text-3xl font-black">Data Diri Pemesan</h1>
                                <p className="mt-2 text-sm text-slate-500">
                                    Isi data dengan benar. Kode booking dan e-ticket akan
                                    dikirim ke email Anda.
                                </p>
                            </div>

                            <div className="rounded-[28px] bg-white p-6 shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70 sm:p-8">
                                <div className="space-y-5">
                                    {[
                                        ['name', 'Nama', 'Contoh: Budi Santoso', 'text'],
                                        ['phone', 'Nomor Telepon', '08123456789', 'tel'],
                                        ['email', 'Email', 'nama@email.com', 'email'],
                                        ['birthDate', 'Tanggal Lahir', '', 'date'],
                                    ].map(([field, label, placeholder, type]) => (
                                        <label key={field} className="block">
                                            <span className="mb-2 block text-sm font-bold text-slate-700">
                                                {label}
                                            </span>
                                            <input
                                                type={type}
                                                value={customer[field as keyof Customer]}
                                                onChange={(event) =>
                                                    updateCustomer(
                                                        field as keyof Customer,
                                                        event.target.value,
                                                    )
                                                }
                                                placeholder={placeholder}
                                                className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none transition placeholder:text-slate-300 focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                            />
                                        </label>
                                    ))}

                                    <label className="block">
                                        <span className="mb-2 block text-sm font-bold text-slate-700">
                                            Provinsi
                                        </span>
                                        <select
                                            value={customer.province}
                                            onChange={(event) =>
                                                updateCustomer('province', event.target.value)
                                            }
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        >
                                            <option value="">Pilih Provinsi</option>
                                            <option>Jawa Tengah</option>
                                            <option>Jawa Barat</option>
                                            <option>DKI Jakarta</option>
                                            <option>Jawa Timur</option>
                                            <option>DI Yogyakarta</option>
                                            <option>Sumatera Utara</option>
                                            <option>Sumatera Barat</option>
                                            <option>Bali</option>
                                        </select>
                                    </label>

                                    <label className="block">
                                        <span className="mb-2 block text-sm font-bold text-slate-700">
                                            Kota/Kabupaten
                                        </span>
                                        <select
                                            value={customer.city}
                                            onChange={(event) =>
                                                updateCustomer('city', event.target.value)
                                            }
                                            className="w-full rounded-xl border border-slate-200 bg-white px-4 py-3.5 text-sm outline-none focus:border-emerald-500 focus:ring-4 focus:ring-emerald-500/10"
                                        >
                                            <option value="">Pilih Kota/Kabupaten</option>
                                            <option>Semarang</option>
                                            <option>Kendal</option>
                                            <option>Demak</option>
                                            <option>Ungaran</option>
                                            <option>Magelang</option>
                                            <option>Surakarta</option>
                                        </select>
                                    </label>
                                </div>

                                <div className="mt-7 flex gap-3">
                                    <button
                                        type="button"
                                        onClick={() => setStep(1)}
                                        className="flex-1 rounded-xl border border-slate-200 px-5 py-3.5 text-sm font-black text-slate-600 hover:bg-slate-50"
                                    >
                                        KEMBALI
                                    </button>
                                    <button
                                        type="button"
                                        disabled={!customerValid}
                                        onClick={continueCustomer}
                                        className="flex-1 rounded-xl bg-emerald-600 px-5 py-3.5 text-sm font-black text-white shadow-lg shadow-emerald-600/20 disabled:bg-slate-200 disabled:text-slate-400 disabled:shadow-none"
                                    >
                                        SELANJUTNYA
                                    </button>
                                </div>
                            </div>
                        </section>
                    )}

                    {step === 4 && (
                        <section className="mx-auto max-w-lg">
                            <div className="mb-7 text-center">
                                <h1 className="text-3xl font-black">Ringkasan Pesanan</h1>
                                <p className="mt-2 text-sm text-slate-500">
                                    Periksa kembali semua data sebelum melakukan pembayaran.
                                </p>
                            </div>

                            <div className="overflow-hidden rounded-[28px] bg-white shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70">
                                <div className="bg-emerald-700 p-6 text-white">
                                    <div className="text-xs font-bold uppercase tracking-[0.2em] text-emerald-200">
                                        Dusun Semilir
                                    </div>
                                    <div className="mt-2 text-xl font-black">
                                        Ringkasan Pesanan
                                    </div>
                                    <div className="mt-1 text-sm text-emerald-100">
                                        {selectedDate && formatLongDate(selectedDay!)}
                                    </div>
                                </div>

                                <div className="space-y-5 p-6">
                                    <div className="grid grid-cols-2 gap-4 text-sm">
                                        <div>
                                            <div className="text-xs text-slate-400">Nama</div>
                                            <div className="mt-1 font-bold">{customer.name}</div>
                                        </div>
                                        <div>
                                            <div className="text-xs text-slate-400">Telepon</div>
                                            <div className="mt-1 font-bold">{customer.phone}</div>
                                        </div>
                                        <div className="col-span-2">
                                            <div className="text-xs text-slate-400">Email</div>
                                            <div className="mt-1 font-bold break-all">
                                                {customer.email}
                                            </div>
                                        </div>
                                    </div>

                                    <div className="border-y border-dashed border-slate-200 py-5">
                                        <div className="flex items-start justify-between gap-4">
                                            <div>
                                                <div className="font-black">
                                                    {selectedTicket?.name ?? product.name}
                                                </div>
                                                <div className="mt-1 text-xs text-slate-400">
                                                    {quantity} tiket ·{' '}
                                                    {isWeekend ? 'Weekend' : 'Weekday'}
                                                </div>
                                            </div>
                                            <div className="text-right font-black">
                                                {rupiah(total)}
                                            </div>
                                        </div>

                                        {promotion && (
                                            <div className="mt-3 flex justify-between text-sm text-orange-600">
                                                <span>{promotion.name}</span>
                                                <span>- {rupiah(discountTotal)}</span>
                                            </div>
                                        )}
                                    </div>

                                    <div className="flex items-end justify-between">
                                        <span className="font-black">TOTAL</span>
                                        <span className="text-2xl font-black text-emerald-700">
                                            {rupiah(total)}
                                        </span>
                                    </div>

                                    <button
                                        type="button"
                                        onClick={startPayment}
                                        className="w-full rounded-2xl bg-orange-500 px-5 py-4 text-sm font-black text-white shadow-xl shadow-orange-500/20 hover:bg-orange-600"
                                    >
                                        BAYAR SEKARANG
                                    </button>

                                    <button
                                        type="button"
                                        onClick={() => setStep(2)}
                                        className="w-full rounded-xl px-5 py-3 text-sm font-bold text-slate-500 hover:bg-slate-50"
                                    >
                                        Kembali ke data pemesan
                                    </button>
                                </div>
                            </div>
                        </section>
                    )}

                    {step === 5 && (
                        <section className="mx-auto max-w-xl">
                            <div className="mb-7 text-center">
                                <span className="inline-flex rounded-full bg-orange-100 px-4 py-2 text-xs font-black text-orange-600">
                                    MENUNGGU PEMBAYARAN
                                </span>
                                <h1 className="mt-4 text-3xl font-black">Pembayaran QRIS</h1>
                                <p className="mt-2 text-sm text-slate-500">
                                    Scan QRIS menggunakan mobile banking atau e-wallet Anda.
                                </p>
                            </div>

                            <div className="rounded-[28px] bg-white p-6 text-center shadow-xl shadow-slate-900/5 ring-1 ring-slate-200/70 sm:p-8">
                                <div className="mx-auto max-w-sm rounded-2xl border border-slate-100 bg-slate-50 p-5">
                                    <div className="mb-4 text-sm font-bold text-slate-600">
                                        Total Pembayaran
                                    </div>
                                    <div className="text-3xl font-black text-emerald-700">
                                        {rupiah(total)}
                                    </div>

                                    <div className="mx-auto my-6 grid aspect-square max-w-[270px] place-items-center rounded-2xl bg-white p-5 shadow-sm">
                                        {/* Ganti placeholder ini dengan URL QRIS dari backend/Espay */}
                                        <div className="grid aspect-square w-full place-items-center border-8 border-slate-900 bg-white p-4">
                                            <div className="text-center">
                                                <div className="text-5xl">▦</div>
                                                <div className="mt-2 text-xs font-black tracking-widest">
                                                    QRIS
                                                </div>
                                                <div className="mt-1 text-[9px] text-slate-400">
                                                    MENUNGGU QR DARI ESPAY
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div className="rounded-xl bg-emerald-50 p-4 text-left text-xs leading-5 text-emerald-800">
                                        <div className="font-black">Cara pembayaran</div>
                                        <ol className="mt-2 list-decimal pl-4">
                                            <li>Buka mobile banking atau e-wallet.</li>
                                            <li>Pilih menu Scan QR / QRIS.</li>
                                            <li>Scan QR di atas.</li>
                                            <li>Pastikan nominal pembayaran benar.</li>
                                        </ol>
                                    </div>
                                </div>

                                <div className="mt-6 rounded-2xl border border-orange-100 bg-orange-50 p-4 text-sm text-orange-800">
                                    <div className="font-black">Status</div>
                                    <div className="mt-1">
                                        {paymentStarted
                                            ? 'Menunggu konfirmasi pembayaran dari Espay...'
                                            : 'Menyiapkan pembayaran...'}
                                    </div>
                                </div>

                                <div className="mt-6 text-xs text-slate-400">
                                    Setelah pembayaran berhasil, e-ticket akan dibuat secara
                                    otomatis.
                                </div>
                            </div>
                        </section>
                    )}
                </main>

                <footer className="relative z-10 border-t border-emerald-100 bg-white/70 py-8 text-center text-xs text-slate-400">
                    © {new Date().getFullYear()} Dusun Semilir · Tiket Online
                </footer>
            </div>

            {showDateConfirmation && selectedDate && (
                <Modal>
                    <div className="text-center">
                        <div className="mx-auto grid h-36 w-36 place-items-center rounded-full bg-emerald-50 text-7xl">
                            🐊
                        </div>
                        <div className="mt-5 text-xs font-bold uppercase tracking-[0.2em] text-emerald-600">
                            Konfirmasi
                        </div>
                        <h2 className="mt-2 text-2xl font-black">
                            Tanggal Kedatangan
                        </h2>
                        <div className="mt-2 text-lg font-black">
                            {formatLongDate(selectedDay!)}
                        </div>
                        <p className="mx-auto mt-4 max-w-md text-sm leading-6 text-slate-500">
                            Silakan pastikan tanggal sudah benar sebelum melanjutkan
                            pengisian data pemesan.
                        </p>

                        <div className="mt-7 flex justify-center gap-3">
                            <button
                                type="button"
                                onClick={() => setShowDateConfirmation(false)}
                                className="rounded-xl px-6 py-3 text-sm font-black text-orange-500 hover:bg-orange-50"
                            >
                                CANCEL
                            </button>
                            <button
                                type="button"
                                onClick={confirmDate}
                                className="rounded-xl bg-emerald-600 px-7 py-3 text-sm font-black text-white shadow-lg shadow-emerald-600/20 hover:bg-emerald-700"
                            >
                                NEXT
                            </button>
                        </div>
                    </div>
                </Modal>
            )}

            {showEmailConfirmation && (
                <Modal>
                    <div>
                        <div className="pr-8 text-xl font-black">
                            Apakah alamat email {customer.email} sudah benar?
                        </div>
                        <p className="mt-4 text-sm leading-6 text-slate-500">
                            Kode booking akan dikirim via email. Kesalahan penulisan
                            alamat dapat menyebabkan Anda tidak menerima kode booking
                            dari kami.
                        </p>

                        <div className="mt-7 flex justify-end gap-3">
                            <button
                                type="button"
                                onClick={() => setShowEmailConfirmation(false)}
                                className="rounded-xl px-5 py-3 text-sm font-black text-emerald-600 hover:bg-emerald-50"
                            >
                                CANCEL
                            </button>
                            <button
                                type="button"
                                onClick={confirmCustomer}
                                className="rounded-xl px-5 py-3 text-sm font-black text-emerald-600 hover:bg-emerald-50"
                            >
                                NEXT
                            </button>
                        </div>
                    </div>
                </Modal>
            )}
        </>
    );
}
