@extends('layouts.admin')

@section('title', 'Payment')

@section('breadcrumb')

    <nav class="page-breadcrumb">

        <ol class="breadcrumb">

            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">

                Payment

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
                                Payment
                            </h6>

                            <p class="text-muted mb-0">
                                Monitoring transaksi pembayaran customer.
                            </p>

                        </div>


                        {{-- EXPORT --}}

                        <div>

                            <button type="button" id="btn-export-payment" class="btn btn-success w-100 w-sm-auto">

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
                                    Filter Payment
                                </h6>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">


                                {{-- PAYMENT NUMBER --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-payment" class="form-label">
                                        Payment Number
                                    </label>

                                    <input type="text" id="filter-payment" class="form-control"
                                        placeholder="Cari payment...">

                                </div>



                                {{-- ORDER NUMBER --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-order" class="form-label">
                                        Order Number
                                    </label>

                                    <input type="text" id="filter-order" class="form-control"
                                        placeholder="Cari order...">

                                </div>



                                {{-- CUSTOMER --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-customer" class="form-label">
                                        Customer
                                    </label>

                                    <input type="text" id="filter-customer" class="form-control"
                                        placeholder="Nama / email / phone">

                                </div>



                                {{-- GATEWAY --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-gateway" class="form-label">
                                        Gateway
                                    </label>

                                    <select id="filter-gateway" class="form-select">

                                        <option value="">
                                            Semua Gateway
                                        </option>

                                        <option value="ESPAY">
                                            ESPAY
                                        </option>

                                    </select>

                                </div>



                                {{-- METHOD --}}

                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-method" class="form-label">
                                        Payment Method
                                    </label>

                                    <select id="filter-method" class="form-select">

                                        <option value="">
                                            Semua Method
                                        </option>

                                        <option value="QRIS">
                                            QRIS
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

                                        <option value="FAILED">
                                            FAILED
                                        </option>

                                        <option value="EXPIRED">
                                            EXPIRED
                                        </option>

                                        <option value="CANCELLED">
                                            CANCELLED
                                        </option>

                                        <option value="REFUNDED">
                                            REFUNDED
                                        </option>

                                    </select>

                                </div>



                                {{-- DATE FROM --}}

                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-date-from" class="form-label">
                                        Dari Tanggal
                                    </label>

                                    <input type="date" id="filter-date-from" class="form-control">

                                </div>



                                {{-- DATE TO --}}

                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-date-to" class="form-label">
                                        Sampai Tanggal
                                    </label>

                                    <input type="date" id="filter-date-to" class="form-control">

                                </div>



                                {{-- BUTTON --}}

                                <div
                                    class="col-12 col-lg-3
                                            d-flex
                                            align-items-end">

                                    <div class="d-flex gap-2 w-100">


                                        <button type="button" id="btn-filter" class="btn btn-primary flex-grow-1">

                                            <i data-feather="filter" class="me-1"></i>

                                            <span>
                                                Filter
                                            </span>

                                        </button>



                                        <button type="button" id="btn-reset" class="btn btn-outline-secondary"
                                            title="Reset Filter">

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

                        <table id="payment-table"
                            class="table table-bordered
                                   table-hover
                                   align-middle
                                   w-100">

                            <thead>

                                <tr>

                                    <th>
                                        Payment Number
                                    </th>

                                    <th>
                                        Order Number
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Gateway
                                    </th>

                                    <th>
                                        Method
                                    </th>

                                    <th>
                                        Amount
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Paid At
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
        | PAYMENT STATUS
        |--------------------------------------------------------------------------
        */

        .payment-status {

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

        .payment-action {

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


        .payment-action svg {

            width: 15px;

            height: 15px;

        }


        .payment-action:hover {

            color: #3f4bd8;

            background: #f1f3ff;

        }


        /*
        |--------------------------------------------------------------------------
        | DATE
        |--------------------------------------------------------------------------
        */

        .payment-date {

            white-space: nowrap;

            font-size: 12px;

        }


        /*
        |--------------------------------------------------------------------------
        | MOBILE
        |--------------------------------------------------------------------------
        */

        @media (max-width: 575.98px) {

            .payment-action {

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

                const filterPayment =
                    $('#filter-payment');

                const filterOrder =
                    $('#filter-order');

                const filterCustomer =
                    $('#filter-customer');

                const filterGateway =
                    $('#filter-gateway');

                const filterMethod =
                    $('#filter-method');

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
                    $('#btn-export-payment');



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
                    $('#payment-table').DataTable({

                        processing: true,

                        serverSide: true,

                        autoWidth: false,


                        ajax: {

                            url: "{{ route('super.payments.dt') }}",


                            data: function(d) {

                                d.payment_number =
                                    filterPayment.val();

                                d.order_number =
                                    filterOrder.val();

                                d.customer =
                                    filterCustomer.val();

                                d.gateway =
                                    filterGateway.val();

                                d.payment_method =
                                    filterMethod.val();

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
                                data: 'payment_number',

                                name: 'payment_number',

                                defaultContent: '-'

                            },


                            {
                                data: 'order_number',

                                name: 'order_number',

                                orderable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'customer_name',

                                name: 'customer_name',

                                orderable: false,

                                defaultContent: '-'

                            },


                            {
                                data: 'gateway',

                                name: 'gateway',

                                defaultContent: '-'

                            },


                            {
                                data: 'payment_method',

                                name: 'payment_method',

                                defaultContent: '-'

                            },


                            {
                                data: 'amount',

                                name: 'amount',

                                className: 'text-end',

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
                                data: 'paid_at',

                                name: 'paid_at',

                                defaultContent: '-'

                            },


                            {
                                data: 'created_at',

                                name: 'created_at',

                                defaultContent: '-',

                                render: function(data) {

                                    return `
                                <span class="payment-date">
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

                            [8, 'desc']

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

                            zeroRecords: 'Payment tidak ditemukan',

                            emptyTable: 'Tidak ada payment untuk tanggal yang dipilih',

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
                | FILTER
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
                | ENTER SEARCH
                |--------------------------------------------------------------------------
                */

                filterPayment
                    .add(filterOrder)
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

                        filterPayment.val('');

                        filterOrder.val('');

                        filterCustomer.val('');

                        filterGateway.val('');

                        filterMethod.val('');

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
                                "{{ route('super.payments.export') }}",
                                window.location.origin
                            );


                        const paymentNumber =
                            filterPayment.val();

                        const orderNumber =
                            filterOrder.val();

                        const customer =
                            filterCustomer.val();

                        const gateway =
                            filterGateway.val();

                        const paymentMethod =
                            filterMethod.val();

                        const status =
                            filterStatus.val();

                        const dateFrom =
                            filterDateFrom.val();

                        const dateTo =
                            filterDateTo.val();



                        if (paymentNumber) {

                            url.searchParams.set(
                                'payment_number',
                                paymentNumber
                            );

                        }


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


                        if (gateway) {

                            url.searchParams.set(
                                'gateway',
                                gateway
                            );

                        }


                        if (paymentMethod) {

                            url.searchParams.set(
                                'payment_method',
                                paymentMethod
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


                        /*
                        |--------------------------------------------------------------------------
                        | DOWNLOAD
                        |--------------------------------------------------------------------------
                        */

                        window.location.href =
                            url.toString();

                    }
                );


            });
    </script>
@endpush
