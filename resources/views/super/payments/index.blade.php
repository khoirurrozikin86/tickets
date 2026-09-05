@extends('layouts.admin')

@section('title', 'payment')

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

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h6 class="card-title mb-1">
                                Payment
                            </h6>

                            <p class="text-muted mb-0">
                                Monitoring transaksi pembayaran customer.
                            </p>

                        </div>

                        <div>

                            <a href="{{ route('super.payments.export') }}" id="btn-export-payment" class="btn btn-success">

                                <i data-feather="download"></i>

                                Export Excel

                            </a>

                        </div>

                    </div>


                    {{-- FILTER --}}
                    <div class="row mb-4">

                        {{-- Payment Number --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Payment Number
                            </label>

                            <input type="text" id="filter-payment" class="form-control" placeholder="Cari payment...">

                        </div>


                        {{-- Order Number --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Order Number
                            </label>

                            <input type="text" id="filter-order" class="form-control" placeholder="Cari order...">

                        </div>


                        {{-- Customer --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Customer
                            </label>

                            <input type="text" id="filter-customer" class="form-control"
                                placeholder="Nama / email / phone">

                        </div>


                        {{-- Gateway --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
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


                        {{-- Payment Method --}}
                        <div class="col-md-3 mb-3">

                            <label class="form-label">
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


                        {{-- Status --}}
                        <div class="col-md-2 mb-3">

                            <label class="form-label">
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


                        {{-- Dari --}}
                        <div class="col-md-2 mb-3">

                            <label class="form-label">
                                Dari
                            </label>

                            <input type="date" id="filter-date-from" class="form-control">

                        </div>


                        {{-- Sampai --}}
                        <div class="col-md-2 mb-3">

                            <label class="form-label">
                                Sampai
                            </label>

                            <input type="date" id="filter-date-to" class="form-control">

                        </div>


                        {{-- Filter Button --}}
                        <div class="col-md-2 mb-3 d-flex align-items-end">

                            <button type="button" id="btn-filter" class="btn btn-primary w-100" title="Filter">

                                <i data-feather="filter"></i>

                            </button>

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table id="payment-table" class="table table-bordered table-hover" style="width:100%">

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

                                    <th width="60">
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


@push('styles')
    <style>
        /*
         * Payment status
         */
        .payment-status {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
        }


        /*
         * Action icon
         */
        .payment-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 24px;
            height: 24px;

            color: #6571ff;
            text-decoration: none;
            border-radius: 4px;

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
    </style>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
             * Feather icon
             */
            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }


            /*
             * DataTable
             */
            const table = $('#payment-table').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: "{{ route('super.payments.dt') }}",

                    data: function(d) {

                        d.payment_number =
                            $('#filter-payment').val();

                        d.order_number =
                            $('#filter-order').val();

                        d.customer =
                            $('#filter-customer').val();

                        d.gateway =
                            $('#filter-gateway').val();

                        d.payment_method =
                            $('#filter-method').val();

                        d.status =
                            $('#filter-status').val();

                        d.date_from =
                            $('#filter-date-from').val();

                        d.date_to =
                            $('#filter-date-to').val();

                    }

                },


                columns: [

                    {
                        data: 'payment_number',
                        name: 'payment_number'
                    },

                    {
                        data: 'order_number',
                        name: 'order_number',
                        orderable: false
                    },

                    {
                        data: 'customer_name',
                        name: 'customer_name',
                        orderable: false
                    },

                    {
                        data: 'gateway',
                        name: 'gateway'
                    },

                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },

                    {
                        data: 'amount',
                        name: 'amount'
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'paid_at',
                        name: 'paid_at'
                    },

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }

                ],


                order: [
                    [8, 'desc']
                ],


                pageLength: 25,


                language: {

                    processing: 'Memuat...',

                    search: 'Cari:',

                    lengthMenu: 'Tampilkan _MENU_ data',

                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    infoEmpty: 'Tidak ada data',

                    zeroRecords: 'Payment tidak ditemukan',

                    paginate: {
                        first: 'Awal',
                        last: 'Akhir',
                        next: '›',
                        previous: '‹'
                    }

                }

            });


            /*
             * FILTER
             */
            $('#btn-filter').on('click', function() {

                table.ajax.reload();

            });


            /*
             * Enter pada Payment Number
             */
            $('#filter-payment').on('keypress', function(e) {

                if (e.which === 13) {

                    table.ajax.reload();

                }

            });


            /*
             * Enter pada Order Number
             */
            $('#filter-order').on('keypress', function(e) {

                if (e.which === 13) {

                    table.ajax.reload();

                }

            });


            /*
             * Enter pada Customer
             */
            $('#filter-customer').on('keypress', function(e) {

                if (e.which === 13) {

                    table.ajax.reload();

                }

            });


            /*
             * Reset otomatis ketika select/date berubah
             */
            $(
                '#filter-gateway, ' +
                '#filter-method, ' +
                '#filter-status, ' +
                '#filter-date-from, ' +
                '#filter-date-to'
            ).on('change', function() {

                table.ajax.reload();

            });


            /*
             * DETAIL PAYMENT
             */
            $(document).on('click', '.btn-payment-detail', function(e) {

                e.preventDefault();

                const url = $(this).attr('href');

                window.location.href = url;

            });


            /*
             * Feather setelah DataTable redraw
             */
            $('#payment-table').on(
                'draw.dt',
                function() {

                    if (
                        window.feather &&
                        typeof window.feather.replace === 'function'
                    ) {
                        window.feather.replace();
                    }

                }
            );


            /*
             * EXPORT EXCEL
             */
            $('#btn-export-payment').on('click', function(e) {

                e.preventDefault();

                const url = new URL(
                    $(this).attr('href'),
                    window.location.origin
                );


                url.searchParams.set(
                    'payment_number',
                    $('#filter-payment').val()
                );


                url.searchParams.set(
                    'order_number',
                    $('#filter-order').val()
                );


                url.searchParams.set(
                    'customer',
                    $('#filter-customer').val()
                );


                url.searchParams.set(
                    'gateway',
                    $('#filter-gateway').val()
                );


                url.searchParams.set(
                    'payment_method',
                    $('#filter-method').val()
                );


                url.searchParams.set(
                    'status',
                    $('#filter-status').val()
                );


                url.searchParams.set(
                    'date_from',
                    $('#filter-date-from').val()
                );


                url.searchParams.set(
                    'date_to',
                    $('#filter-date-to').val()
                );


                window.location.href = url.toString();

            });

        });
    </script>
@endpush
