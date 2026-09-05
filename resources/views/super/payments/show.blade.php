@extends('layouts.admin')

@section('title', 'Payment Detail')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Payment Detail
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Payment Information --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Payment Information
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="detail-label">
                                Payment Number
                            </div>

                            <div class="detail-value">
                                {{ $payment->payment_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Status
                            </div>

                            @php
                                $badges = [
                                    'PENDING' => 'warning',
                                    'PAID' => 'success',
                                    'FAILED' => 'danger',
                                    'EXPIRED' => 'secondary',
                                    'CANCELLED' => 'danger',
                                    'REFUNDED' => 'dark',
                                ];

                                $badgeClass = $badges[$payment->status] ?? 'secondary';
                            @endphp

                            <div>
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $payment->status }}
                                </span>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Gateway
                            </div>

                            <div class="detail-value">
                                {{ $payment->gateway ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Payment Method
                            </div>

                            <div class="detail-value">
                                {{ $payment->payment_method ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Payment Channel
                            </div>

                            <div class="detail-value">
                                {{ $payment->payment_channel ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Amount
                            </div>

                            <div class="detail-value amount">
                                Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Gateway Reference
                            </div>

                            <div class="detail-value text-break">
                                {{ $payment->gateway_reference ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Gateway Transaction ID
                            </div>

                            <div class="detail-value text-break">
                                {{ $payment->gateway_transaction_id ?: '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Created At
                            </div>

                            <div class="detail-value">
                                {{ $payment->created_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Expired At
                            </div>

                            <div class="detail-value">
                                {{ $payment->expired_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Paid At
                            </div>

                            <div class="detail-value">
                                {{ $payment->paid_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Cancelled At
                            </div>

                            <div class="detail-value">
                                {{ $payment->cancelled_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Customer --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Customer
                    </h6>
                </div>

                <div class="card-body">

                    @if ($payment->order)
                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="detail-label">
                                    Order Number
                                </div>

                                <div class="detail-value">

                                    <a href="{{ route('super.orders.show', $payment->order->id) }}" class="order-link">

                                        {{ $payment->order->order_number }}

                                    </a>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Customer Name
                                </div>

                                <div class="detail-value">
                                    {{ $payment->order->customer_name }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Email
                                </div>

                                <div class="detail-value text-break">
                                    {{ $payment->order->customer_email }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Phone
                                </div>

                                <div class="detail-value">
                                    {{ $payment->order->customer_phone }}
                                </div>

                            </div>

                        </div>
                    @else
                        <div class="text-muted">
                            Order tidak ditemukan.
                        </div>
                    @endif

                </div>

            </div>


            {{-- Order Items --}}
            @if ($payment->order && $payment->order->items->count())

                <div class="card mb-4">

                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            Order Items
                        </h6>
                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-bordered mb-0 align-middle">

                                <thead>

                                    <tr>

                                        <th>Produk</th>

                                        <th>Visit Date</th>

                                        <th>Qty</th>

                                        <th>Harga</th>

                                        <th>Subtotal</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($payment->order->items as $item)
                                        <tr>

                                            <td>
                                                {{ $item->product_name }}
                                            </td>

                                            <td>
                                                {{ $item->visit_date?->format('d/m/Y') ?? '-' }}
                                            </td>

                                            <td>
                                                {{ number_format($item->quantity) }}
                                            </td>

                                            <td>
                                                Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endif


            {{-- Discount --}}
            @if ($payment->order)
                <div class="card mb-4">

                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            Discount
                        </h6>
                    </div>

                    <div class="card-body">

                        <div class="row g-4">

                            <div class="col-md-4">

                                <div class="detail-label">
                                    Discount Code
                                </div>

                                <div class="detail-value">
                                    {{ $payment->order->discount_code ?: '-' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-label">
                                    Discount Amount
                                </div>

                                <div class="detail-value">
                                    Rp {{ number_format((float) $payment->order->discount_amount, 0, ',', '.') }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-label">
                                    Total Order
                                </div>

                                <div class="detail-value amount">
                                    Rp {{ number_format((float) $payment->order->total_amount, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif


            {{-- Tickets --}}
            @if ($payment->order && $payment->order->tickets->count())

                <div class="card mb-4">

                    <div class="card-header">

                        <h6 class="card-title mb-0">
                            Tickets
                        </h6>

                    </div>

                    <div class="card-body p-0">

                        <div class="table-responsive">

                            <table class="table table-bordered mb-0 align-middle">

                                <thead>

                                    <tr>

                                        <th>Ticket Number</th>

                                        <th>Produk</th>

                                        <th>Visit Date</th>

                                        <th>Status</th>

                                        <th>Used At</th>

                                    </tr>

                                </thead>

                                <tbody>

                                    @foreach ($payment->order->tickets as $ticket)
                                        @php

                                            $ticketBadges = [
                                                'ACTIVE' => 'success',
                                                'USED' => 'primary',
                                                'CANCELLED' => 'danger',
                                                'EXPIRED' => 'secondary',
                                            ];

                                            $ticketClass = $ticketBadges[$ticket->status] ?? 'secondary';

                                        @endphp

                                        <tr>

                                            <td>
                                                {{ $ticket->ticket_number }}
                                            </td>

                                            <td>
                                                {{ $ticket->product_name }}
                                            </td>

                                            <td>
                                                {{ $ticket->visit_date?->format('d/m/Y') ?? '-' }}
                                            </td>

                                            <td>

                                                <span class="badge bg-{{ $ticketClass }}">
                                                    {{ $ticket->status }}
                                                </span>

                                            </td>

                                            <td>
                                                {{ $ticket->used_at?->format('d/m/Y H:i:s') ?? '-' }}
                                            </td>

                                        </tr>
                                    @endforeach

                                </tbody>

                            </table>

                        </div>

                    </div>

                </div>

            @endif

        </div>


        {{-- RIGHT --}}
        <div class="col-lg-4">

            {{-- Summary --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="card-title mb-0">
                        Payment Summary
                    </h6>

                </div>

                <div class="card-body">

                    <div class="summary-row">

                        <span>Subtotal</span>

                        <strong>
                            Rp {{ number_format((float) ($payment->order->subtotal ?? 0), 0, ',', '.') }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>Discount</span>

                        <strong class="text-danger">

                            - Rp {{ number_format((float) ($payment->order->discount_amount ?? 0), 0, ',', '.') }}

                        </strong>

                    </div>


                    <hr>


                    <div class="summary-row total">

                        <span>Total</span>

                        <strong>
                            Rp {{ number_format((float) $payment->amount, 0, ',', '.') }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Payment Status --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="card-title mb-0">
                        Payment Status
                    </h6>

                </div>

                <div class="card-body text-center">

                    @if ($payment->status === 'PAID')
                        <div class="status-icon success">
                            <i data-feather="check-circle"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Payment Berhasil
                        </h5>

                        <p class="text-muted mb-0">
                            Pembayaran telah dikonfirmasi.
                        </p>
                    @elseif($payment->status === 'PENDING')
                        <div class="status-icon warning">
                            <i data-feather="clock"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Menunggu Pembayaran
                        </h5>

                        <p class="text-muted mb-0">
                            Pembayaran belum dikonfirmasi.
                        </p>
                    @elseif($payment->status === 'FAILED')
                        <div class="status-icon danger">
                            <i data-feather="x-circle"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Pembayaran Gagal
                        </h5>

                        <p class="text-muted mb-0">
                            Pembayaran tidak berhasil.
                        </p>
                    @elseif($payment->status === 'EXPIRED')
                        <div class="status-icon secondary">
                            <i data-feather="clock"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Pembayaran Expired
                        </h5>

                        <p class="text-muted mb-0">
                            Waktu pembayaran telah berakhir.
                        </p>
                    @else
                        <div class="status-icon">
                            <i data-feather="info"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            {{ $payment->status }}
                        </h5>
                    @endif

                </div>

            </div>


            {{-- Callback --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="card-title mb-0">
                        Gateway Callback
                    </h6>

                </div>

                <div class="card-body">

                    @if ($payment->callback_payload)
                        <pre class="callback-json">{{ json_encode($payment->callback_payload, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>
                    @else
                        <div class="text-muted small">
                            Belum ada callback dari gateway.
                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>



@endsection


@push('styles')
    <style>
        .detail-label {
            font-size: 12px;
            color: #8b8b8b;
            margin-bottom: 5px;
        }

        .detail-value {
            font-weight: 500;
            color: #343a40;
        }

        .detail-value.amount {
            font-size: 18px;
            font-weight: 700;
        }

        .order-link {
            color: #6571ff;
            text-decoration: none;
            font-weight: 600;
        }

        .order-link:hover {
            text-decoration: underline;
        }

        .summary-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 12px;
        }

        .summary-row.total {
            font-size: 17px;
            margin-bottom: 0;
        }

        .status-icon {
            width: 52px;
            height: 52px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            background: #f1f3f5;
        }

        .status-icon svg {
            width: 28px;
            height: 28px;
        }

        .status-icon.success {
            color: #198754;
            background: #e8f7ee;
        }

        .status-icon.warning {
            color: #ffc107;
            background: #fff8e1;
        }

        .status-icon.danger {
            color: #dc3545;
            background: #fdebec;
        }

        .status-icon.secondary {
            color: #6c757d;
            background: #f1f3f5;
        }

        .callback-json {
            background: #f8f9fa;
            border-radius: 6px;
            padding: 12px;
            font-size: 11px;
            max-height: 400px;
            overflow: auto;
            margin: 0;
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
