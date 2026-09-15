@extends('layouts.admin')

@section('title', 'Invoice Detail')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Invoice Detail
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">

        {{-- LEFT --}}
        <div class="col-lg-8">

            {{-- Invoice Information --}}
            <div class="card mb-4">

                <div class="card-header">
                    <h6 class="card-title mb-0">
                        Invoice Information
                    </h6>
                </div>

                <div class="card-body">

                    <div class="row g-4">

                        <div class="col-md-6">

                            <div class="detail-label">
                                Invoice Number
                            </div>

                            <div class="detail-value">
                                {{ $invoice->invoice_number }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Status
                            </div>

                            @php
                                $badges = [
                                    'ISSUED' => 'success',
                                    'CANCELLED' => 'danger',
                                    'VOID' => 'secondary',
                                ];

                                $badgeClass = $badges[$invoice->status] ?? 'secondary';
                            @endphp

                            <div>
                                <span class="badge bg-{{ $badgeClass }}">
                                    {{ $invoice->status }}
                                </span>
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Invoice Date
                            </div>

                            <div class="detail-value">
                                {{ $invoice->invoice_date?->format('d/m/Y') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Currency
                            </div>

                            <div class="detail-value">
                                {{ $invoice->currency ?: 'IDR' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Issued At
                            </div>

                            <div class="detail-value">
                                {{ $invoice->issued_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="col-md-6">

                            <div class="detail-label">
                                Created At
                            </div>

                            <div class="detail-value">
                                {{ $invoice->created_at?->format('d/m/Y H:i:s') ?? '-' }}
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

                    @if ($invoice->order)
                        <div class="row g-4">

                            <div class="col-md-6">

                                <div class="detail-label">
                                    Order Number
                                </div>

                                <div class="detail-value">

                                    <a href="{{ route('super.orders.show', $invoice->order->id) }}" class="order-link">
                                        {{ $invoice->order->order_number }}
                                    </a>

                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Customer Name
                                </div>

                                <div class="detail-value">
                                    {{ $invoice->customer_name ?: '-' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Email
                                </div>

                                <div class="detail-value text-break">
                                    {{ $invoice->customer_email ?: '-' }}
                                </div>

                            </div>


                            <div class="col-md-6">

                                <div class="detail-label">
                                    Phone
                                </div>

                                <div class="detail-value">
                                    {{ $invoice->customer_phone ?: '-' }}
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
            @if ($invoice->order && $invoice->order->items->count())

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

                                    @foreach ($invoice->order->items as $item)
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
                                                Rp
                                                {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                                            </td>

                                            <td>
                                                Rp
                                                {{ number_format((float) $item->subtotal, 0, ',', '.') }}
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
            @if ($invoice->discount_amount > 0 || ($invoice->order && $invoice->order->discount_code))
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
                                    {{ $invoice->order?->discount_code ?: '-' }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-label">
                                    Discount Amount
                                </div>

                                <div class="detail-value">
                                    Rp
                                    {{ number_format((float) $invoice->discount_amount, 0, ',', '.') }}
                                </div>

                            </div>


                            <div class="col-md-4">

                                <div class="detail-label">
                                    Total Invoice
                                </div>

                                <div class="detail-value amount">
                                    Rp
                                    {{ number_format((float) $invoice->total_amount, 0, ',', '.') }}
                                </div>

                            </div>

                        </div>

                    </div>

                </div>
            @endif


            {{-- Tickets --}}
            @if ($invoice->order && $invoice->order->tickets->count())

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

                                    @foreach ($invoice->order->tickets as $ticket)
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

            {{-- Invoice Summary --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="card-title mb-0">
                        Invoice Summary
                    </h6>

                </div>

                <div class="card-body">

                    <div class="summary-row">

                        <span>Subtotal</span>

                        <strong>
                            Rp
                            {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}
                        </strong>

                    </div>


                    <div class="summary-row">

                        <span>Discount</span>

                        <strong class="text-danger">

                            - Rp
                            {{ number_format((float) $invoice->discount_amount, 0, ',', '.') }}

                        </strong>

                    </div>


                    <hr>


                    <div class="summary-row total">

                        <span>Total</span>

                        <strong>
                            Rp
                            {{ number_format((float) $invoice->total_amount, 0, ',', '.') }}
                        </strong>

                    </div>

                </div>

            </div>


            {{-- Invoice Status --}}
            <div class="card mb-4">

                <div class="card-header">

                    <h6 class="card-title mb-0">
                        Invoice Status
                    </h6>

                </div>

                <div class="card-body text-center">

                    @if ($invoice->status === 'ISSUED')
                        <div class="status-icon success">
                            <i data-feather="file-text"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Invoice Diterbitkan
                        </h5>

                        <p class="text-muted mb-0">
                            Invoice telah dibuat setelah pembayaran berhasil.
                        </p>
                    @elseif($invoice->status === 'CANCELLED')
                        <div class="status-icon danger">
                            <i data-feather="x-circle"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            Invoice Dibatalkan
                        </h5>

                        <p class="text-muted mb-0">
                            Invoice sudah tidak berlaku.
                        </p>
                    @else
                        <div class="status-icon">
                            <i data-feather="info"></i>
                        </div>

                        <h5 class="mt-3 mb-1">
                            {{ $invoice->status }}
                        </h5>
                    @endif

                </div>

            </div>


            {{-- Payment --}}
            @if ($invoice->order && $invoice->order->payments->count())

                <div class="card mb-4">

                    <div class="card-header">

                        <h6 class="card-title mb-0">
                            Payment
                        </h6>

                    </div>

                    <div class="card-body">

                        @foreach ($invoice->order->payments as $payment)
                            <div class="d-flex justify-content-between align-items-center mb-2">

                                <span class="text-muted">
                                    {{ $payment->payment_number }}
                                </span>

                                @php
                                    $paymentBadges = [
                                        'PENDING' => 'warning',
                                        'PAID' => 'success',
                                        'FAILED' => 'danger',
                                        'EXPIRED' => 'secondary',
                                        'CANCELLED' => 'danger',
                                        'REFUNDED' => 'dark',
                                    ];

                                    $paymentClass = $paymentBadges[$payment->status] ?? 'secondary';
                                @endphp

                                <span class="badge bg-{{ $paymentClass }}">
                                    {{ $payment->status }}
                                </span>

                            </div>
                        @endforeach

                    </div>

                </div>

            @endif

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

        .status-icon.danger {
            color: #dc3545;
            background: #fdebec;
        }
    </style>
@endpush


@push('scripts')
    <script>
        $(function() {

            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                feather.replace();
            }

        });
    </script>
@endpush
