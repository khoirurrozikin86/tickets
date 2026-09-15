@extends('layouts.admin')

@section('title', 'Order')

@section('breadcrumb')

    <nav class="page-breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">

                Order

            </li>

        </ol>

    </nav>

@endsection


@section('content')

    <div class="row">

        <div class="col-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">


                    {{-- =====================================================
                    HEADER
                    ====================================================== --}}

                    <div
                        class="d-flex flex-column flex-lg-row
                                justify-content-between
                                align-items-lg-center
                                gap-3
                                mb-4">

                        <div>

                            <h6 class="card-title mb-1">
                                Order
                            </h6>

                            <p class="text-muted mb-0">
                                Monitoring transaksi dan pembayaran customer.
                            </p>

                        </div>


                        <div>

                            <button type="button" id="btn-export-order" class="btn btn-success w-100 w-sm-auto">

                                <i data-feather="download" class="me-1"></i>

                                Export Excel

                            </button>

                        </div>

                    </div>



                    {{-- =====================================================
                    FILTER
                    ====================================================== --}}

                    <div class="card border mb-4">

                        <div class="card-header bg-light">

                            <div class="d-flex align-items-center gap-2">

                                <i data-feather="filter"></i>

                                <h6 class="mb-0">
                                    Filter Order
                                </h6>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- ORDER NUMBER --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-order" class="form-label">
                                        Order Number
                                    </label>

                                    <input type="text" id="filter-order" class="form-control"
                                        placeholder="Cari order...">

                                </div>



                                {{-- CUSTOMER --}}

                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-customer" class="form-label">
                                        Customer
                                    </label>

                                    <input type="text" id="filter-customer" class="form-control"
                                        placeholder="Nama customer...">

                                </div>



                                {{-- PAYMENT --}}

                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-payment" class="form-label">
                                        Payment
                                    </label>

                                    <select id="filter-payment" class="form-select">

                                        <option value="">
                                            Semua Payment
                                        </option>

                                        <option value="UNPAID">
                                            UNPAID
                                        </option>

                                        <option value="PENDING">
                                            PENDING
                                        </option>

                                        <option value="PAID">
                                            PAID
                                        </option>

                                        <option value="FAILED">
                                            FAILED
                                        </option>

                                        <option value="EXPIRED">
                                            EXPIRED
                                        </option>

                                        <option value="REFUNDED">
                                            REFUNDED
                                        </option>

                                    </select>

                                </div>



                                {{-- STATUS --}}

                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-status" class="form-label">
                                        Status
                                    </label>

                                    <select id="filter-status" class="form-select">

                                        <option value="">
                                            Semua Status
                                        </option>

                                        <option value="PENDING">
                                            PENDING
                                        </option>

                                        <option value="PAID">
                                            PAID
                                        </option>

                                        <option value="COMPLETED">
                                            COMPLETED
                                        </option>

                                        <option value="CANCELLED">
                                            CANCELLED
                                        </option>

                                        <option value="EXPIRED">
                                            EXPIRED
                                        </option>

                                        <option value="REFUNDED">
                                            REFUNDED
                                        </option>

                                    </select>

                                </div>



                                {{-- DATE FROM --}}

                                <div class="col-12 col-md-6 col-lg-1">

                                    <label for="filter-date-from" class="form-label">
                                        Dari
                                    </label>

                                    <input type="date" id="filter-date-from" class="form-control">

                                </div>



                                {{-- DATE TO --}}

                                <div class="col-12 col-md-6 col-lg-1">

                                    <label for="filter-date-to" class="form-label">
                                        Sampai
                                    </label>

                                    <input type="date" id="filter-date-to" class="form-control">

                                </div>



                                {{-- BUTTON --}}

                                <div
                                    class="col-12 col-lg-1
                                            d-flex
                                            align-items-end">

                                    <div class="d-flex gap-2 w-100">


                                        <button type="button" id="btn-filter" class="btn btn-primary flex-grow-1"
                                            title="Filter">

                                            <i data-feather="filter"></i>

                                            <span class="d-lg-none ms-1">
                                                Filter
                                            </span>

                                        </button>



                                        <button type="button" id="btn-reset" class="btn btn-outline-secondary"
                                            title="Reset">

                                            <i data-feather="refresh-cw"></i>

                                        </button>


                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- =====================================================
                    TABLE
                    ====================================================== --}}

                    <div class="table-responsive">

                        <table id="order-table"
                            class="table table-bordered
                                   table-hover
                                   align-middle
                                   w-100">

                            <thead>

                                <tr>

                                    <th>
                                        Order Number
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Item
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Payment
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Created At
                                    </th>

                                    <th>
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection



{{-- =============================================================
STYLES
============================================================= --}}

