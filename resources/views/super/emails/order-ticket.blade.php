<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>E-Ticket Dusun Semilir</title>
</head>

<body style="margin:0; padding:0; background:#f5f5f5; font-family:Arial, sans-serif;">

    <div style="max-width:650px; margin:30px auto; background:#ffffff; padding:35px;">

        <h2 style="margin-top:0;">
            E-Ticket Dusun Semilir
        </h2>

        <p>
            Halo <strong>{{ $order->customer_name }}</strong>,
        </p>

        <p>
            Terima kasih telah melakukan pembelian tiket Dusun Semilir.
        </p>

        <p>
            Pembayaran untuk order
            <strong>{{ $order->order_number }}</strong>
            telah berhasil.
        </p>

        <table style="width:100%; margin:25px 0; border-collapse:collapse;">
            <tr>
                <td style="padding:8px 0;">Order</td>
                <td style="padding:8px 0; text-align:right;">
                    <strong>{{ $order->order_number }}</strong>
                </td>
            </tr>

            <tr>
                <td style="padding:8px 0;">Customer</td>
                <td style="padding:8px 0; text-align:right;">
                    {{ $order->customer_name }}
                </td>
            </tr>

            <tr>
                <td style="padding:8px 0;">Total</td>
                <td style="padding:8px 0; text-align:right;">
                    <strong>
                        Rp {{ number_format((float) $order->total_amount, 0, ',', '.') }}
                    </strong>
                </td>
            </tr>

            <tr>
                <td style="padding:8px 0;">Jumlah Ticket</td>
                <td style="padding:8px 0; text-align:right;">
                    {{ $order->tickets->count() }}
                </td>
            </tr>
        </table>

        <p>
            E-Ticket kamu terlampir dalam email ini dalam format PDF.
        </p>

        <p>
            Silakan tunjukkan QR Code pada E-Ticket ketika melakukan
            validasi di lokasi.
        </p>

        <p style="margin-top:35px;">
            Sampai jumpa di <strong>Dusun Semilir</strong> 👋
        </p>

        <hr style="margin:30px 0; border:none; border-top:1px solid #ddd;">

        <small style="color:#777;">
            Email ini dikirim secara otomatis oleh sistem Tiket Dusun Semilir.
        </small>

    </div>

</body>

</html>
