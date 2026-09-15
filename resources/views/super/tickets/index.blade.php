@extends('layouts.admin')

@section('title', 'Ticket')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Ticket
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

                        {{-- Title --}}
                        <div>
                            <h6 class="card-title mb-1">
                                Ticket
                            </h6>

                            <p class="text-muted mb-0">
                                Monitoring dan kontrol e-ticket customer.
                            </p>
                        </div>


                        {{-- Export --}}
                        <div>

                            <button type="button" id="btn-export-ticket" class="btn btn-success w-100 w-sm-auto">

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
                                    Filter Ticket
                                </h6>

                            </div>

                        </div>


                        <div class="card-body">

                            <div class="row g-3">

                                {{-- Ticket Number --}}
                                <div class="col-12 col-md-6 col-lg-3">

                                    <label for="filter-ticket" class="form-label">
                                        Ticket Number
                                    </label>

                                    <input type="text" id="filter-ticket" class="form-control"
                                        placeholder="Cari ticket...">

                                </div>


                                {{-- Product --}}
                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-product" class="form-label">
                                        Produk
                                    </label>

                                    <select id="filter-product" class="form-select">

                                        <option value="">
                                            Semua Produk
                                        </option>

                                        @foreach ($products as $product)
                                            <option value="{{ $product->id }}">
                                                {{ $product->name }}
                                            </option>
                                        @endforeach

                                    </select>

                                </div>


                                {{-- Status --}}
                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-status" class="form-label">
                                        Status
                                    </label>

                                    <select id="filter-status" class="form-select">

                                        <option value="">
                                            Semua Status
                                        </option>

                                        <option value="ACTIVE">
                                            ACTIVE
                                        </option>

                                        <option value="USED">
                                            USED
                                        </option>

                                        <option value="CANCELLED">
                                            CANCELLED
                                        </option>

                                        <option value="EXPIRED">
                                            EXPIRED
                                        </option>

                                    </select>

                                </div>


                                {{-- Date From --}}
                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-date-from" class="form-label">
                                        Dari Tanggal
                                    </label>

                                    <input type="date" id="filter-date-from" class="form-control">

                                </div>


                                {{-- Date To --}}
                                <div class="col-12 col-md-6 col-lg-2">

                                    <label for="filter-date-to" class="form-label">
                                        Sampai Tanggal
                                    </label>

                                    <input type="date" id="filter-date-to" class="form-control">

                                </div>


                                {{-- Buttons --}}
                                <div class="col-12 col-lg-1 d-flex align-items-end">

                                    <div class="d-flex gap-2 w-100">

                                        <button type="button" id="btn-filter" class="btn btn-primary flex-grow-1"
                                            title="Filter">

                                            <i data-feather="filter"></i>

                                            <span class="d-lg-none ms-1">
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

                        <table id="ticket-table" class="table table-bordered table-hover align-middle w-100">

                            <thead>

                                <tr>

                                    <th>
                                        Ticket Number
                                    </th>

                                    <th>
                                        Produk
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Visit Date
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Scan
                                    </th>

                                    <th>
                                        Issued At
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
            | Ticket Status
            |--------------------------------------------------------------------------
            */

        .ticket-status {

            font-size: 11px;

            font-weight: 600;

            padding: 5px 8px;

            border-radius: 5px;

        }


        /*
            |--------------------------------------------------------------------------
            | Ticket Action
            |--------------------------------------------------------------------------
            */

        .ticket-action {

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


        .ticket-action svg {

            width: 15px;

            height: 15px;

        }


        .ticket-action:hover {

            color: #3f4bd8;

            background: #f1f3ff;

        }


        .ticket-action.cancel {

            color: #dc3545;

        }


        .ticket-action.cancel:hover {

            color: #bb2d3b;

            background: #fff1f2;

        }


        /*
            |--------------------------------------------------------------------------
            | Mobile
            |--------------------------------------------------------------------------
            */

        @media (max-width: 575.98px) {

            .ticket-action {

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
                | Feather
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
                | Elements
                |--------------------------------------------------------------------------
                */

                const filterTicket =
                    $('#filter-ticket');

                const filterProduct =
                    $('#filter-product');

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
                    $('#btn-export-ticket');


                /*
                |--------------------------------------------------------------------------
                | Today
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
                | Default Filter = Today
                |--------------------------------------------------------------------------
                */

                function setDefaultDate() {

                    const today = getToday();

                    filterDateFrom.val(today);

                    filterDateTo.val(today);

                }


                setDefaultDate();


                /*
                |--------------------------------------------------------------------------
                | DataTable
                |--------------------------------------------------------------------------
                */

                const table = $('#ticket-table').DataTable({

                    processing: true,

                    serverSide: true,

                    autoWidth: false,

                    ajax: {

                        url: "{{ route('super.tickets.dt') }}",

                        data: function(d) {

                            d.ticket_number =
                                filterTicket.val();

                            d.product_id =
                                filterProduct.val();

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
                    | Columns
                    |--------------------------------------------------------------------------
                    */

                    columns: [

                        {
                            data: 'ticket_number',

                            name: 'ticket_number',

                            defaultContent: '-'
                        },


                        {
                            data: 'product_name',

                            name: 'product_name',

                            defaultContent: '-'
                        },


                        {
                            data: 'customer_name',

                            name: 'order.customer_name',

                            defaultContent: '-'
                        },


                        {
                            data: 'visit_date',

                            name: 'visit_date',

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
                            data: 'scan',

                            name: 'scan',

                            orderable: false,

                            searchable: false,

                            defaultContent: '-'
                        },


                        {
                            data: 'issued_at',

                            name: 'issued_at',

                            defaultContent: '-'
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
                    | Default Order
                    |--------------------------------------------------------------------------
                    */

                    order: [

                        [6, 'desc']

                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | Page Length
                    |--------------------------------------------------------------------------
                    */

                    pageLength: 25,

                    lengthMenu: [

                        [10, 25, 50, 100],

                        [10, 25, 50, 100]

                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | Language
                    |--------------------------------------------------------------------------
                    */

                    language: {

                        processing: 'Memuat...',

                        search: 'Cari:',

                        lengthMenu: 'Tampilkan _MENU_ data',

                        info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                        infoEmpty: 'Tidak ada data',

                        zeroRecords: 'Ticket tidak ditemukan',

                        emptyTable: 'Tidak ada ticket untuk tanggal yang dipilih',

                        paginate: {

                            first: 'Awal',

                            last: 'Akhir',

                            next: '›',

                            previous: '‹'

                        }

                    },

                    drawCallback: function() {

                        refreshFeather();

                    }

                });


                /*
                |--------------------------------------------------------------------------
                | Filter
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
                | Enter = Filter
                |--------------------------------------------------------------------------
                */

                filterTicket.on(
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
                | Reset
                |--------------------------------------------------------------------------
                */

                btnReset.on(
                    'click',
                    function() {

                        filterTicket.val('');

                        filterProduct.val('');

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
                | Export Excel
                |--------------------------------------------------------------------------
                */

                btnExport.on(
                    'click',
                    function(e) {

                        e.preventDefault();


                        const url =
                            new URL(
                                "{{ route('super.tickets.export') }}",
                                window.location.origin
                            );


                        /*
                        |--------------------------------------------------------------------------
                        | Current Filters
                        |--------------------------------------------------------------------------
                        */

                        const ticketNumber =
                            filterTicket.val();

                        const productId =
                            filterProduct.val();

                        const status =
                            filterStatus.val();

                        const dateFrom =
                            filterDateFrom.val();

                        const dateTo =
                            filterDateTo.val();


                        if (ticketNumber) {

                            url.searchParams.set(
                                'ticket_number',
                                ticketNumber
                            );

                        }


                        if (productId) {

                            url.searchParams.set(
                                'product_id',
                                productId
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
                        | Download
                        |--------------------------------------------------------------------------
                        */

                        window.location.href =
                            url.toString();

                    }
                );


            });
    </script>
@endpush
