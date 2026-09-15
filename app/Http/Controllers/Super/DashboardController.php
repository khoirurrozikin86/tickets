<?php

namespace App\Http\Controllers\Super;

use App\Http\Controllers\Controller;
use App\Models\Discount;
use App\Models\Invoice;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ScanRecord;
use App\Models\Ticket;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        $today = today();

        $sevenDaysAgo = today()->subDays(6);


        /*
        |--------------------------------------------------------------------------
        | TODAY - ORDER
        |--------------------------------------------------------------------------
        */

        $ordersToday = Order::query()
            ->whereDate('created_at', $today)
            ->count();


        $paidOrdersToday = Order::query()
            ->whereDate('created_at', $today)
            ->where('payment_status', 'PAID')
            ->count();


        $pendingOrdersToday = Order::query()
            ->whereDate('created_at', $today)
            ->where('payment_status', 'PENDING')
            ->count();


        $expiredOrdersToday = Order::query()
            ->whereDate('created_at', $today)
            ->where('payment_status', 'EXPIRED')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TODAY - REVENUE
        |--------------------------------------------------------------------------
        */

        $revenueToday = Order::query()
            ->whereDate('created_at', $today)
            ->where('payment_status', 'PAID')
            ->sum('total_amount');


        /*
        |--------------------------------------------------------------------------
        | TODAY - PAYMENT
        |--------------------------------------------------------------------------
        */

        $paymentsToday = Payment::query()
            ->whereDate('created_at', $today)
            ->count();


        $paidPaymentsToday = Payment::query()
            ->whereDate('created_at', $today)
            ->where('status', 'PAID')
            ->count();


        $pendingPaymentsToday = Payment::query()
            ->whereDate('created_at', $today)
            ->where('status', 'PENDING')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TODAY - TICKET
        |--------------------------------------------------------------------------
        */

        $ticketsToday = Ticket::query()
            ->whereDate('issued_at', $today)
            ->count();


        $activeTicketsToday = Ticket::query()
            ->whereDate('issued_at', $today)
            ->where('status', 'ACTIVE')
            ->count();


        $usedTicketsToday = Ticket::query()
            ->whereDate('used_at', $today)
            ->where('status', 'USED')
            ->count();


        $expiredTicketsToday = Ticket::query()
            ->whereDate('expired_at', $today)
            ->where('status', 'EXPIRED')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | TODAY - SCAN
        |--------------------------------------------------------------------------
        */

        $scansToday = ScanRecord::query()
            ->whereDate('scanned_at', $today)
            ->count();


        $successfulScansToday = ScanRecord::query()
            ->whereDate('scanned_at', $today)
            ->where('result', 'SUCCESS')
            ->count();


        $failedScansToday = ScanRecord::query()
            ->whereDate('scanned_at', $today)
            ->where('result', 'FAILED')
            ->count();


        $scanSuccessRate = $scansToday > 0
            ? round(
                ($successfulScansToday / $scansToday) * 100,
                1
            )
            : 0;


        /*
        |--------------------------------------------------------------------------
        | TODAY - INVOICE
        |--------------------------------------------------------------------------
        */

        $invoicesToday = Invoice::query()
            ->whereDate('invoice_date', $today)
            ->count();


        $issuedInvoicesToday = Invoice::query()
            ->whereDate('invoice_date', $today)
            ->where('status', 'ISSUED')
            ->count();


        /*
        |--------------------------------------------------------------------------
        | MASTER DATA
        |--------------------------------------------------------------------------
        */

        $totalProducts = Product::count();

        $totalUsers = User::count();

        $activeDiscounts = Discount::query()
            ->where('is_active', true)
            ->count();


        /*
        |--------------------------------------------------------------------------
        | REVENUE 7 DAYS
        |--------------------------------------------------------------------------
        */

        $revenueRaw = Order::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('payment_status', 'PAID')
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->whereDate('created_at', '<=', $today)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');


        $revenueLabels = [];

        $revenueData = [];


        for ($i = 6; $i >= 0; $i--) {

            $date = today()->subDays($i);

            $key = $date->format('Y-m-d');

            $revenueLabels[] = $date->format('d M');

            $revenueData[] = (float) (
                $revenueRaw[$key]->total ?? 0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | ORDERS 7 DAYS
        |--------------------------------------------------------------------------
        */

        $ordersRaw = Order::query()
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('created_at', '>=', $sevenDaysAgo)
            ->whereDate('created_at', '<=', $today)
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');


        $ordersData = [];


        for ($i = 6; $i >= 0; $i--) {

            $date = today()->subDays($i);

            $key = $date->format('Y-m-d');

            $ordersData[] = (int) (
                $ordersRaw[$key]->total ?? 0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TICKETS 7 DAYS
        |--------------------------------------------------------------------------
        */

        $ticketsRaw = Ticket::query()
            ->select(
                DB::raw('DATE(issued_at) as date'),
                DB::raw('COUNT(*) as total')
            )
            ->whereDate('issued_at', '>=', $sevenDaysAgo)
            ->whereDate('issued_at', '<=', $today)
            ->groupBy(DB::raw('DATE(issued_at)'))
            ->orderBy('date')
            ->get()
            ->keyBy('date');


        $ticketsData = [];


        for ($i = 6; $i >= 0; $i--) {

            $date = today()->subDays($i);

            $key = $date->format('Y-m-d');

            $ticketsData[] = (int) (
                $ticketsRaw[$key]->total ?? 0
            );
        }


        /*
        |--------------------------------------------------------------------------
        | TICKET STATUS
        |--------------------------------------------------------------------------
        */

        $ticketStatus = Ticket::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck('total', 'status');


        /*
        |--------------------------------------------------------------------------
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        $paymentStatus = Payment::query()
            ->select(
                'status',
                DB::raw('COUNT(*) as total')
            )
            ->groupBy('status')
            ->pluck('total', 'status');


        /*
        |--------------------------------------------------------------------------
        | TOP PRODUCTS
        |--------------------------------------------------------------------------
        */

        $topProducts = DB::table('order_items')
            ->join(
                'orders',
                'orders.id',
                '=',
                'order_items.order_id'
            )
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_quantity'),
                DB::raw(
                    'SUM(order_items.subtotal) as total_sales'
                )
            )
            ->where(
                'orders.payment_status',
                'PAID'
            )
            ->whereDate(
                'orders.created_at',
                '>=',
                $sevenDaysAgo
            )
            ->whereDate(
                'orders.created_at',
                '<=',
                $today
            )
            ->groupBy(
                'order_items.product_name'
            )
            ->orderByDesc('total_quantity')
            ->limit(5)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RECENT ORDERS
        |--------------------------------------------------------------------------
        */

        $recentOrders = Order::query()
            ->latest('created_at')
            ->limit(8)
            ->get([
                'id',
                'order_number',
                'customer_name',
                'total_amount',
                'payment_status',
                'status',
                'created_at',
            ]);


        /*
        |--------------------------------------------------------------------------
        | RECENT SCANS
        |--------------------------------------------------------------------------
        */

        $recentScans = ScanRecord::query()
            ->with([
                'ticket:id,ticket_number,product_name,visit_date,status',
                'scannedBy:id,name',
            ])
            ->latest('scanned_at')
            ->limit(8)
            ->get();


        /*
        |--------------------------------------------------------------------------
        | RETURN
        |--------------------------------------------------------------------------
        */

        return view(
            'admin.dashboard',
            compact(
                'ordersToday',
                'paidOrdersToday',
                'pendingOrdersToday',
                'expiredOrdersToday',

                'revenueToday',

                'paymentsToday',
                'paidPaymentsToday',
                'pendingPaymentsToday',

                'ticketsToday',
                'activeTicketsToday',
                'usedTicketsToday',
                'expiredTicketsToday',

                'scansToday',
                'successfulScansToday',
                'failedScansToday',
                'scanSuccessRate',

                'invoicesToday',
                'issuedInvoicesToday',

                'totalProducts',
                'totalUsers',
                'activeDiscounts',

                'revenueLabels',
                'revenueData',
                'ordersData',
                'ticketsData',

                'ticketStatus',
                'paymentStatus',

                'topProducts',
                'recentOrders',
                'recentScans',
            )
        );
    }
}
