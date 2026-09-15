<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        E-Ticket {{ $order->order_number }}
    </title>

    <style>
        /*
        |--------------------------------------------------------------------------
        | PAGE
        |--------------------------------------------------------------------------
        */

        @page {
            size: A4 portrait;
            margin: 0;
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            background: #ffffff;
            font-family: DejaVu Sans, sans-serif;
            color: #263238;
            font-size: 9px;
        }

        /*
         * Margin halaman dibuat melalui padding body.
         * Sama seperti PDF Ticket individual.
         */
        body {
            padding: 12mm 14mm 10mm 14mm;
        }


        /*
        |--------------------------------------------------------------------------
        | TICKET PAGE
        |--------------------------------------------------------------------------
        */

        .ticket-page {
            page-break-after: always;
        }

        .ticket-page:last-child {
            page-break-after: auto;
        }


        /*
        |--------------------------------------------------------------------------
        | HEADER
        |--------------------------------------------------------------------------
        */

        .header {
            width: 100%;
            padding-bottom: 7mm;
            border-bottom: 1px solid #355c4b;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .header-left {
            width: 55%;
        }

        .header-right {
            width: 45%;
            text-align: right;
        }

        .logo {
            display: block;
            width: 44mm;
            height: auto;
        }

        .fallback-logo {
            font-size: 16px;
            font-weight: bold;
            color: #214d3d;
        }

        .title {
            font-size: 20px;
            font-weight: bold;
            color: #214d3d;
            letter-spacing: .5px;
        }

        .ticket-number {
            margin-top: 2mm;
            font-size: 8px;
            color: #78909c;
        }


        /*
        |--------------------------------------------------------------------------
        | QR CODE
        |--------------------------------------------------------------------------
        */

        .qr-section {
            width: 100%;
            text-align: center;
            padding-top: 6mm;
            padding-bottom: 3mm;
        }

        .qr-box {
            display: inline-block;
            padding: 3mm;
            border: 1px solid #dce5df;
            border-radius: 3mm;
            background: #ffffff;
        }

        .qr-code {
            display: block;
            width: 44mm;
            height: 44mm;
        }

        .qr-description {
            margin-top: 2mm;
            font-size: 8px;
            color: #78909c;
        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .status-wrapper {
            text-align: center;
            padding-bottom: 4mm;
        }

        .status {
            display: inline-block;
            padding: 1.5mm 7mm;
            border: 1px solid #b8d3c0;
            border-radius: 10mm;
            background: #edf6ef;
            color: #315d3c;
            font-size: 8px;
            font-weight: bold;
            letter-spacing: .7px;
        }


        /*
        |--------------------------------------------------------------------------
        | TWO COLUMN CARDS
        |--------------------------------------------------------------------------
        */

        .columns {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 3mm;
        }

        .columns td {
            width: 50%;
            vertical-align: top;
        }

        .columns td:first-child {
            padding-right: 2mm;
        }

        .columns td:last-child {
            padding-left: 2mm;
        }

        .card {
            border: 1px solid #dfe7e2;
            border-radius: 2.5mm;
            padding: 3.5mm;
        }

        .card-title {
            padding-bottom: 2mm;
            margin-bottom: 2mm;
            border-bottom: 1px solid #e8eeea;
            color: #214d3d;
            font-size: 9px;
            font-weight: bold;
            letter-spacing: .2px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 1.2mm 0;
            vertical-align: top;
        }

        .info-label {
            width: 27mm;
            color: #78909c;
        }

        .info-value {
            color: #263238;
            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | TRANSACTION
        |--------------------------------------------------------------------------
        */

        .transaction {
            width: 100%;
            border: 1px solid #dfe7e2;
            border-radius: 2.5mm;
            padding: 3.5mm;
            margin-bottom: 3mm;
        }

        .transaction-table {
            width: 100%;
            border-collapse: collapse;
        }

        .transaction-table td {
            width: 50%;
            vertical-align: middle;
        }

        .transaction-table td:first-child {
            padding-right: 2mm;
        }

        .transaction-table td:last-child {
            padding-left: 2mm;
        }

        .transaction-box {
            padding: 2.5mm 3mm;
            background: #f5f8f6;
            border-radius: 1.5mm;
        }

        .transaction-label {
            display: block;
            margin-bottom: 1mm;
            color: #78909c;
            font-size: 7.5px;
        }

        .transaction-value {
            color: #263238;
            font-size: 8.5px;
            font-weight: bold;
        }


        /*
        |--------------------------------------------------------------------------
        | INSTRUCTION
        |--------------------------------------------------------------------------
        */

        .instruction {
            width: 100%;
            padding: 3.5mm;
            margin-bottom: 3mm;
            border-radius: 2.5mm;
            background: #f1f7f2;
        }

        .instruction-title {
            margin-bottom: 1.5mm;
            color: #214d3d;
            font-size: 9px;
            font-weight: bold;
        }

        .instruction-table {
            width: 100%;
            border-collapse: collapse;
        }

        .instruction-table td {
            padding: .6mm 0;
            color: #60756b;
            font-size: 7.5px;
        }


        /*
        |--------------------------------------------------------------------------
        | WELCOME
        |--------------------------------------------------------------------------
        */

        .welcome {
            text-align: center;
            padding-top: 1mm;
        }

        .welcome-title {
            color: #214d3d;
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .welcome-subtitle {
            margin-top: 1mm;
            color: #90a49a;
            font-size: 7px;
            letter-spacing: 1.5px;
        }


        /*
        |--------------------------------------------------------------------------
        | FOOTER
        |--------------------------------------------------------------------------
        */

        .footer {
            margin-top: 2.5mm;
            padding-top: 2mm;
            border-top: 1px solid #e0e7e2;
            text-align: center;
            color: #90a49a;
            font-size: 6.5px;
        }
    </style>

</head>


<body>


    @foreach ($tickets as $ticket)
        <div class="ticket-page">


            {{-- =====================================================
             HEADER
        ====================================================== --}}

            <div class="header">

                <table class="header-table">

                    <tr>

                        <td class="header-left">

                            @php

                                $logoPath = public_path('images/semilir_logo.png');

                                $logoBase64 = file_exists($logoPath)
                                    ? base64_encode(file_get_contents($logoPath))
                                    : null;

                            @endphp


                            @if ($logoBase64)
                                <img src="data:image/png;base64,{{ $logoBase64 }}" class="logo" alt="Dusun Semilir">
                            @else
                                <div class="fallback-logo">
                                    DUSUN SEMILIR
                                </div>
                            @endif

                        </td>


                        <td class="header-right">

                            <div class="title">
                                E-TICKET
                            </div>

                            <div class="ticket-number">
                                {{ $ticket->ticket_number }}
                            </div>

                        </td>

                    </tr>

                </table>

            </div>


            {{-- =====================================================
             QR CODE
        ====================================================== --}}

            <div class="qr-section">

                <div class="qr-box">

                    <img src="data:image/png;base64,{{ $qrCodes[$ticket->id] }}" class="qr-code" alt="QR Code">

                </div>

                <div class="qr-description">
                    Tunjukkan QR Code ini kepada petugas saat melakukan
                    validasi tiket.
                </div>

            </div>


            {{-- =====================================================
             STATUS
        ====================================================== --}}

            <div class="status-wrapper">

                <span class="status">
                    {{ $ticket->status }}
                </span>

            </div>


            {{-- =====================================================
             CUSTOMER + TICKET
        ====================================================== --}}

            <table class="columns">

                <tr>

                    {{-- CUSTOMER --}}

                    <td>

                        <div class="card">

                            <div class="card-title">
                                CUSTOMER
                            </div>

                            <table class="info-table">

                                <tr>

                                    <td class="info-label">
                                        Nama
                                    </td>

                                    <td class="info-value">
                                        {{ $order->customer_name ?: '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">
                                        Email
                                    </td>

                                    <td class="info-value">
                                        {{ $order->customer_email ?: '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">
                                        Phone
                                    </td>

                                    <td class="info-value">
                                        {{ $order->customer_phone ?: '-' }}
                                    </td>

                                </tr>

                            </table>

                        </div>

                    </td>


                    {{-- TICKET INFORMATION --}}

                    <td>

                        <div class="card">

                            <div class="card-title">
                                INFORMASI TIKET
                            </div>

                            <table class="info-table">

                                <tr>

                                    <td class="info-label">
                                        Product
                                    </td>

                                    <td class="info-value">
                                        {{ $ticket->product_name ?: $ticket->product?->name ?: '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">
                                        Visit Date
                                    </td>

                                    <td class="info-value">
                                        {{ $ticket->visit_date?->format('d-m-Y') ?: '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">
                                        Day Type
                                    </td>

                                    <td class="info-value">
                                        {{ $ticket->orderItem?->day_type ?: '-' }}
                                    </td>

                                </tr>

                                <tr>

                                    <td class="info-label">
                                        Issued At
                                    </td>

                                    <td class="info-value">
                                        {{ $ticket->issued_at?->format('d-m-Y H:i') ?: '-' }}
                                    </td>

                                </tr>

                            </table>

                        </div>

                    </td>

                </tr>

            </table>


            {{-- =====================================================
             TRANSACTION
        ====================================================== --}}

            <div class="transaction">

                <div class="card-title">
                    INFORMASI TRANSAKSI
                </div>

                <table class="transaction-table">

                    <tr>

                        <td>

                            <div class="transaction-box">

                                <span class="transaction-label">
                                    Ticket Number
                                </span>

                                <span class="transaction-value">
                                    {{ $ticket->ticket_number }}
                                </span>

                            </div>

                        </td>


                        <td>

                            <div class="transaction-box">

                                <span class="transaction-label">
                                    Order Number
                                </span>

                                <span class="transaction-value">
                                    {{ $order->order_number }}
                                </span>

                            </div>

                        </td>

                    </tr>

                </table>

            </div>


            {{-- =====================================================
             INSTRUCTION
        ====================================================== --}}

            <div class="instruction">

                <div class="instruction-title">
                    INFORMASI PENGGUNAAN
                </div>

                <table class="instruction-table">

                    <tr>

                        <td>
                            1. Tiket hanya dapat digunakan sesuai tanggal
                            kunjungan.
                        </td>

                    </tr>

                    <tr>

                        <td>
                            2. Tunjukkan QR Code kepada petugas saat
                            memasuki area.
                        </td>

                    </tr>

                    <tr>

                        <td>
                            3. Satu QR Code hanya dapat digunakan satu kali.
                        </td>

                    </tr>

                    <tr>

                        <td>
                            4. Jangan membagikan QR Code kepada orang lain.
                        </td>

                    </tr>

                </table>

            </div>


            {{-- =====================================================
             WELCOME
        ====================================================== --}}

            <div class="welcome">

                <div class="welcome-title">
                    SELAMAT BERKUNJUNG
                </div>

                <div class="welcome-subtitle">
                    DUSUN SEMILIR ECO PARK
                </div>

            </div>


            {{-- =====================================================
             FOOTER
        ====================================================== --}}

            <div class="footer">

                E-Ticket resmi
                &nbsp;•&nbsp;
                {{ $ticket->ticket_number }}
                &nbsp;•&nbsp;
                Dusun Semilir

            </div>


        </div>
    @endforeach


</body>

</html>
