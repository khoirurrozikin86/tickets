<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <title>
        {{ $invoice->invoice_number }}
    </title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #333;
            margin: 0;
            padding: 30px;
        }

        .header {
            width: 100%;
            margin-bottom: 30px;
        }

        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: top;
        }

        .company {
            font-size: 18px;
            font-weight: bold;
        }

        .invoice-title {
            text-align: right;
            font-size: 24px;
            font-weight: bold;
        }

        .invoice-number {
            text-align: right;
            margin-top: 5px;
        }

        .section-title {
            font-weight: bold;
            font-size: 12px;
            margin-bottom: 8px;
        }

        .info-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .info-table td {
            padding: 4px 0;
            vertical-align: top;
        }

        .label {
            width: 120px;
            color: #666;
        }

        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .items th {
            background: #f1f1f1;
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }

        .items td {
            border: 1px solid #ddd;
            padding: 8px;
        }

        .text-right {
            text-align: right;
        }

        .summary {
            width: 300px;
            margin-left: auto;
            margin-top: 20px;
            border-collapse: collapse;
        }

        .summary td {
            padding: 6px;
        }

        .summary .total {
            font-size: 14px;
            font-weight: bold;
            border-top: 2px solid #333;
        }

        .status {
            display: inline-block;
            padding: 5px 10px;
            border: 1px solid #999;
            font-weight: bold;
        }

        .footer {
            margin-top: 50px;
            padding-top: 15px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #777;
            font-size: 9px;
        }
    </style>

</head>

<body>


    {{-- HEADER --}}

    <div class="header">

        <table class="header-table">

            <tr>

                <td>

                    <div class="company">
                        DUSUN SEMILIR
                    </div>

                    <div>
                        Invoice Transaksi
                    </div>

                </td>

                <td>

                    <div class="invoice-title">
                        INVOICE
                    </div>

                    <div class="invoice-number">
                        {{ $invoice->invoice_number }}
                    </div>

                </td>

            </tr>

        </table>

    </div>


    {{-- CUSTOMER --}}

    <div class="section-title">
        Customer
    </div>

    <table class="info-table">

        <tr>
            <td class="label">
                Nama
            </td>

            <td>
                {{ $invoice->customer_name ?: '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Email
            </td>

            <td>
                {{ $invoice->customer_email ?: '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Phone
            </td>

            <td>
                {{ $invoice->customer_phone ?: '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Invoice Date
            </td>

            <td>
                {{ $invoice->invoice_date?->format('d-m-Y') ?: '-' }}
            </td>
        </tr>

        <tr>
            <td class="label">
                Status
            </td>

            <td>
                <span class="status">
                    {{ $invoice->status }}
                </span>
            </td>
        </tr>

    </table>


    {{-- ORDER --}}

    <div class="section-title">
        Order
    </div>

    <table class="info-table">

        <tr>

            <td class="label">
                Order Number
            </td>

            <td>
                {{ $invoice->order?->order_number ?: '-' }}
            </td>

        </tr>

    </table>


    {{-- ITEMS --}}

    <div class="section-title">
        Order Items
    </div>

    <table class="items">

        <thead>

            <tr>

                <th>
                    Product
                </th>

                <th>
                    Visit Date
                </th>

                <th class="text-right">
                    Harga
                </th>

                <th class="text-right">
                    Qty
                </th>

                <th class="text-right">
                    Subtotal
                </th>

            </tr>

        </thead>

        <tbody>

            @forelse ($invoice->order?->items ?? [] as $item)
                <tr>

                    <td>
                        {{ $item->product_name }}
                    </td>

                    <td>
                        {{ $item->visit_date?->format('d-m-Y') ?: '-' }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format((float) $item->unit_price, 0, ',', '.') }}
                    </td>

                    <td class="text-right">
                        {{ $item->quantity }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format((float) $item->subtotal, 0, ',', '.') }}
                    </td>

                </tr>

            @empty

                <tr>

                    <td colspan="5" style="text-align:center;">
                        Tidak ada item.
                    </td>

                </tr>
            @endforelse

        </tbody>

    </table>


    {{-- SUMMARY --}}

    <table class="summary">

        <tr>

            <td>
                Subtotal
            </td>

            <td class="text-right">
                Rp {{ number_format((float) $invoice->subtotal, 0, ',', '.') }}
            </td>

        </tr>

        <tr>

            <td>
                Discount
            </td>

            <td class="text-right">
                Rp {{ number_format((float) $invoice->discount_amount, 0, ',', '.') }}
            </td>

        </tr>

        <tr class="total">

            <td>
                TOTAL
            </td>

            <td class="text-right">
                Rp {{ number_format((float) $invoice->total_amount, 0, ',', '.') }}
            </td>

        </tr>

    </table>


    {{-- PAYMENT --}}

    @php
        $payment = $invoice->order?->payments?->sortByDesc('created_at')->first();
    @endphp

    <div style="margin-top: 30px;">

        <div class="section-title">
            Payment
        </div>

        <table class="info-table">

            <tr>

                <td class="label">
                    Payment Number
                </td>

                <td>
                    {{ $payment?->payment_number ?: '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Status
                </td>

                <td>
                    {{ $payment?->status ?: '-' }}
                </td>

            </tr>

            <tr>

                <td class="label">
                    Paid At
                </td>

                <td>
                    {{ $payment?->paid_at?->format('d-m-Y H:i:s') ?: '-' }}
                </td>

            </tr>

        </table>

    </div>


    {{-- FOOTER --}}

    <div class="footer">

        Invoice ini dibuat secara otomatis oleh sistem
        Ticketing Dusun Semilir.

    </div>


</body>

</html>
