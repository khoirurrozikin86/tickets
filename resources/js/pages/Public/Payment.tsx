import PublicLayout from '../../layouts/PublicLayout';
import { formatRupiah } from '../../lib/format';

interface OrderItem {
    productName: string;
    visitDate: string;
    quantity: number;
    unitPrice: number;
    subtotal: number;
}

interface PaymentData {
    paymentNumber: string;
    amount: number;
    method: string;
    channel: string | null;
    status: string;
    expiredAt: string | null;
    paymentUrl: string | null;
    qrCode: string | null;
}

interface Order {
    id: number;
    orderNumber: string;
    customerName: string;
    customerEmail: string;
    customerPhone: string;
    subtotal: number;
    discountAmount: number;
    totalAmount: number;
    currency: string;
    status: string;
    paymentStatus: string;
    expiresAt: string | null;
    items: OrderItem[];
    payment: PaymentData | null;
}

interface PaymentProps {
    order: Order;
    settings: Record<string, string | null>;
}

export default function Payment({
    order,
    settings,
}: PaymentProps) {
    return (
        <PublicLayout settings={settings}>
            <main className="min-h-screen bg-[#f5faf7] px-4 py-10">
                <div className="mx-auto max-w-3xl">

                    <div className="rounded-3xl bg-white p-6 shadow-sm ring-1 ring-black/5 sm:p-8">

                        {/* Icon */}
                        <div className="flex justify-center">
                            <div className="flex h-16 w-16 items-center justify-center rounded-full bg-[#159f79]/10">
                                <span className="text-3xl text-[#159f79]">
                                    ✓
                                </span>
                            </div>
                        </div>

                        <h1 className="mt-5 text-center text-2xl font-bold text-gray-800">
                            Lanjut ke Pembayaran
                        </h1>

                        <p className="mt-2 text-center text-sm text-gray-500">
                            Silakan lakukan pembayaran untuk menyelesaikan pesanan.
                        </p>

                        {/* Order */}
                        <div className="mt-7 rounded-2xl bg-[#f5faf7] p-5">
                            <p className="text-xs font-medium text-gray-400">
                                Nomor Pesanan
                            </p>

                            <p className="mt-1 text-xl font-bold text-[#159f79]">
                                {order.orderNumber}
                            </p>

                            <div className="mt-4 grid gap-3 sm:grid-cols-2">
                                <div>
                                    <p className="text-xs text-gray-400">
                                        Nama Pemesan
                                    </p>

                                    <p className="text-sm font-semibold text-gray-700">
                                        {order.customerName}
                                    </p>
                                </div>

                                <div>
                                    <p className="text-xs text-gray-400">
                                        Email
                                    </p>

                                    <p className="text-sm font-semibold text-gray-700">
                                        {order.customerEmail}
                                    </p>
                                </div>
                            </div>
                        </div>

                        {/* Items */}
                        <div className="mt-6">
                            <h2 className="text-sm font-bold text-gray-800">
                                Detail Pesanan
                            </h2>

                            <div className="mt-3 space-y-3">
                                {order.items.map((item, index) => (
                                    <div
                                        key={index}
                                        className="flex items-center justify-between rounded-xl border border-gray-100 p-4"
                                    >
                                        <div>
                                            <p className="font-semibold text-gray-800">
                                                {item.productName}
                                            </p>

                                            <p className="mt-1 text-xs text-gray-500">
                                                {item.visitDate} × {item.quantity}
                                            </p>
                                        </div>

                                        <p className="font-semibold text-gray-800">
                                            {formatRupiah(item.subtotal)}
                                        </p>
                                    </div>
                                ))}
                            </div>
                        </div>

                        {/* Total */}
                        <div className="mt-6 border-t border-gray-100 pt-5">
                            <div className="flex justify-between text-sm text-gray-500">
                                <span>Subtotal</span>
                                <span>
                                    {formatRupiah(order.subtotal)}
                                </span>
                            </div>

                            {order.discountAmount > 0 && (
                                <div className="mt-2 flex justify-between text-sm text-green-600">
                                    <span>Diskon</span>
                                    <span>
                                        - {formatRupiah(order.discountAmount)}
                                    </span>
                                </div>
                            )}

                            <div className="mt-4 flex justify-between">
                                <span className="text-lg font-bold text-gray-800">
                                    Total
                                </span>

                                <span className="text-xl font-bold text-[#159f79]">
                                    {formatRupiah(order.totalAmount)}
                                </span>
                            </div>
                        </div>

                        {/* Payment */}
                        <div className="mt-7 rounded-2xl border border-[#159f79]/20 bg-[#159f79]/5 p-5">
                            <p className="text-sm font-bold text-gray-800">
                                Metode Pembayaran
                            </p>

                            <p className="mt-1 text-sm text-gray-600">
                                {order.payment?.method ?? 'QRIS'}
                            </p>

                            <div className="mt-4 rounded-xl bg-white p-4 text-center">
                                <p className="text-xs text-gray-400">
                                    Status Pembayaran
                                </p>

                                <p className="mt-1 font-bold text-orange-500">
                                    {order.paymentStatus}
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </main>
        </PublicLayout>
    );
}