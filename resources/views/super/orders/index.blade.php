@extends('layouts.admin')

@section('title', 'order')

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
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h6 class="card-title mb-1">Order</h6>

                            <p class="text-muted mb-0">
                                Monitoring transaksi dan pembayaran customer.
                            </p>
                        </div>

                        <div>
                            <a href="{{ route('super.orders.export') }}" id="btn-export-order" class="btn btn-success">

                                <i data-feather="download"></i>

                                Export Excel
                            </a>
                        </div>
                    </div>


                    {{-- FILTER --}}
                    <div class="row mb-4">

                        {{-- ORDER NUMBER --}}
                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                Order Number
                            </label>

                            <input type="text" id="filter-order" class="form-control" placeholder="Cari order...">
                        </div>


                        {{-- CUSTOMER --}}
                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Customer
                            </label>

                            <input type="text" id="filter-customer" class="form-control" placeholder="Nama customer...">
                        </div>


                        {{-- PAYMENT STATUS --}}
                        <div class="col-md-2 mb-3">
                            <label class="form-label">
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


                        {{-- ORDER STATUS --}}
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


                        {{-- DARI --}}
                        <div class="col-md-1 mb-3">
                            <label class="form-label">
                                Dari
                            </label>

                            <input type="date" id="filter-date-from" class="form-control">
                        </div>


                        {{-- SAMPAI --}}
                        <div class="col-md-1 mb-3">
                            <label class="form-label">
                                Sampai
                            </label>

                            <input type="date" id="filter-date-to" class="form-control">
                        </div>


                        {{-- FILTER BUTTON --}}
                        <div class="col-md-1 mb-3 d-flex align-items-end">

                            <button type="button" id="btn-filter" class="btn btn-primary w-100" title="Filter">
                                <i data-feather="filter"></i>
                            </button>

                        </div>

                    </div>


                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table id="order-table" class="table table-bordered table-hover" style="width:100%">

                            <thead>

                                <tr>
                                    <th>Order Number</th>
                                    <th>Customer</th>
                                    <th>Item</th>
                                    <th>Total</th>
                                    <th>Payment</th>
                                    <th>Status</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
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
                                                     * ORDER NUMBER
                                                     */
        .order-number {
            font-weight: 600;
            color: #3949ab;
        }


        /*
                                                     * CUSTOMER
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
                                                     * ITEMS
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
                                                     * TOTAL
                                                     */
        .order-total {
            font-weight: 600;
            white-space: nowrap;
        }


        /*
                                                     * STATUS
                                                     */
        .order-status {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
            white-space: nowrap;
        }


        /*
                                                     * PAYMENT STATUS
                                                     */
        .order-payment-status {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
            white-space: nowrap;
        }


        /*
                                                     * ACTION ICON
                                                     */
        .order-action {
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


        .order-action svg {
            width: 15px;
            height: 15px;
        }


        .order-action:hover {
            color: #3f4bd8;
            background: #f1f3ff;
        }


        /*
                                                     * DATE
                                                     */
        .order-date {
            white-space: nowrap;
            font-size: 12px;
        }
    </style>
@endpush


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /*
             * FEATHER ICON
             */
            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }


            /*
             * DATATABLE
             */
            const table = $('#order-table').DataTable({

                processing: true,

                serverSide: true,

                ajax: {

                    url: "{{ route('super.orders.dt') }}",

                    data: function(d) {

                        d.order_number =
                            $('#filter-order').val();

                        d.customer =
                            $('#filter-customer').val();

                        d.payment_status =
                            $('#filter-payment').val();

                        d.status =
                            $('#filter-status').val();

                        d.date_from =
                            $('#filter-date-from').val();

                        d.date_to =
                            $('#filter-date-to').val();

                    }

                },


                columns: [

                    /*
                     * ORDER NUMBER
                     */
                    {
                        data: 'order_number',
                        name: 'order_number',

                        render: function(data) {

                            return `
                        <span class="order-number">
                            ${data ?? '-'}
                        </span>
                    `;

                        }
                    },


                    /*
                     * CUSTOMER
                     */
                    {
                        data: 'customer',
                        name: 'customer',
                        orderable: false,
                        searchable: false
                    },


                    /*
                     * ITEMS
                     */
                    {
                        data: 'items',
                        name: 'items',
                        orderable: false,
                        searchable: false
                    },


                    /*
                     * TOTAL
                     */
                    {
                        data: 'total_amount',
                        name: 'total_amount',
                        className: 'text-end'
                    },


                    /*
                     * PAYMENT
                     */
                    {
                        data: 'payment_status',
                        name: 'payment_status',

                        orderable: false,
                        searchable: false

                    },


                    /*
                     * ORDER STATUS
                     */
                    {
                        data: 'status',
                        name: 'status',

                        orderable: false,
                        searchable: false

                    },


                    /*
                     * CREATED AT
                     */
                    {
                        data: 'created_at',
                        name: 'created_at',

                        render: function(data) {

                            return `
                        <span class="order-date">
                            ${data ?? '-'}
                        </span>
                    `;

                        }

                    },


                    /*
                     * ACTION
                     */
                    {
                        data: 'actions',
                        name: 'actions',

                        orderable: false,
                        searchable: false,

                        className: 'text-center'

                    }

                ],


                /*
                 * DEFAULT ORDER
                 */
                order: [
                    [6, 'desc']
                ],


                pageLength: 25,


                language: {

                    processing: 'Memuat...',

                    search: 'Cari:',

                    lengthMenu: 'Tampilkan _MENU_ data',

                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    infoEmpty: 'Tidak ada data',

                    zeroRecords: 'Order tidak ditemukan',

                    paginate: {

                        first: 'Awal',

                        last: 'Akhir',

                        next: '›',

                        previous: '‹'

                    }

                }

            });


            /*
             * FILTER BUTTON
             */
            $('#btn-filter').on('click', function() {

                table.ajax.reload();

            });


            /*
             * ENTER ORDER NUMBER
             */
            $('#filter-order, #filter-customer')
                .on('keypress', function(e) {

                    if (e.which === 13) {

                        table.ajax.reload();

                    }

                });


            /*
             * AUTO FILTER
             */
            $('#filter-payment, #filter-status, #filter-date-from, #filter-date-to')
                .on('change', function() {

                    table.ajax.reload();

                });


            /*
             * DETAIL ORDER
             */
            $(document).on('click', '.btn-order-detail', function(e) {

                e.preventDefault();

                const url = $(this).attr('href');

                window.location.href = url;

            });


            /*
             * FEATHER AFTER DATATABLE DRAW
             */
            $('#order-table').on('draw.dt', function() {

                if (
                    window.feather &&
                    typeof window.feather.replace === 'function'
                ) {

                    window.feather.replace();

                }

            });

        });




        $('#btn-export-order').on('click', function(e) {

            e.preventDefault();

            const url = new URL($(this).attr('href'), window.location.origin);

            url.searchParams.set(
                'order_number',
                $('#filter-order').val()
            );

            url.searchParams.set(
                'customer',
                $('#filter-customer').val()
            );

            url.searchParams.set(
                'payment_status',
                $('#filter-payment').val()
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
    </script>
@endpush
