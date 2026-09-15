@extends('layouts.admin')

@section('title', 'Dashboard')

@section('breadcrumb')

    <nav class="page-breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="#">Overview</a>
            </li>

            <li class="breadcrumb-item active">
                Dashboard
            </li>

        </ol>

    </nav>

@endsection


@section('content')

    <div class="dashboard-page">


        {{-- =========================================================
        HERO
    ========================================================== --}}

        <div class="dashboard-hero mb-4">

            <div class="hero-decoration hero-decoration-one"></div>
            <div class="hero-decoration hero-decoration-two"></div>

            <div class="row align-items-center position-relative">

                <div class="col-lg-8">

                    <div class="hero-content">

                        <div class="hero-badge">
                            <i data-feather="activity"></i>
                            <span>Ticket Management System</span>
                        </div>


                        <h1 class="hero-title">

                            Halo,
                            <span>
                                {{ auth()->user()->name ?? 'Admin' }}
                            </span>

                            👋

                        </h1>


                        <p class="hero-description">

                            Pantau transaksi, pembayaran, tiket,
                            invoice dan aktivitas scanning
                            dalam satu dashboard.

                        </p>


                        <div class="hero-date">

                            <i data-feather="calendar"></i>

                            {{ now()->translatedFormat('l, d F Y') }}

                        </div>

                    </div>

                </div>


                <div class="col-lg-4">

                    <div class="hero-illustration">

                        <div class="hero-glow"></div>

                        <img src="{{ asset('images/osil.png') }}" alt="OSIL" class="osil-image">

                        <div class="hero-bubble">

                            <small>System Status</small>

                            <strong>
                                Everything is ready!
                            </strong>

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        MAIN KPI
    ========================================================== --}}

        <div class="row g-3 mb-4">


            {{-- REVENUE --}}

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-stat stat-revenue">

                    <div class="stat-icon">
                        <i data-feather="dollar-sign"></i>
                    </div>

                    <div class="stat-content">

                        <div class="stat-label">
                            Revenue Hari Ini
                        </div>

                        <div class="stat-value">

                            Rp
                            {{ number_format((float) $revenueToday, 0, ',', '.') }}

                        </div>

                        <div class="stat-meta">
                            {{ $paidOrdersToday }} order PAID
                        </div>

                    </div>

                </div>

            </div>


            {{-- ORDER --}}

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-stat stat-order">

                    <div class="stat-icon">
                        <i data-feather="shopping-cart"></i>
                    </div>

                    <div class="stat-content">

                        <div class="stat-label">
                            Order Hari Ini
                        </div>

                        <div class="stat-value">
                            {{ number_format($ordersToday) }}
                        </div>

                        <div class="stat-meta">

                            <span>
                                {{ $pendingOrdersToday }} pending
                            </span>

                            <span>
                                {{ $expiredOrdersToday }} expired
                            </span>

                        </div>

                    </div>

                </div>

            </div>


            {{-- TICKET --}}

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-stat stat-ticket">

                    <div class="stat-icon">
                        <i data-feather="tag"></i>
                    </div>

                    <div class="stat-content">

                        <div class="stat-label">
                            Tiket Terbit Hari Ini
                        </div>

                        <div class="stat-value">
                            {{ number_format($ticketsToday) }}
                        </div>

                        <div class="stat-meta">

                            {{ $activeTicketsToday }} active ·
                            {{ $usedTicketsToday }} used

                        </div>

                    </div>

                </div>

            </div>


            {{-- SCAN --}}

            <div class="col-12 col-sm-6 col-xl-3">

                <div class="dashboard-stat stat-scan">

                    <div class="stat-icon">
                        <i data-feather="maximize"></i>
                    </div>

                    <div class="stat-content">

                        <div class="stat-label">
                            Scan Hari Ini
                        </div>

                        <div class="stat-value">
                            {{ number_format($scansToday) }}
                        </div>

                        <div class="stat-meta">

                            {{ $scanSuccessRate }}% success rate

                        </div>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        SECONDARY KPI
    ========================================================== --}}

        <div class="row g-3 mb-4">


            {{-- PAYMENT --}}

            <div class="col-12 col-md-4">

                <div class="mini-stat-card">

                    <div class="mini-stat-icon blue">
                        <i data-feather="credit-card"></i>
                    </div>

                    <div>

                        <span>
                            Payment Hari Ini
                        </span>

                        <strong>
                            {{ number_format($paymentsToday) }}
                        </strong>

                        <small>
                            {{ $paidPaymentsToday }} berhasil ·
                            {{ $pendingPaymentsToday }} pending
                        </small>

                    </div>

                </div>

            </div>


            {{-- INVOICE --}}

            <div class="col-12 col-md-4">

                <div class="mini-stat-card">

                    <div class="mini-stat-icon purple">
                        <i data-feather="file-text"></i>
                    </div>

                    <div>

                        <span>
                            Invoice Hari Ini
                        </span>

                        <strong>
                            {{ number_format($invoicesToday) }}
                        </strong>

                        <small>
                            {{ $issuedInvoicesToday }} issued
                        </small>

                    </div>

                </div>

            </div>


            {{-- DATABASE --}}

            <div class="col-12 col-md-4">

                <div class="mini-stat-card">

                    <div class="mini-stat-icon green">
                        <i data-feather="database"></i>
                    </div>

                    <div>

                        <span>
                            Master Data
                        </span>

                        <strong>
                            {{ number_format($totalProducts) }}
                            Produk
                        </strong>

                        <small>
                            {{ $totalUsers }} user ·
                            {{ $activeDiscounts }} discount aktif
                        </small>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        CHARTS
    ========================================================== --}}

        <div class="row g-3 mb-4">


            {{-- REVENUE CHART --}}

            <div class="col-12 col-xl-8">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Revenue
                            </h5>

                            <p>
                                Performa pendapatan 7 hari terakhir
                            </p>

                        </div>

                        <span class="card-period">
                            7 Hari
                        </span>

                    </div>

                    <div class="chart-container">

                        <canvas id="revenueChart"></canvas>

                    </div>

                </div>

            </div>


            {{-- TICKET CHART --}}

            <div class="col-12 col-xl-4">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Aktivitas
                            </h5>

                            <p>
                                Order & tiket
                            </p>

                        </div>

                    </div>

                    <div class="chart-container chart-small">

                        <canvas id="activityChart"></canvas>

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        STATUS OVERVIEW
    ========================================================== --}}

        <div class="row g-3 mb-4">


            {{-- TICKET STATUS --}}

            <div class="col-12 col-lg-6">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Ticket Status
                            </h5>

                            <p>
                                Kondisi seluruh tiket
                            </p>

                        </div>

                    </div>


                    <div class="status-list">


                        @php

                            $ticketStatuses = [
                                'ACTIVE' => ['label' => 'Active', 'class' => 'success'],
                                'USED' => ['label' => 'Used', 'class' => 'primary'],
                                'EXPIRED' => ['label' => 'Expired', 'class' => 'danger'],
                                'CANCELLED' => ['label' => 'Cancelled', 'class' => 'dark'],
                            ];

                            $totalTicketStatus = max(1, $ticketStatus->sum());

                        @endphp


                        @foreach ($ticketStatuses as $status => $config)
                            @php

                                $total = (int) ($ticketStatus[$status] ?? 0);

                                $percentage = round(($total / $totalTicketStatus) * 100, 1);
                            @endphp


                            <div class="status-row">

                                <div class="status-name">

                                    <span class="status-dot {{ $config['class'] }}"></span>

                                    {{ $config['label'] }}

                                </div>

                                <strong>
                                    {{ number_format($total) }}
                                </strong>

                                <div class="status-bar">

                                    <span class="{{ $config['class'] }}" style="width: {{ $percentage }}%"></span>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>


            {{-- PAYMENT STATUS --}}

            <div class="col-12 col-lg-6">

                <div class="dashboard-card">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Payment Status
                            </h5>

                            <p>
                                Status pembayaran
                            </p>

                        </div>

                    </div>


                    <div class="payment-status-grid">

                        @php

                            $paymentStatuses = [
                                'PAID' => [
                                    'label' => 'Paid',
                                    'icon' => 'check-circle',
                                    'class' => 'success',
                                ],

                                'PENDING' => [
                                    'label' => 'Pending',
                                    'icon' => 'clock',
                                    'class' => 'warning',
                                ],

                                'EXPIRED' => [
                                    'label' => 'Expired',
                                    'icon' => 'x-circle',
                                    'class' => 'danger',
                                ],

                                'FAILED' => [
                                    'label' => 'Failed',
                                    'icon' => 'alert-circle',
                                    'class' => 'dark',
                                ],
                            ];

                        @endphp


                        @foreach ($paymentStatuses as $status => $config)
                            <div class="payment-box">

                                <div class="payment-box-icon {{ $config['class'] }}">

                                    <i data-feather="{{ $config['icon'] }}"></i>

                                </div>

                                <div>

                                    <span>
                                        {{ $config['label'] }}
                                    </span>

                                    <strong>
                                        {{ number_format((int) ($paymentStatus[$status] ?? 0)) }}
                                    </strong>

                                </div>

                            </div>
                        @endforeach

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        TOP PRODUCTS
    ========================================================== --}}

        <div class="row g-3 mb-4">


            <div class="col-12 col-lg-5">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Produk Terlaris
                            </h5>

                            <p>
                                7 hari terakhir
                            </p>

                        </div>

                    </div>


                    @if ($topProducts->isEmpty())

                        <div class="empty-state">

                            <i data-feather="package"></i>

                            <span>
                                Belum ada data penjualan.
                            </span>

                        </div>
                    @else
                        <div class="product-list">

                            @foreach ($topProducts as $index => $product)
                                <div class="product-row">

                                    <div class="product-rank">
                                        {{ $index + 1 }}
                                    </div>

                                    <div class="product-info">

                                        <strong>
                                            {{ $product->product_name }}
                                        </strong>

                                        <small>
                                            {{ number_format($product->total_quantity) }}
                                            tiket
                                        </small>

                                    </div>

                                    <div class="product-sales">

                                        Rp
                                        {{ number_format((float) $product->total_sales, 0, ',', '.') }}

                                    </div>

                                </div>
                            @endforeach

                        </div>

                    @endif

                </div>

            </div>


            {{-- RECENT ORDERS --}}

            <div class="col-12 col-lg-7">

                <div class="dashboard-card h-100">

                    <div class="dashboard-card-header">

                        <div>

                            <h5>
                                Order Terbaru
                            </h5>

                            <p>
                                Transaksi terbaru
                            </p>

                        </div>


                        @can('orders.view')
                            <a href="{{ route('super.orders.index') }}" class="card-link">
                                Lihat semua
                                <i data-feather="arrow-right"></i>
                            </a>
                        @endcan

                    </div>


                    <div class="recent-order-list">

                        @forelse ($recentOrders as $order)
                            <div class="recent-order-row">

                                <div class="order-icon">

                                    <i data-feather="shopping-bag"></i>

                                </div>

                                <div class="order-info">

                                    <strong>
                                        {{ $order->order_number }}
                                    </strong>

                                    <span>
                                        {{ $order->customer_name }}
                                    </span>

                                </div>

                                <div class="order-total">

                                    <strong>
                                        Rp
                                        {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                                    </strong>

                                    <small>
                                        {{ $order->created_at?->format('H:i') }}
                                    </small>

                                </div>

                                <span
                                    class="status-badge
                                status-{{ strtolower($order->payment_status) }}">
                                    {{ $order->payment_status }}
                                </span>

                            </div>

                        @empty

                            <div class="empty-state">

                                <i data-feather="shopping-cart"></i>

                                <span>
                                    Belum ada order.
                                </span>

                            </div>
                        @endforelse

                    </div>

                </div>

            </div>

        </div>



        {{-- =========================================================
        RECENT SCANS
    ========================================================== --}}

        <div class="dashboard-card mb-4">

            <div class="dashboard-card-header">

                <div>

                    <h5>
                        Aktivitas Scan Terbaru
                    </h5>

                    <p>
                        Aktivitas scanner terakhir
                    </p>

                </div>


                @can('tickets.view')
                    <a href="{{ route('super.scan.monitoring') }}" class="card-link">

                        Monitoring

                        <i data-feather="arrow-right"></i>

                    </a>
                @endcan

            </div>


            <div class="table-responsive">

                <table class="table dashboard-table align-middle mb-0">

                    <thead>

                        <tr>

                            <th>Tiket</th>

                            <th>Produk</th>

                            <th>Visit Date</th>

                            <th>Result</th>

                            <th>Reason</th>

                            <th>Scanner</th>

                            <th>Waktu</th>

                        </tr>

                    </thead>


                    <tbody>

                        @forelse ($recentScans as $scan)
                            <tr>

                                <td>

                                    <strong>
                                        {{ $scan->ticket?->ticket_number ?? '-' }}
                                    </strong>

                                </td>


                                <td>
                                    {{ $scan->ticket?->product_name ?? '-' }}
                                </td>


                                <td>

                                    {{ $scan->ticket?->visit_date ? $scan->ticket->visit_date->format('d/m/Y') : '-' }}

                                </td>


                                <td>

                                    <span
                                        class="scan-result
                                    {{ strtolower($scan->result) }}">

                                        {{ $scan->result }}

                                    </span>

                                </td>


                                <td>
                                    {{ $scan->reason }}
                                </td>


                                <td>
                                    {{ $scan->scannedBy?->name ?? 'System' }}
                                </td>


                                <td>
                                    {{ $scan->scanned_at?->format('d/m/Y H:i:s') }}
                                </td>

                            </tr>

                        @empty

                            <tr>

                                <td colspan="7" class="text-center py-5 text-muted">

                                    Belum ada aktivitas scan.

                                </td>

                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>



        {{-- =========================================================
        QUICK ACCESS
    ========================================================== --}}

        <div class="row g-3">


            @can('tickets.view')
                <div class="col-12 col-md-4">

                    <a href="{{ route('super.scan.barcode') }}" class="quick-card">

                        <div class="quick-icon green">
                            <i data-feather="maximize"></i>
                        </div>

                        <div>

                            <strong>
                                Scan Ticket
                            </strong>

                            <span>
                                Validasi tiket customer
                            </span>

                        </div>

                        <i data-feather="arrow-right" class="quick-arrow"></i>

                    </a>

                </div>
            @endcan


            @can('orders.view')
                <div class="col-12 col-md-4">

                    <a href="{{ route('super.orders.index') }}" class="quick-card">

                        <div class="quick-icon blue">
                            <i data-feather="shopping-cart"></i>
                        </div>

                        <div>

                            <strong>
                                Order
                            </strong>

                            <span>
                                Monitor transaksi customer
                            </span>

                        </div>

                        <i data-feather="arrow-right" class="quick-arrow"></i>

                    </a>

                </div>
            @endcan


            @can('payments.view')
                <div class="col-12 col-md-4">

                    <a href="{{ route('super.payments.index') }}" class="quick-card">

                        <div class="quick-icon purple">
                            <i data-feather="credit-card"></i>
                        </div>

                        <div>

                            <strong>
                                Payment
                            </strong>

                            <span>
                                Monitor pembayaran Espay
                            </span>

                        </div>

                        <i data-feather="arrow-right" class="quick-arrow"></i>

                    </a>

                </div>
            @endcan

        </div>

    </div>

@endsection



@push('styles')
    <style>
        /* =========================================================
       DASHBOARD
    ========================================================= */

        .dashboard-page {
            --dashboard-radius: 18px;
        }


        /* =========================================================
       HERO
    ========================================================= */

        .dashboard-hero {

            position: relative;

            overflow: hidden;

            min-height: 280px;

            padding: 36px 40px;

            border-radius: 22px;

            background:
                radial-gradient(circle at 85% 15%,
                    rgba(255, 255, 255, .65),
                    transparent 28%),
                linear-gradient(135deg,
                    #ecfdf5 0%,
                    #d1fae5 50%,
                    #bbf7d0 100%);

            border: 1px solid #d8f3e3;

        }


        .hero-decoration {

            position: absolute;

            border-radius: 50%;

            pointer-events: none;

        }


        .hero-decoration-one {

            width: 300px;

            height: 300px;

            right: -100px;

            top: -140px;

            background: rgba(34, 197, 94, .08);

        }


        .hero-decoration-two {

            width: 200px;

            height: 200px;

            right: 270px;

            bottom: -150px;

            background: rgba(16, 185, 129, .07);

        }


        .hero-content {

            position: relative;

            z-index: 2;

        }


        .hero-badge {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            padding: 7px 12px;

            margin-bottom: 15px;

            border-radius: 30px;

            background: rgba(255, 255, 255, .75);

            color: #15803d;

            font-size: 11px;

            font-weight: 700;

        }


        .hero-badge svg {

            width: 15px;

            height: 15px;

        }


        .hero-title {

            margin: 0;

            font-size: clamp(28px, 4vw, 42px);

            font-weight: 800;

            color: #102a1b;

            letter-spacing: -1.2px;

        }


        .hero-title span {

            color: #15803d;

        }


        .hero-description {

            max-width: 650px;

            margin: 10px 0 14px;

            color: #527064;

            font-size: 14px;

            line-height: 1.7;

        }


        .hero-date {

            display: inline-flex;

            align-items: center;

            gap: 7px;

            color: #347054;

            font-size: 12px;

            font-weight: 600;

        }


        .hero-date svg {

            width: 15px;

            height: 15px;

        }


        .hero-illustration {

            position: relative;

            height: 245px;

            display: flex;

            align-items: flex-end;

            justify-content: center;

        }


        .hero-glow {

            position: absolute;

            width: 210px;

            height: 210px;

            border-radius: 50%;

            background: rgba(255, 255, 255, .6);

        }


        .osil-image {

            position: relative;

            z-index: 2;

            width: 210px;

            max-width: 90%;

            filter: drop-shadow(0 16px 14px rgba(0, 0, 0, .12));

            animation: osilFloat 4s ease-in-out infinite;

        }


        .hero-bubble {

            position: absolute;

            right: 0;

            top: 25px;

            z-index: 3;

            display: flex;

            flex-direction: column;

            padding: 12px 16px;

            min-width: 155px;

            border-radius: 14px 14px 14px 4px;

            background: #15803d;

            color: white;

            box-shadow:
                0 10px 25px rgba(21, 128, 61, .2);

        }


        .hero-bubble small {

            font-size: 9px;

            opacity: .75;

        }


        .hero-bubble strong {

            margin-top: 2px;

            font-size: 12px;

        }


        @keyframes osilFloat {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-7px);
            }

        }


        /* =========================================================
       MAIN STAT
    ========================================================= */

        .dashboard-stat {

            position: relative;

            display: flex;

            align-items: center;

            gap: 15px;

            min-height: 125px;

            padding: 20px;

            overflow: hidden;

            border-radius: var(--dashboard-radius);

            background: #fff;

            border: 1px solid #e9edf0;

            transition:
                transform .2s ease,
                box-shadow .2s ease;

        }


        .dashboard-stat:hover {

            transform: translateY(-3px);

            box-shadow:
                0 12px 30px rgba(0, 0, 0, .07);

        }


        .stat-icon {

            width: 54px;

            height: 54px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 16px;

        }


        .stat-icon svg {

            width: 25px;

            height: 25px;

        }


        .stat-content {

            min-width: 0;

        }


        .stat-label {

            color: #718096;

            font-size: 11px;

            margin-bottom: 3px;

        }


        .stat-value {

            color: #182b22;

            font-size: 22px;

            font-weight: 800;

            line-height: 1.2;

        }


        .stat-meta {

            margin-top: 5px;

            color: #94a3a8;

            font-size: 10px;

        }


        .stat-revenue .stat-icon {

            background: #dcfce7;

            color: #15803d;

        }


        .stat-order .stat-icon {

            background: #dbeafe;

            color: #2563eb;

        }


        .stat-ticket .stat-icon {

            background: #fef3c7;

            color: #ca8a04;

        }


        .stat-scan .stat-icon {

            background: #ccfbf1;

            color: #0f766e;

        }


        /* =========================================================
       MINI STAT
    ========================================================= */

        .mini-stat-card {

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 17px;

            border-radius: 15px;

            background: #fff;

            border: 1px solid #e9edf0;

        }


        .mini-stat-icon {

            width: 45px;

            height: 45px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

        }


        .mini-stat-icon svg {

            width: 20px;

            height: 20px;

        }


        .mini-stat-icon.blue {

            background: #dbeafe;

            color: #2563eb;

        }


        .mini-stat-icon.purple {

            background: #ede9fe;

            color: #7c3aed;

        }


        .mini-stat-icon.green {

            background: #dcfce7;

            color: #15803d;

        }


        .mini-stat-card span {

            display: block;

            color: #718096;

            font-size: 10px;

        }


        .mini-stat-card strong {

            display: block;

            margin-top: 2px;

            color: #1f2937;

            font-size: 17px;

            font-weight: 800;

        }


        .mini-stat-card small {

            display: block;

            margin-top: 2px;

            color: #94a3b8;

            font-size: 9px;

        }


        /* =========================================================
       CARD
    ========================================================= */

        .dashboard-card {

            overflow: hidden;

            padding: 20px;

            border-radius: var(--dashboard-radius);

            background: #fff;

            border: 1px solid #e9edf0;

        }


        .dashboard-card-header {

            display: flex;

            align-items: flex-start;

            justify-content: space-between;

            gap: 15px;

            margin-bottom: 18px;

        }


        .dashboard-card-header h5 {

            margin: 0;

            color: #1f2937;

            font-size: 15px;

            font-weight: 800;

        }


        .dashboard-card-header p {

            margin: 3px 0 0;

            color: #94a3b8;

            font-size: 10px;

        }


        .card-period {

            padding: 5px 9px;

            border-radius: 7px;

            background: #f1f5f9;

            color: #64748b;

            font-size: 9px;

            font-weight: 700;

        }


        .card-link {

            display: inline-flex;

            align-items: center;

            gap: 4px;

            color: #15803d;

            font-size: 10px;

            font-weight: 700;

            text-decoration: none;

        }


        .card-link svg {

            width: 13px;

            height: 13px;

        }


        /* =========================================================
       CHART
    ========================================================= */

        .chart-container {

            position: relative;

            height: 300px;

        }


        .chart-small {

            height: 300px;

        }


        /* =========================================================
       STATUS
    ========================================================= */

        .status-row {

            display: grid;

            grid-template-columns: 100px 60px 1fr;

            align-items: center;

            gap: 12px;

            margin-bottom: 17px;

        }


        .status-row:last-child {

            margin-bottom: 0;

        }


        .status-name {

            display: flex;

            align-items: center;

            gap: 7px;

            color: #64748b;

            font-size: 11px;

        }


        .status-dot {

            width: 8px;

            height: 8px;

            border-radius: 50%;

        }


        .status-dot.success {

            background: #22c55e;

        }


        .status-dot.primary {

            background: #3b82f6;

        }


        .status-dot.danger {

            background: #ef4444;

        }


        .status-dot.dark {

            background: #475569;

        }


        .status-row strong {

            text-align: right;

            color: #1f2937;

            font-size: 12px;

        }


        .status-bar {

            height: 7px;

            overflow: hidden;

            border-radius: 20px;

            background: #f1f5f9;

        }


        .status-bar span {

            display: block;

            height: 100%;

            border-radius: inherit;

        }


        .status-bar .success {

            background: #22c55e;

        }


        .status-bar .primary {

            background: #3b82f6;

        }


        .status-bar .danger {

            background: #ef4444;

        }


        .status-bar .dark {

            background: #475569;

        }


        /* =========================================================
       PAYMENT
    ========================================================= */

        .payment-status-grid {

            display: grid;

            grid-template-columns: repeat(2, 1fr);

            gap: 10px;

        }


        .payment-box {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px;

            border-radius: 12px;

            background: #f8fafc;

        }


        .payment-box-icon {

            width: 35px;

            height: 35px;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

        }


        .payment-box-icon svg {

            width: 17px;

            height: 17px;

        }


        .payment-box-icon.success {

            background: #dcfce7;

            color: #15803d;

        }


        .payment-box-icon.warning {

            background: #fef3c7;

            color: #ca8a04;

        }


        .payment-box-icon.danger {

            background: #fee2e2;

            color: #dc2626;

        }


        .payment-box-icon.dark {

            background: #e2e8f0;

            color: #475569;

        }


        .payment-box span {

            display: block;

            color: #94a3b8;

            font-size: 9px;

        }


        .payment-box strong {

            display: block;

            color: #1f2937;

            font-size: 15px;

            font-weight: 800;

        }


        /* =========================================================
       PRODUCT
    ========================================================= */

        .product-row {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 12px 0;

            border-bottom: 1px solid #f1f5f9;

        }


        .product-row:last-child {

            border-bottom: 0;

        }


        .product-rank {

            width: 29px;

            height: 29px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 9px;

            background: #ecfdf5;

            color: #15803d;

            font-size: 11px;

            font-weight: 800;

        }


        .product-info {

            flex: 1;

            min-width: 0;

        }


        .product-info strong {

            display: block;

            overflow: hidden;

            color: #334155;

            font-size: 11px;

            text-overflow: ellipsis;

            white-space: nowrap;

        }


        .product-info small {

            display: block;

            margin-top: 2px;

            color: #94a3b8;

            font-size: 9px;

        }


        .product-sales {

            color: #15803d;

            font-size: 10px;

            font-weight: 800;

        }


        /* =========================================================
       ORDERS
    ========================================================= */

        .recent-order-row {

            display: flex;

            align-items: center;

            gap: 10px;

            padding: 10px 0;

            border-bottom: 1px solid #f1f5f9;

        }


        .recent-order-row:last-child {

            border-bottom: 0;

        }


        .order-icon {

            width: 34px;

            height: 34px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 10px;

            background: #eff6ff;

            color: #2563eb;

        }


        .order-icon svg {

            width: 16px;

            height: 16px;

        }


        .order-info {

            flex: 1;

            min-width: 0;

        }


        .order-info strong {

            display: block;

            color: #334155;

            font-size: 10px;

        }


        .order-info span {

            display: block;

            overflow: hidden;

            margin-top: 2px;

            color: #94a3b8;

            font-size: 9px;

            text-overflow: ellipsis;

            white-space: nowrap;

        }


        .order-total {

            text-align: right;

        }


        .order-total strong {

            display: block;

            color: #1f2937;

            font-size: 10px;

        }


        .order-total small {

            color: #94a3b8;

            font-size: 8px;

        }


        .status-badge {

            padding: 4px 7px;

            border-radius: 6px;

            font-size: 8px;

            font-weight: 700;

        }


        .status-paid {

            background: #dcfce7;

            color: #15803d;

        }


        .status-pending {

            background: #fef3c7;

            color: #a16207;

        }


        .status-expired {

            background: #f1f5f9;

            color: #64748b;

        }


        .status-failed {

            background: #fee2e2;

            color: #dc2626;

        }


        /* =========================================================
       TABLE
    ========================================================= */

        .dashboard-table {

            font-size: 11px;

        }


        .dashboard-table thead th {

            padding: 10px;

            color: #94a3b8;

            background: #f8fafc;

            border-bottom: 0;

            font-size: 9px;

            font-weight: 700;

            white-space: nowrap;

        }


        .dashboard-table tbody td {

            padding: 12px 10px;

            color: #64748b;

            border-color: #f1f5f9;

            white-space: nowrap;

        }


        .dashboard-table tbody strong {

            color: #334155;

        }


        .scan-result {

            display: inline-block;

            padding: 4px 7px;

            border-radius: 6px;

            font-size: 8px;

            font-weight: 700;

        }


        .scan-result.success {

            background: #dcfce7;

            color: #15803d;

        }


        .scan-result.failed {

            background: #fee2e2;

            color: #dc2626;

        }


        /* =========================================================
       EMPTY
    ========================================================= */

        .empty-state {

            display: flex;

            flex-direction: column;

            align-items: center;

            justify-content: center;

            min-height: 150px;

            gap: 8px;

            color: #94a3b8;

            font-size: 11px;

        }


        .empty-state svg {

            width: 28px;

            height: 28px;

            opacity: .5;

        }


        /* =========================================================
       QUICK ACCESS
    ========================================================= */

        .quick-card {

            display: flex;

            align-items: center;

            gap: 13px;

            padding: 17px;

            text-decoration: none;

            border-radius: 15px;

            background: #fff;

            border: 1px solid #e9edf0;

            transition: all .2s ease;

        }


        .quick-card:hover {

            transform: translateY(-3px);

            box-shadow:
                0 12px 30px rgba(0, 0, 0, .07);

        }


        .quick-icon {

            width: 45px;

            height: 45px;

            flex-shrink: 0;

            display: flex;

            align-items: center;

            justify-content: center;

            border-radius: 13px;

        }


        .quick-icon svg {

            width: 20px;

            height: 20px;

        }


        .quick-icon.green {

            background: #dcfce7;

            color: #15803d;

        }


        .quick-icon.blue {

            background: #dbeafe;

            color: #2563eb;

        }


        .quick-icon.purple {

            background: #ede9fe;

            color: #7c3aed;

        }


        .quick-card strong {

            display: block;

            color: #1f2937;

            font-size: 12px;

        }


        .quick-card span {

            display: block;

            margin-top: 3px;

            color: #94a3b8;

            font-size: 9px;

        }


        .quick-arrow {

            width: 16px;

            height: 16px;

            margin-left: auto;

            color: #94a3b8;

        }


        /* =========================================================
       RESPONSIVE
    ========================================================= */

        @media (max-width: 991.98px) {

            .dashboard-hero {

                padding: 30px;

            }


            .hero-illustration {

                height: 190px;

                margin-top: 10px;

            }


            .osil-image {

                width: 170px;

            }


            .hero-bubble {

                right: 5%;

            }

        }


        @media (max-width: 575.98px) {

            .dashboard-hero {

                padding: 25px 20px;

                border-radius: 17px;

            }


            .hero-title {

                font-size: 27px;

            }


            .hero-description {

                font-size: 12px;

            }


            .hero-illustration {

                height: 180px;

            }


            .dashboard-stat {

                min-height: 105px;

            }


            .stat-value {

                font-size: 19px;

            }


            .payment-status-grid {

                grid-template-columns: 1fr;

            }


            .status-row {

                grid-template-columns: 90px 45px 1fr;

                gap: 7px;

            }


            .recent-order-row {

                flex-wrap: wrap;

            }


            .status-badge {

                margin-left: 44px;

            }

        }
    </style>