@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | ORDER NUMBER
        |--------------------------------------------------------------------------
        */

        .order-number {

            font-weight: 600;

            color: #3949ab;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | CUSTOMER
        |--------------------------------------------------------------------------
        */

        .order-customer-name {

            font-weight: 500;

        }


        .order-customer-email {

            font-size: 11px;

            color: #8c8c8c;

            margin-top: 2px;

        }


        /*
        |--------------------------------------------------------------------------
        | ITEMS
        |--------------------------------------------------------------------------
        */

        .order-items {

            line-height: 1.5;

        }


        .order-item {

            display: flex;

            align-items: center;

            justify-content: space-between;

            gap: 10px;

            margin-bottom: 2px;

        }


        .order-item:last-child {

            margin-bottom: 0;

        }


        .order-item-name {

            font-size: 12px;

            color: #495057;

        }


        .order-item-qty {

            font-size: 11px;

            color: #8c8c8c;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | TOTAL
        |--------------------------------------------------------------------------
        */

        .order-total {

            font-weight: 600;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | STATUS
        |--------------------------------------------------------------------------
        */

        .order-status,
        .order-payment-status {

            font-size: 11px;

            font-weight: 600;

            padding: 5px 8px;

            border-radius: 5px;

            white-space: nowrap;

        }


        /*
        |--------------------------------------------------------------------------
        | ACTION
        |--------------------------------------------------------------------------
        */

        .order-action {

            display: inline-flex;

            align-items: center;

            justify-content: center;

            width: 30px;

            height: 30px;

            color: #6571ff;

            text-decoration: none;

            border-radius: 5px;

            transition: all .15s ease;

        }


        .order-action svg {

            width: 15px;

            height: 15px;

        }


        .order-action:hover {

            color: #3f4bd8;

            background: #f1f3ff;

        }


        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        .order-date {

            white-space: nowrap;

            font-size: 12px;

        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 575.98px) {

            .order-action {

                width: 28px;

                height: 28px;

            }

        }
    </style>
@endpush



{{-- =============================================================
SCRIPTS
============================================================= --}}

@push('scripts')
    <script>
        document.addEventListener(
            'DOMContentLoaded',
            function() {


                /*
                |--------------------------------------------------------------------------
                | FEATHER
                |--------------------------------------------------------------------------
                */

                function refreshFeather() {

                    if (
                        window.feather &&
                        typeof window.feather.replace === 'function'
                    ) {

                        window.feather.replace();

                    }

                }


                refreshFeather();



                /*
                |--------------------------------------------------------------------------
                | ELEMENTS
                |--------------------------------------------------------------------------
                */

                const filterOrder =
                    $('#filter-order');

                const filterCustomer =
                    $('#filter-customer');

                const filterPayment =
                    $('#filter-payment');

                const filterStatus =
                    $('#filter-status');

                const filterDateFrom =
                    $('#filter-date-from');

                const filterDateTo =
                    $('#filter-date-to');

                const btnFilter =
                    $('#btn-filter');

                const btnReset =
                    $('#btn-reset');

                const btnExport =
                    $('#btn-export-order');



                /*
                |--------------------------------------------------------------------------
                | TODAY
                |--------------------------------------------------------------------------
                */

                function getToday() {

                    const now = new Date();

                    const year =
                        now.getFullYear();

                    const month =
                        String(
                            now.getMonth() + 1
                        ).padStart(2, '0');

                    const day =
                        String(
                            now.getDate()
                        ).padStart(2, '0');

                    return `${year}-${month}-${day}`;

                }



                /*
                |--------------------------------------------------------------------------
                | DEFAULT DATE
                |--------------------------------------------------------------------------
                */

                function setDefaultDate() {

                    const today =
                        getToday();

                    filterDateFrom.val(today);

                    filterDateTo.val(today);

                }


                setDefaultDate();



                /*
                |--------------------------------------------------------------------------
                | DATATABLE
                |--------------------------------------------------------------------------
                */

                const table =
                    $('#order-table').DataTable({

                        processing: true,

                        serverSide: true,

                        autoWidth: false,


                        ajax: {

                            url: "{{ route('super.orders.dt') }}",


                            data: function(d) {

                                d.order_number =
                                    filterOrder.val();

                                d.customer =
                                    filterCustomer.val();

                                d.payment_status =
                                    filterPayment.val();

                                d.status =
                                    filterStatus.val();

                                d.date_from =
                                    filterDateFrom.val();

                                d.date_to =
                                    filterDateTo.val();

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | COLUMNS
                        |--------------------------------------------------------------------------
                        */

                        columns: [

                            {
                                data: 'order_number',

                                name: 'order_number',

                                defaultContent: '-',

                                render: function(data) {

                                    return `
                                <span class="order-number">
                                    ${data ?? '-'}
                                </span>
                            `;

                                }

                            },


                            {
                                data: 'customer',

                                name: 'customer',

                                orderable: false,

                                searchable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'items',

                                name: 'items',

                                orderable: false,

                                searchable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'total_amount',

                                name: 'total_amount',

                                className: 'text-end',

                                defaultContent: '-'

                            },


                            {
                                data: 'payment_status',

                                name: 'payment_status',

                                orderable: false,

                                searchable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'status',

                                name: 'status',

                                orderable: false,

                                searchable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'created_at',

                                name: 'created_at',

                                defaultContent: '-',

                                render: function(data) {

                                    return `
                                <span class="order-date">
                                    ${data ?? '-'}
                                </span>
                            `;

                                }

                            },


                            {
                                data: 'actions',

                                name: 'actions',

                                orderable: false,

                                searchable: false,

                                className: 'text-center',

                                defaultContent: '-'

                            }

                        ],



                        /*
                        |--------------------------------------------------------------------------
                        | DEFAULT ORDER
                        |--------------------------------------------------------------------------
                        */

                        order: [

                            [6, 'desc']

                        ],



                        /*
                        |--------------------------------------------------------------------------
                        | PAGINATION
                        |--------------------------------------------------------------------------
                        */

                        pageLength: 25,

                        lengthMenu: [

                            [10, 25, 50, 100],

                            [10, 25, 50, 100]

                        ],



                        /*
                        |--------------------------------------------------------------------------
                        | LANGUAGE
                        |--------------------------------------------------------------------------
                        */

                        language: {

                            processing: 'Memuat...',

                            search: 'Cari:',

                            lengthMenu: 'Tampilkan _MENU_ data',

                            info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                            infoEmpty: 'Tidak ada data',

                            zeroRecords: 'Order tidak ditemukan',

                            emptyTable: 'Tidak ada order untuk tanggal yang dipilih',

                            paginate: {

                                first: 'Awal',

                                last: 'Akhir',

                                next: '›',

                                previous: '‹'

                            }

                        },


                        /*
                        |--------------------------------------------------------------------------
                        | DRAW
                        |--------------------------------------------------------------------------
                        */

                        drawCallback: function() {

                            refreshFeather();

                        }

                    });



                /*
                |--------------------------------------------------------------------------
                | FILTER BUTTON
                |--------------------------------------------------------------------------
                */

                btnFilter.on(
                    'click',
                    function() {

                        table.ajax.reload(
                            null,
                            true
                        );

                    }
                );



                /*
                |--------------------------------------------------------------------------
                | ENTER
                |--------------------------------------------------------------------------
                */

                filterOrder
                    .add(filterCustomer)
                    .on(
                        'keypress',
                        function(e) {

                            if (e.which === 13) {

                                e.preventDefault();

                                table.ajax.reload(
                                    null,
                                    true
                                );

                            }

                        }
                    );



                /*
                |--------------------------------------------------------------------------
                | RESET
                |--------------------------------------------------------------------------
                */

                btnReset.on(
                    'click',
                    function() {

                        filterOrder.val('');

                        filterCustomer.val('');

                        filterPayment.val('');

                        filterStatus.val('');

                        setDefaultDate();

                        table.search('');

                        table.ajax.reload(
                            null,
                            true
                        );

                    }
                );



                /*
                |--------------------------------------------------------------------------
                | EXPORT EXCEL
                |--------------------------------------------------------------------------
                */

                btnExport.on(
                    'click',
                    function(e) {

                        e.preventDefault();


                        const url =
                            new URL(
                                "{{ route('super.orders.export') }}",
                                window.location.origin
                            );


                        const orderNumber =
                            filterOrder.val();

                        const customer =
                            filterCustomer.val();

                        const paymentStatus =
                            filterPayment.val();

                        const status =
                            filterStatus.val();

                        const dateFrom =
                            filterDateFrom.val();

                        const dateTo =
                            filterDateTo.val();



                        if (orderNumber) {

                            url.searchParams.set(
                                'order_number',
                                orderNumber
                            );

                        }


                        if (customer) {

                            url.searchParams.set(
                                'customer',
                                customer
                            );

                        }


                        if (paymentStatus) {

                            url.searchParams.set(
                                'payment_status',
                                paymentStatus
                            );

                        }


                        if (status) {

                            url.searchParams.set(
                                'status',
                                status
                            );

                        }


                        if (dateFrom) {

                            url.searchParams.set(
                                'date_from',
                                dateFrom
                            );

                        }


                        if (dateTo) {

                            url.searchParams.set(
                                'date_to',
                                dateTo
                            );

                        }


                        window.location.href =
                            url.toString();

                    }
                );


            });
    </script>
@endpush
