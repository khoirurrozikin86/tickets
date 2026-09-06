import { router } from '@inertiajs/react';
import axios from 'axios';
import { useEffect, useMemo, useState } from 'react';

import PublicLayout from '@/layouts/PublicLayout';
import { formatRupiah } from '@/lib/format';

interface Product {
    id: number;
    name: string;
    slug: string;
    description?: string | null;
}

interface VoucherResult {
    code: string;
    name: string;
    type: string;
    value: number;
}

interface Props {
    product: Product;

    selectedDate: string;

    dayType: string | null;

    price: number | null;

    priceError?: string | null;

    minDate: string;

    settings: Record<
        string,
        string | null | undefined
    >;
}

const DAY_TYPE_LABEL: Record<string, string> = {
    WEEKDAY: 'Weekday',
    WEEKEND: 'Weekend',
    HOLIDAY: 'Holiday',
};

const MONTH_NAMES = [
    'Januari',
    'Februari',
    'Maret',
    'April',
    'Mei',
    'Juni',
    'Juli',
    'Agustus',
    'September',
    'Oktober',
    'November',
    'Desember',
];

export default function TicketCheckout({
    product,
    selectedDate,
    dayType,
    price,
    priceError,
    minDate,
    settings,
}: Props) {
    /*
    |--------------------------------------------------------------------------
    | State
    |--------------------------------------------------------------------------
    */

    const [date, setDate] =
        useState(selectedDate);

    const [quantity, setQuantity] =
        useState(0);

    const [agree, setAgree] =
        useState(false);

    const [voucher, setVoucher] =
        useState('');

    const [voucherApplied, setVoucherApplied] =
        useState<VoucherResult | null>(null);

    const [discountAmount, setDiscountAmount] =
        useState(0);

    const [voucherMessage, setVoucherMessage] =
        useState('');

    const [voucherError, setVoucherError] =
        useState('');

    const [voucherLoading, setVoucherLoading] =
        useState(false);

    const [loading, setLoading] =
        useState(false);


    const [showDateConfirmation, setShowDateConfirmation] = useState(false);


    /*
    |--------------------------------------------------------------------------
    | Calendar state
    |--------------------------------------------------------------------------
    */

    const initialCalendar = useMemo(() => {
        const value = new Date(
            `${selectedDate}T00:00:00`
        );

        return {
            year: value.getFullYear(),
            month: value.getMonth(),
        };
    }, [selectedDate]);

    const [calendarYear, setCalendarYear] =
        useState(initialCalendar.year);

    const [calendarMonth, setCalendarMonth] =
        useState(initialCalendar.month);


    /*
    |--------------------------------------------------------------------------
    | Update calendar when Laravel selected date changes
    |--------------------------------------------------------------------------
    */

    useEffect(() => {
        const selected = new Date(
            `${selectedDate}T00:00:00`
        );

        setDate(selectedDate);

        setCalendarYear(
            selected.getFullYear()
        );

        setCalendarMonth(
            selected.getMonth()
        );
    }, [selectedDate]);


    /*
    |--------------------------------------------------------------------------
    | Reset voucher ketika tanggal / quantity berubah
    |--------------------------------------------------------------------------
    |
    | Voucher sebelumnya belum tentu masih memenuhi
    | minimal pembelian setelah quantity berubah.
    |
    */

    useEffect(() => {
        if (!voucherApplied) {
            return;
        }

        setVoucherApplied(null);
        setDiscountAmount(0);
        setVoucherMessage(
            'Jumlah tiket berubah. Silakan gunakan kembali voucher.'
        );
    }, [quantity, date]);


    /*
    |--------------------------------------------------------------------------
    | Calendar days
    |--------------------------------------------------------------------------
    */

    const calendarDays = useMemo(() => {
        const firstDay = new Date(
            calendarYear,
            calendarMonth,
            1
        );

        const lastDay = new Date(
            calendarYear,
            calendarMonth + 1,
            0
        );

        /*
         * Sunday = 0
         * Monday = 1
         *
         * Kita ubah menjadi:
         *
         * Monday = 0
         * ...
         * Sunday = 6
         */

        const startDay =
            firstDay.getDay() === 0
                ? 6
                : firstDay.getDay() - 1;

        const totalDays =
            lastDay.getDate();

        const previousMonthLastDay =
            new Date(
                calendarYear,
                calendarMonth,
                0
            ).getDate();

        const days: Array<{
            day: number;
            currentMonth: boolean;
            date: string;
        }> = [];

        /*
         * Previous month
         */

        for (
            let i = startDay - 1;
            i >= 0;
            i--
        ) {
            const day =
                previousMonthLastDay - i;

            const previousMonth =
                calendarMonth === 0
                    ? 11
                    : calendarMonth - 1;

            const previousYear =
                calendarMonth === 0
                    ? calendarYear - 1
                    : calendarYear;

            days.push({
                day,
                currentMonth: false,
                date: makeDate(
                    previousYear,
                    previousMonth,
                    day
                ),
            });
        }


        /*
         * Current month
         */

        for (
            let day = 1;
            day <= totalDays;
            day++
        ) {
            days.push({
                day,
                currentMonth: true,
                date: makeDate(
                    calendarYear,
                    calendarMonth,
                    day
                ),
            });
        }


        /*
         * Next month
         */

        let nextDay = 1;

        while (days.length < 42) {
            const nextMonth =
                calendarMonth === 11
                    ? 0
                    : calendarMonth + 1;

            const nextYear =
                calendarMonth === 11
                    ? calendarYear + 1
                    : calendarYear;

            days.push({
                day: nextDay,
                currentMonth: false,
                date: makeDate(
                    nextYear,
                    nextMonth,
                    nextDay
                ),
            });

            nextDay++;
        }

        return days;
    }, [
        calendarYear,
        calendarMonth,
    ]);


    /*
    |--------------------------------------------------------------------------
    | Previous month
    |--------------------------------------------------------------------------
    */

    function previousMonth() {
        if (
            calendarYear ===
            new Date().getFullYear() &&
            calendarMonth ===
            new Date().getMonth()
        ) {
            return;
        }

        if (calendarMonth === 0) {
            setCalendarMonth(11);
            setCalendarYear(
                (year) => year - 1
            );
        } else {
            setCalendarMonth(
                (month) => month - 1
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Next month
    |--------------------------------------------------------------------------
    */

    function nextMonth() {
        if (calendarMonth === 11) {
            setCalendarMonth(0);
            setCalendarYear(
                (year) => year + 1
            );
        } else {
            setCalendarMonth(
                (month) => month + 1
            );
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Select date
    |--------------------------------------------------------------------------
    */

    function handleDateSelect(
        selected: string
    ) {
        if (selected < minDate) {
            return;
        }

        if (selected === date) {
            return;
        }

        /*
         * Voucher dibatalkan karena harga
         * bisa berubah berdasarkan tanggal.
         */

        setVoucherApplied(null);
        setDiscountAmount(0);
        setVoucherMessage('');
        setVoucherError('');

        setDate(selected);

        setLoading(true);

        router.get(
            `/tickets/${product.slug}`,
            {
                date: selected,
            },
            {
                preserveState: true,
                preserveScroll: true,
                replace: true,

                onFinish: () => {
                    setLoading(false);
                },
            }
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Quantity
    |--------------------------------------------------------------------------
    */

    function decreaseQuantity() {
        setQuantity((current) =>
            Math.max(
                0,
                current - 1
            )
        );
    }


    function increaseQuantity() {
        setQuantity((current) =>
            Math.min(
                20,
                current + 1
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | Subtotal
    |--------------------------------------------------------------------------
    */

    const subtotal =
        price !== null
            ? price * quantity
            : 0;


    /*
    |--------------------------------------------------------------------------
    | Total
    |--------------------------------------------------------------------------
    */

    const total = Math.max(
        0,
        subtotal - discountAmount
    );


    /*
    |--------------------------------------------------------------------------
    | Apply Voucher
    |--------------------------------------------------------------------------
    */

    async function handleVoucher() {
        const code =
            voucher.trim().toUpperCase();

        setVoucherError('');
        setVoucherMessage('');

        if (!code) {
            setVoucherError(
                'Masukkan kode voucher terlebih dahulu.'
            );

            return;
        }

        if (quantity <= 0) {
            setVoucherError(
                'Pilih minimal 1 tiket terlebih dahulu.'
            );

            return;
        }

        if (!price) {
            setVoucherError(
                'Harga tiket belum tersedia.'
            );

            return;
        }

        setVoucherLoading(true);

        try {
            const response =
                await axios.post(
                    `/tickets/${product.slug}/voucher`,
                    {
                        date,
                        quantity,
                        code,
                    }
                );

            const data =
                response.data;

            if (!data.success) {
                setVoucherApplied(null);
                setDiscountAmount(0);

                setVoucherError(
                    data.message ??
                    'Voucher tidak dapat digunakan.'
                );

                return;
            }

            setVoucherApplied(
                data.voucher
            );

            setDiscountAmount(
                Number(
                    data.discount_amount ?? 0
                )
            );

            setVoucherMessage(
                `Voucher ${data.voucher.code} berhasil digunakan.`
            );

        } catch (error: any) {
            setVoucherApplied(null);
            setDiscountAmount(0);

            const message =
                error?.response?.data?.message ??
                'Voucher tidak valid atau tidak dapat digunakan.';

            setVoucherError(message);

        } finally {
            setVoucherLoading(false);
        }
    }


    /*
    |--------------------------------------------------------------------------
    | Remove Voucher
    |--------------------------------------------------------------------------
    */

    function removeVoucher() {
        setVoucherApplied(null);
        setDiscountAmount(0);
        setVoucher('');
        setVoucherMessage('');
        setVoucherError('');
    }


    /*
    |--------------------------------------------------------------------------
    | Checkout
    |--------------------------------------------------------------------------
    */

    function handleCheckout() {
        if (
            quantity <= 0 ||
            price === null ||
            !agree ||
            loading
        ) {
            return;
        }

        setShowDateConfirmation(true);
    }

    function handleConfirmCheckout() {
        setShowDateConfirmation(false);

        router.get('/checkout', {
            product: product.slug,
            date,
            quantity,
            voucher: voucherApplied?.code ?? '',
        });
    }


    /*
    |--------------------------------------------------------------------------
    | Render
    |--------------------------------------------------------------------------
    */

    return (
        <PublicLayout
            settings={settings}
        >
            <div className="min-h-screen bg-[#f8f8f8]">

                <div className="mx-auto max-w-[1800px] px-6 pb-10 pt-28">

                    <div className="grid grid-cols-1 gap-12 lg:grid-cols-[0.72fr_1.45fr]">

                        {/* =================================================
                            LEFT
                        ================================================= */}

                        <div className="rounded-[30px] border border-gray-200 bg-white px-8 py-8 shadow-[0_2px_6px_rgba(0,0,0,0.10)]">

                            <h1 className="text-center text-[26px] font-bold text-[#171717]">
                                Tanggal Kedatangan
                            </h1>


                            {/* Calendar Header */}

                            <div className="mt-12 flex items-center justify-between">

                                <button
                                    type="button"
                                    onClick={
                                        previousMonth
                                    }
                                    className="flex h-9 w-9 items-center justify-center rounded-full text-gray-300 transition hover:bg-gray-50 hover:text-gray-600"
                                >
                                    <span className="text-3xl leading-none">
                                        ‹
                                    </span>
                                </button>


                                <div className="text-[20px] font-medium text-gray-800">
                                    {
                                        MONTH_NAMES[
                                        calendarMonth
                                        ]
                                    }
                                </div>


                                <button
                                    type="button"
                                    onClick={
                                        nextMonth
                                    }
                                    className="flex h-9 w-9 items-center justify-center rounded-full text-gray-300 transition hover:bg-gray-50 hover:text-gray-600"
                                >
                                    <span className="text-3xl leading-none">
                                        ›
                                    </span>
                                </button>


                                <select
                                    value={
                                        calendarYear
                                    }
                                    onChange={(
                                        event
                                    ) =>
                                        setCalendarYear(
                                            Number(
                                                event
                                                    .target
                                                    .value
                                            )
                                        )
                                    }
                                    className="ml-2 rounded-full border border-gray-300 bg-white px-4 py-2 text-sm text-gray-700 outline-none"
                                >
                                    {Array.from(
                                        {
                                            length: 6,
                                        },
                                        (
                                            _,
                                            index
                                        ) =>
                                            new Date().getFullYear() +
                                            index
                                    ).map(
                                        (
                                            year
                                        ) => (
                                            <option
                                                key={
                                                    year
                                                }
                                                value={
                                                    year
                                                }
                                            >
                                                {
                                                    year
                                                }
                                            </option>
                                        )
                                    )}
                                </select>

                            </div>


                            {/* Weekday */}

                            <div className="mt-6 grid grid-cols-7 text-center">

                                {[
                                    'Min',
                                    'Sen',
                                    'Sel',
                                    'Rab',
                                    'Kam',
                                    'Jum',
                                    'Sab',
                                ].map(
                                    (
                                        day
                                    ) => (
                                        <div
                                            key={
                                                day
                                            }
                                            className="py-2 text-[13px] font-medium text-[#a3acc0]"
                                        >
                                            {
                                                day
                                            }
                                        </div>
                                    )
                                )}

                            </div>


                            {/* Days */}

                            <div className="grid grid-cols-7 gap-y-2 text-center">

                                {calendarDays.map(
                                    (
                                        item,
                                        index
                                    ) => {
                                        const isSelected =
                                            item.date ===
                                            date;

                                        const isDisabled =
                                            item.date <
                                            minDate;

                                        return (
                                            <button
                                                key={`${item.date}-${index}`}
                                                type="button"
                                                disabled={
                                                    isDisabled
                                                }
                                                onClick={() =>
                                                    handleDateSelect(
                                                        item.date
                                                    )
                                                }
                                                className={`
                                                    mx-auto
                                                    flex
                                                    h-10
                                                    w-10
                                                    items-center
                                                    justify-center
                                                    rounded-full
                                                    text-[14px]
                                                    transition

                                                    ${isSelected
                                                        ? 'bg-[#13a77d] font-bold text-white shadow-md'
                                                        : item.currentMonth
                                                            ? isDisabled
                                                                ? 'cursor-not-allowed text-gray-200'
                                                                : 'text-gray-800 hover:bg-emerald-50 hover:text-emerald-700'
                                                            : 'text-gray-300'
                                                    }
                                                `}
                                            >
                                                {
                                                    item.day
                                                }
                                            </button>
                                        );
                                    }
                                )}

                            </div>


                            {/* Selected Date */}

                            <div className="mt-8 border-t border-gray-100 pt-6">

                                <p className="text-xs uppercase tracking-wide text-gray-400">
                                    Tanggal dipilih
                                </p>

                                <p className="mt-1 text-base font-bold text-gray-800">
                                    {
                                        formatLongDate(
                                            date
                                        )
                                    }
                                </p>

                                {dayType && (
                                    <p className="mt-1 text-sm text-[#13a77d]">
                                        Tarif{' '}
                                        <strong>
                                            {
                                                DAY_TYPE_LABEL[
                                                dayType
                                                ] ??
                                                dayType
                                            }
                                        </strong>
                                    </p>
                                )}

                            </div>

                        </div>


                        {/* =================================================
                            RIGHT
                        ================================================= */}

                        <div className="rounded-[30px] border border-gray-200 bg-white px-10 py-8 shadow-[0_2px_6px_rgba(0,0,0,0.10)]">

                            <h2 className="text-center text-[28px] font-bold text-[#171717]">
                                Ticket Types
                            </h2>


                            {/* Ticket */}

                            <div className="mx-auto mt-16 max-w-[760px] rounded-[12px] border border-gray-100 bg-white px-7 py-6 shadow-[0_2px_5px_rgba(0,0,0,0.15)]">

                                <div className="flex items-center justify-between gap-5">

                                    {/* Name */}

                                    <div className="min-w-0 flex-1">

                                        <h3 className="text-[20px] font-bold text-gray-900">
                                            {
                                                product.name
                                            }
                                        </h3>

                                        <p className="mt-1 text-[13px] text-gray-700">
                                            {
                                                dayType
                                                    ? `Promo ${DAY_TYPE_LABEL[
                                                    dayType
                                                    ] ??
                                                    dayType
                                                    }`
                                                    : 'Harga tiket'
                                            }
                                        </p>

                                    </div>


                                    {/* Quantity */}

                                    <div className="flex items-center gap-5">

                                        <button
                                            type="button"
                                            onClick={
                                                decreaseQuantity
                                            }
                                            disabled={
                                                quantity <=
                                                0
                                            }
                                            className="flex h-9 w-9 items-center justify-center rounded-full bg-[#159f79] text-xl font-bold leading-none text-white shadow-md transition hover:bg-[#108b69] disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            −
                                        </button>


                                        <span className="w-4 text-center text-[22px] font-bold text-gray-900">
                                            {
                                                quantity
                                            }
                                        </span>


                                        <button
                                            type="button"
                                            onClick={
                                                increaseQuantity
                                            }
                                            disabled={
                                                quantity >=
                                                20
                                            }
                                            className="flex h-9 w-9 items-center justify-center rounded-full bg-[#159f79] text-xl font-bold leading-none text-white shadow-md transition hover:bg-[#108b69] disabled:opacity-50"
                                        >
                                            +
                                        </button>

                                    </div>


                                    {/* Price */}

                                    <div className="w-[150px] text-right">

                                        <span className="text-[16px] font-medium text-gray-900">
                                            {price !==
                                                null
                                                ? formatRupiah(
                                                    price
                                                )
                                                : 'Rp. 0'}
                                        </span>

                                    </div>

                                </div>

                            </div>


                            {/* Loading */}

                            {loading && (
                                <div className="mx-auto mt-3 max-w-[760px] text-center text-xs text-gray-400">
                                    Menghitung harga...
                                </div>
                            )}


                            {/* Price Error */}

                            {priceError && (
                                <div className="mx-auto mt-4 max-w-[760px] rounded-lg bg-red-50 px-4 py-3 text-sm text-red-600">
                                    {
                                        priceError
                                    }
                                </div>
                            )}


                            {/* =================================================
                                VOUCHER
                            ================================================= */}

                            <div className="mx-auto mt-6 max-w-[760px]">

                                {!voucherApplied ? (
                                    <div className="flex gap-2">

                                        <input
                                            type="text"
                                            value={
                                                voucher
                                            }
                                            onChange={(
                                                event
                                            ) => {
                                                setVoucher(
                                                    event
                                                        .target
                                                        .value
                                                        .toUpperCase()
                                                );

                                                setVoucherError(
                                                    ''
                                                );

                                                setVoucherMessage(
                                                    ''
                                                );
                                            }}
                                            onKeyDown={(
                                                event
                                            ) => {
                                                if (
                                                    event.key ===
                                                    'Enter'
                                                ) {
                                                    handleVoucher();
                                                }
                                            }}
                                            placeholder="Kode voucher"
                                            className="h-10 flex-1 rounded-lg border border-gray-200 px-4 text-sm uppercase outline-none transition focus:border-[#159f79] focus:ring-1 focus:ring-[#159f79]"
                                        />

                                        <button
                                            type="button"
                                            onClick={
                                                handleVoucher
                                            }
                                            disabled={
                                                voucherLoading ||
                                                quantity <=
                                                0 ||
                                                price ===
                                                null
                                            }
                                            className="rounded-lg bg-[#159f79] px-5 text-sm font-semibold text-white transition hover:bg-[#108b69] disabled:cursor-not-allowed disabled:opacity-50"
                                        >
                                            {voucherLoading
                                                ? '...'
                                                : 'Gunakan'}
                                        </button>

                                    </div>
                                ) : (
                                    <div className="flex items-center justify-between rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3">

                                        <div>
                                            <p className="text-sm font-bold text-emerald-800">
                                                {
                                                    voucherApplied.code
                                                }
                                            </p>

                                            <p className="text-xs text-emerald-600">
                                                {
                                                    voucherApplied.name
                                                }
                                            </p>
                                        </div>

                                        <button
                                            type="button"
                                            onClick={
                                                removeVoucher
                                            }
                                            className="text-xs font-semibold text-red-500 hover:text-red-700"
                                        >
                                            Hapus
                                        </button>

                                    </div>
                                )}


                                {voucherError && (
                                    <p className="mt-2 text-xs text-red-500">
                                        {
                                            voucherError
                                        }
                                    </p>
                                )}


                                {voucherMessage &&
                                    !voucherError && (
                                        <p className="mt-2 text-xs text-emerald-600">
                                            {
                                                voucherMessage
                                            }
                                        </p>
                                    )}

                            </div>


                            {/* =================================================
                                TERM & CONDITION
                            ================================================= */}

                            <div className="mx-auto mt-6 max-w-[760px]">

                                <label className="flex cursor-pointer items-center gap-3 text-[14px] text-[#315fa9]">

                                    <input
                                        type="checkbox"
                                        checked={
                                            agree
                                        }
                                        onChange={(
                                            event
                                        ) =>
                                            setAgree(
                                                event
                                                    .target
                                                    .checked
                                            )
                                        }
                                        className="h-[19px] w-[19px] accent-[#159f79]"
                                    />

                                    <span>
                                        I agree with Term
                                        And Condition and
                                        Privacy Policy of
                                        Saloka Theme Park
                                    </span>

                                </label>

                            </div>


                            {/* =================================================
                                TOTAL
                            ================================================= */}

                            <div className="mx-auto mt-8 flex max-w-[760px] items-center justify-between gap-8">

                                <div className="text-left">

                                    <p className="text-[13px] font-medium text-gray-700">
                                        arrival date:
                                    </p>

                                    <p className="mt-1 text-[18px] font-bold text-gray-900">
                                        {
                                            formatLongDate(
                                                date
                                            )
                                        }
                                    </p>

                                </div>


                                <div className="flex items-center overflow-hidden rounded-full bg-white shadow-[0_2px_12px_rgba(0,0,0,0.18)]">

                                    <div className="px-5 py-3 text-[15px] font-medium text-gray-900">

                                        <div>
                                            Rp.{' '}
                                            {
                                                formatNumber(
                                                    total
                                                )
                                            }
                                        </div>

                                    </div>


                                    <button
                                        type="button"
                                        onClick={
                                            handleCheckout
                                        }
                                        disabled={
                                            quantity <=
                                            0 ||
                                            price ===
                                            null ||
                                            !agree ||
                                            loading
                                        }
                                        className={`
                                            h-[52px]
                                            min-w-[130px]
                                            rounded-full
                                            px-6
                                            text-[16px]
                                            font-bold
                                            transition

                                            ${quantity >
                                                0 &&
                                                price !==
                                                null &&
                                                agree &&
                                                !loading
                                                ? 'bg-[#159f79] text-white hover:bg-[#108b69]'
                                                : 'bg-[#dedede] text-[#a8a8a8]'
                                            }
                                        `}
                                    >
                                        CHECKOUT
                                    </button>

                                </div>

                            </div>


                            {/* =================================================
                                Reservation
                            ================================================= */}

                            <div className="mt-8 text-center text-[13px] text-gray-700">

                                <span>
                                    *telah melakukan
                                    reservasi? cek status
                                    reservasi{' '}
                                </span>

                                <a
                                    href="/reservation"
                                    className="font-medium text-[#315fa9] underline"
                                >
                                    di sini
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            </div>








            {/* =========================
            POPUP KONFIRMASI TANGGAL
            ========================= */}
            {showDateConfirmation && (
                <div className="fixed inset-0 z-[9999] flex items-center justify-center bg-black/50 px-4">
                    <div className="w-full max-w-[585px] rounded-2xl bg-white px-6 py-8 shadow-2xl sm:px-10">

                        <div className="mb-5 flex justify-center">
                            <img
                                src="/images/osil.png"
                                alt="OSIL"
                                className="h-[230px] w-auto object-contain"
                            />
                        </div>

                        <div className="text-center">

                            <h2 className="text-[18px] font-bold text-gray-700">
                                Konfirmasi Tanggal Kedatangan
                            </h2>

                            <p className="mt-1 text-[17px] font-bold italic text-gray-800">
                                {formatLongDate(date)}
                            </p>

                            <p className="mt-4 text-[14px] leading-6 text-gray-700">
                                Silakan pastikan tanggal sudah benar sebelum
                                melanjutkan pengisian data pemesan.
                            </p>

                        </div>

                        <div className="mt-5 flex items-center justify-center gap-8">

                            <button
                                type="button"
                                onClick={() =>
                                    setShowDateConfirmation(false)
                                }
                                className="font-bold text-[#f5c400] transition hover:opacity-70"
                            >
                                CANCEL
                            </button>

                            <button
                                type="button"
                                onClick={handleConfirmCheckout}
                                className="rounded-full bg-[#159f79] px-5 py-2 text-sm font-bold text-white shadow-md transition hover:bg-[#128765]"
                            >
                                NEXT
                            </button>

                        </div>

                    </div>
                </div>
            )}




        </PublicLayout>
    );
}


/*
|--------------------------------------------------------------------------
| Make date
|--------------------------------------------------------------------------
*/

function makeDate(
    year: number,
    month: number,
    day: number
): string {
    const date = new Date(
        year,
        month,
        day
    );

    const y =
        date.getFullYear();

    const m = String(
        date.getMonth() + 1
    ).padStart(2, '0');

    const d = String(
        date.getDate()
    ).padStart(2, '0');

    return `${y}-${m}-${d}`;
}


/*
|--------------------------------------------------------------------------
| Format long date
|--------------------------------------------------------------------------
*/

function formatLongDate(
    date: string
): string {
    if (!date) {
        return '-';
    }

    return new Intl.DateTimeFormat(
        'id-ID',
        {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric',
        }
    ).format(
        new Date(`${date}T00:00:00`)
    );
}


/*
|--------------------------------------------------------------------------
| Format number
|--------------------------------------------------------------------------
*/

function formatNumber(
    value: number
): string {
    return new Intl.NumberFormat(
        'id-ID'
    ).format(value);
}