@endpush



@push('scripts')
    {{-- Chart.js --}}

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | FEATHER
            |--------------------------------------------------------------------------
            */

            function refreshFeatherIcons() {

                if (
                    window.feather &&
                    typeof window.feather.replace === 'function'
                ) {

                    window.feather.replace();

                }

            }

            refreshFeatherIcons();


            /*
            |--------------------------------------------------------------------------
            | CHART DATA
            |--------------------------------------------------------------------------
            */

            const chartLabels = @json($revenueLabels);

            const revenueData = @json($revenueData);

            const ordersData = @json($ordersData);

            const ticketsData = @json($ticketsData);


            /*
            |--------------------------------------------------------------------------
            | REVENUE CHART
            |--------------------------------------------------------------------------
            */

            const revenueCanvas =
                document.getElementById('revenueChart');


            if (revenueCanvas) {

                new Chart(
                    revenueCanvas, {

                        type: 'line',

                        data: {

                            labels: chartLabels,

                            datasets: [

                                {

                                    label: 'Revenue',

                                    data: revenueData,

                                    borderWidth: 3,

                                    tension: .4,

                                    fill: true,

                                    pointRadius: 3,

                                    pointHoverRadius: 5,

                                }

                            ]

                        },


                        options: {

                            responsive: true,

                            maintainAspectRatio: false,

                            interaction: {

                                intersect: false,

                                mode: 'index'

                            },


                            plugins: {

                                legend: {

                                    display: false

                                },


                                tooltip: {

                                    callbacks: {

                                        label: function(context) {

                                            return 'Rp ' +
                                                new Intl.NumberFormat(
                                                    'id-ID'
                                                ).format(
                                                    context.raw
                                                );

                                        }

                                    }

                                }

                            },


                            scales: {

                                y: {

                                    beginAtZero: true,

                                    ticks: {

                                        callback: function(value) {

                                            return 'Rp ' +
                                                new Intl.NumberFormat(
                                                    'id-ID', {
                                                        notation: 'compact'
                                                    }
                                                ).format(value);

                                        }

                                    }

                                },

                                x: {

                                    grid: {

                                        display: false

                                    }

                                }

                            }

                        }

                    }
                );

            }


            /*
            |--------------------------------------------------------------------------
            | ACTIVITY CHART
            |--------------------------------------------------------------------------
            */

            const activityCanvas =
                document.getElementById('activityChart');


            if (activityCanvas) {

                new Chart(
                    activityCanvas, {

                        type: 'bar',

                        data: {

                            labels: chartLabels,

                            datasets: [

                                {

                                    label: 'Order',

                                    data: ordersData,

                                    borderRadius: 5,

                                    borderWidth: 0

                                },

                                {

                                    label: 'Ticket',

                                    data: ticketsData,

                                    borderRadius: 5,

                                    borderWidth: 0

                                }

                            ]

                        },


                        options: {

                            responsive: true,

                            maintainAspectRatio: false,

                            plugins: {

                                legend: {

                                    position: 'bottom',

                                    labels: {

                                        boxWidth: 10,

                                        font: {

                                            size: 9

                                        }

                                    }

                                }

                            },


                            scales: {

                                y: {

                                    beginAtZero: true,

                                    ticks: {

                                        precision: 0

                                    }

                                },

                                x: {

                                    grid: {

                                        display: false

                                    }

                                }

                            }

                        }

                    }
                );

            }

        });
    </script>
@endpush
