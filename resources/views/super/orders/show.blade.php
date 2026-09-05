@extends('layouts.admin')

@section('title', 'Order Detail')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Order Detail
            </li>
        </ol>
    </nav>
@endsection

@section('content')




    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">


            {{-- ORDER INFORMATION --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="mb-0">
                        Informasi Order
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <label class="text-muted small">
                                Order Number
                            </label>

                            <div class="fw-semibold">
                                {{ $order->order_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Tanggal Order
                            </label>

                            <div class="fw-semibold">
                                {{ $order->created_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Order Status
                            </label>

                            <div class="mt-1">

                                @php

                                    $statusBadges = [
                                        'PENDING' => 'warning',
                                        'PAID' => 'success',
                                        'COMPLETED' => 'primary',
                                        'CANCELLED' => 'danger',
                                        'EXPIRED' => 'secondary',
                                        'REFUNDED' => 'info',
                                    ];

                                    $statusClass = $statusBadges[$order->status] ?? 'secondary';

                                @endphp

                                <span class="badge bg-{{ $statusClass }}">
                                    {{ $order->status }}
                                </span>

                            </div>

                        </div>


                        <div class="col-md-6">

                            <label class="text-muted small">
                                Payment Status
                            </label>

                            <div class="mt-1">

                                @php

                                    $paymentBadges = [
                                        'UNPAID' => 'secondary',
                                        'PENDING' => 'warning',
                                        'PAID' => 'success',
                                        'FAILED' => 'danger',
                                        'EXPIRED' => 'secondary',
                                        'REFUNDED' => 'info',
                                    ];

                                    $paymentClass = $paymentBadges[$order->payment_status] ?? 'secondary';

                                @endphp

                                <span class="badge bg-{{ $paymentClass }}">
                                    {{ $order->payment_status }}
                                </span>

                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- CUSTOMER --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="mb-0">
                        Informasi Customer
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-4">

                            <label class="text-muted small">
                                Nama
                            </label>

                            <div class="fw-semibold">
                                {{ $order->customer_name }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Email
                            </label>

                            <div>
                                {{ $order->customer_email }}
                            </div>

                        </div>


                        <div class="col-md-4">

                            <label class="text-muted small">
                                Phone
                            </label>

                            <div>
                                {{ $order->customer_phone }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ORDER ITEMS --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="mb-0">
                        Item Tiket
                    </h6>
                </div>

                <div class="card-body p-0">

                    <div class="table-responsive">

                        <table class="table table-hover mb-0">

                            <thead>

                                <tr>

                                    <th>
                                        Produk
                                    </th>

                                    <th>
                                        Visit Date
                                    </th>

                                    <th>
                                        Day Type
                                    </th>

                                    <th class="text-center">
                                        Qty
                                    </th>

                                    <th class="text-end">
                                        Harga
                                    </th>

                                    <th class="text-end">
                                        Subtotal
                                    </th>

                                </tr>

                            </thead>

                            <tbody>

                                @forelse($order->items as $item)
                                    <tr>

                                        <td>

                                            <div class="fw-semibold">
                                                {{ $item->product_name }}
                                            </div>

                                        </td>

                                        <td>
                                            {{ $item->visit_date?->format('d/m/Y') ?? '-' }}
                                        </td>

                                        <td>

                                            @php

                                                $dayTypeClass = [
                                                    'WEEKDAY' => 'success',
                                                    'WEEKEND' => 'primary',
                                                    'HOLIDAY' => 'warning',
                                                ];

                                            @endphp

                                            <span class="badge bg-{{ $dayTypeClass[$item->day_type] ?? 'secondary' }}">
                                                {{ $item->day_type }}
                                            </span>

                                        </td>

                                        <td class="text-center">
                                            {{ $item->quantity }}
                                        </td>

                                        <td class="text-end">
                                            Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                        </td>

                                        <td class="text-end fw-semibold">
                                            Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                        </td>

                                    </tr>

                                @empty

                                    <tr>

                                        <td colspan="6" class="text-center text-muted py-4">

                                            Tidak ada item.

                                        </td>

                                    </tr>
                                @endforelse

                            </tbody>

                        </table>

                    </div>

                </div>

            </div>


            {{-- TICKETS --}}
            <div class="card mb-4">

                <div class="card-header">

                    <div class="d-flex justify-content-between align-items-center">

                        <h6 class="mb-0">
                            E-Ticket
                        </h6>

                        <span class="text-muted small">
                            {{ $order->tickets->count() }} Ticket
                        </span>

                    </div>

                </div>

                <div class="card-body">

                    @forelse($order->tickets as $ticket)
                        <div class="ticket-row">

                            <div>

                                <div class="fw-semibold">
                                    {{ $ticket->ticket_number }}
                                </div>

                                <small class="text-muted">
                                    {{ $ticket->product_name }}
                                    ·
                                    {{ $ticket->visit_date?->format('d/m/Y') }}
                                </small>

                            </div>


                            <div>

                                @php

                                    $ticketBadges = [
                                        'ACTIVE' => 'success',
                                        'USED' => 'primary',
                                        'CANCELLED' => 'danger',
                                        'EXPIRED' => 'secondary',
                                    ];

                                @endphp

                                <span class="badge bg-{{ $ticketBadges[$ticket->status] ?? 'secondary' }}">
                                    {{ $ticket->status }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted py-3">
                            Ticket belum diterbitkan.
                        </div>
                    @endforelse

                </div>

            </div>

        </div>


        {{-- RIGHT --}}
        <div class="col-lg-4">


            {{-- SUMMARY --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        Ringkasan Pembayaran
                    </h6>

                </div>

                <div class="card-body">

                    <div class="summary-row">

                        <span>
                            Subtotal
                        </span>

                        <strong>
                            Rp {{ number_format((float) $order->subtotal, 0, ',', '.') }}
                        </strong>

                    </div>


                    @if ($order->discount_amount > 0)

                        <div class="summary-row">

                            <span>
                                Discount

                                @if ($order->discount_code)
                                    <small class="text-muted">
                                        ({{ $order->discount_code }})
                                    </small>
                                @endif

                            </span>

                            <strong class="text-danger">
                                - Rp {{ number_format((float) $order->discount_amount, 0, ',', '.') }}
                            </strong>

                        </div>

                    @endif


                    <hr>


                    <div class="summary-total">

                        <span>
                            Total
                        </span>

                        <strong>
                            Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- PAYMENT --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="mb-0">
                        Payment
                    </h6>

                </div>

                <div class="card-body">

                    @forelse($order->payments as $payment)
                        <div class="mb-3">

                            <label class="text-muted small">
                                Payment Number
                            </label>

                            <div class="fw-semibold">
                                {{ $payment->payment_number }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="text-muted small">
                                Gateway
                            </label>

                            <div>
                                {{ $payment->gateway }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="text-muted small">
                                Method
                            </label>

                            <div>
                                {{ $payment->payment_method }}
                            </div>

                        </div>


                        <div class="mb-3">

                            <label class="text-muted small">
                                Amount
                            </label>

                            <div class="fw-semibold">
                                Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </div>

                        </div>


                        <div>

                            <label class="text-muted small">
                                Status
                            </label>

                            <div class="mt-1">

                                <span class="badge bg-{{ $payment->status === 'PAID' ? 'success' : 'warning' }}">
                                    {{ $payment->status }}
                                </span>

                            </div>

                        </div>

                    @empty

                        <div class="text-center text-muted">
                            Belum ada payment.
                        </div>
                    @endforelse

                </div>

            </div>


            {{-- TIMELINE --}}
            <div class="card">

                <div class="card-header">

                    <h6 class="mb-0">
                        Timeline
                    </h6>

                </div>

                <div class="card-body">

                    <div class="timeline-item">

                        <div class="timeline-dot"></div>

                        <div>

                            <strong>
                                Order dibuat
                            </strong>

                            <div class="text-muted small">
                                {{ $order->created_at?->format('d/m/Y H:i:s') }}
                            </div>

                        </div>

                    </div>


                    @if ($order->paid_at)
                        <div class="timeline-item">

                            <div class="timeline-dot"></div>

                            <div>

                                <strong>
                                    Payment berhasil
                                </strong>

                                <div class="text-muted small">
                                    {{ $order->paid_at->format('d/m/Y H:i:s') }}
                                </div>

                            </div>

                        </div>
                    @endif


                    @if ($order->completed_at)
                        <div class="timeline-item">

                            <div class="timeline-dot"></div>

                            <div>

                                <strong>
                                    Order selesai
                                </strong>

                                <div class="text-muted small">
                                    {{ $order->completed_at->format('d/m/Y H:i:s') }}
                                </div>

                            </div>

                        </div>
                    @endif


                    @if ($order->cancelled_at)
                        <div class="timeline-item">

                            <div class="timeline-dot"></div>

                            <div>

                                <strong>
                                    Order dibatalkan
                                </strong>

                                <div class="text-muted small">
                                    {{ $order->cancelled_at->format('d/m/Y H:i:s') }}
                                </div>

                            </div>

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>



@endsection


@push('styles')
    <style>
        .ticket-row {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 12px 0;

            border-bottom: 1px solid #eee;
        }

        .ticket-row:last-child {
            border-bottom: 0;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;

            margin-bottom: 12px;
        }

        .summary-total {
            display: flex;
            justify-content: space-between;
            align-items: center;

            font-size: 16px;
        }

        .timeline-item {
            position: relative;

            display: flex;
            gap: 12px;

            padding-bottom: 20px;
        }

        .timeline-item:last-child {
            padding-bottom: 0;
        }

        .timeline-dot {
            width: 9px;
            height: 9px;

            margin-top: 5px;

            border-radius: 50%;
            background: #6571ff;

            flex-shrink: 0;
        }
    </style>
@endpush


@push('scripts')
    <script>
        $(function() {
            feather.replace();
        });
    </script>
@endpush
