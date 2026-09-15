@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">
                    Discount Usage History
                </h4>

                <div class="text-muted">
                    {{ $discount->code }}
                    — {{ $discount->name }}
                </div>
            </div>

            <a href="{{ route('super.discounts.index') }}" class="btn btn-secondary">
                <i data-feather="arrow-left"></i>
                Back
            </a>

        </div>


        {{-- SUMMARY --}}
        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            Code
                        </small>

                        <h5 class="mb-0">
                            {{ $discount->code }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            Usage
                        </small>

                        <h5 class="mb-0">
                            {{ $discount->usage_count }}
                            /
                            {{ $discount->usage_limit ?? '∞' }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            Type
                        </small>

                        <h5 class="mb-0">
                            {{ $discount->type }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">
                            Total Discount
                        </small>

                        <h5 class="mb-0">
                            Rp
                            {{ number_format((float) $discount->usages()->sum('discount_amount'), 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>


        {{-- FILTER --}}
        <div class="card mb-4">

            <div class="card-header">
                <strong>Filter Usage</strong>
            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- ORDER --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Order
                        </label>

                        <input type="text" id="filter-order" class="form-control" placeholder="Order number">

                    </div>


                    {{-- CUSTOMER --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Customer
                        </label>

                        <input type="text" id="filter-customer" class="form-control" placeholder="Customer name">

                    </div>


                    {{-- EMAIL --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Email
                        </label>

                        <input type="text" id="filter-email" class="form-control" placeholder="Customer email">

                    </div>


                    {{-- DATE FROM --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Date From
                        </label>

                        <input type="date" id="filter-date-from" class="form-control"
                            value="{{ now()->format('Y-m-d') }}">

                    </div>


                    {{-- DATE TO --}}
                    <div class="col-md-3">

                        <label class="form-label">
                            Date To
                        </label>

                        <input type="date" id="filter-date-to" class="form-control" value="{{ now()->format('Y-m-d') }}">

                    </div>


                    {{-- BUTTON --}}
                    <div class="col-md-9 d-flex align-items-end gap-2">

                        <button type="button" id="btn-filter" class="btn btn-primary">
                            <i data-feather="filter"></i>
                            Filter
                        </button>

                        <button type="button" id="btn-reset" class="btn btn-outline-secondary">
                            <i data-feather="refresh-cw"></i>
                            Reset
                        </button>

                        <button type="button" id="btn-export" class="btn btn-success">
                            <i data-feather="download"></i>
                            Export Excel
                        </button>

                    </div>

                </div>

            </div>

        </div>


        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">
                <strong>
                    Usage History
                </strong>
            </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="discount-usage-table" class="table table-hover" style="width:100%">

                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Discount</th>
                                <th>Used At</th>
                            </tr>
                        </thead>

                        <tbody>
                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        $(document).ready(function() {

            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            function getFilters() {

                return {
                    order_number: $('#filter-order').val(),
                    customer: $('#filter-customer').val(),
                    email: $('#filter-email').val(),
                    date_from: $('#filter-date-from').val(),
                    date_to: $('#filter-date-to').val(),
                };

            }


            /*
            |--------------------------------------------------------------------------
            | DATATABLE
            |--------------------------------------------------------------------------
            */

            const table = $('#discount-usage-table').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                ajax: {
                    url: "{{ route('super.discounts.usages.dt', $discount->id) }}",

                    data: function(d) {

                        const filters = getFilters();

                        d.order_number = filters.order_number;
                        d.customer = filters.customer;
                        d.email = filters.email;
                        d.date_from = filters.date_from;
                        d.date_to = filters.date_to;

                    }
                },

                columns: [

                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
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
                        data: 'customer_email',
                        name: 'customer_email',
                        orderable: false
                    },

                    {
                        data: 'discount_amount',
                        name: 'discount_amount',
                        orderable: true
                    },

                    {
                        data: 'used_at',
                        name: 'used_at',
                        orderable: true
                    }

                ],

                order: [
                    [5, 'desc']
                ],

                pageLength: 25,

                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],

                language: {

                    processing: 'Memuat...',

                    lengthMenu: 'Tampilkan _MENU_ data',

                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    infoEmpty: 'Tidak ada data',

                    zeroRecords: 'Data discount usage tidak ditemukan',

                    emptyTable: 'Belum ada penggunaan discount',

                    paginate: {
                        first: 'Awal',
                        last: 'Akhir',
                        next: '›',
                        previous: '‹'
                    }

                }

            });


            /*
            |--------------------------------------------------------------------------
            | FILTER BUTTON
            |--------------------------------------------------------------------------
            */

            $('#btn-filter').on('click', function() {

                table.ajax.reload(null, true);

            });


            /*
            |--------------------------------------------------------------------------
            | RESET
            |--------------------------------------------------------------------------
            */

            $('#btn-reset').on('click', function() {

                $('#filter-order').val('');
                $('#filter-customer').val('');
                $('#filter-email').val('');

                const today =
                    new Date().toISOString().split('T')[0];

                $('#filter-date-from').val(today);
                $('#filter-date-to').val(today);

                table.ajax.reload(null, true);

            });


            /*
            |--------------------------------------------------------------------------
            | ENTER
            |--------------------------------------------------------------------------
            */

            $(
                '#filter-order, ' +
                '#filter-customer, ' +
                '#filter-email'
            ).on('keypress', function(e) {

                if (e.which === 13) {
                    table.ajax.reload(null, true);
                }

            });


            /*
            |--------------------------------------------------------------------------
            | EXPORT
            |--------------------------------------------------------------------------
            */

            $('#btn-export').on('click', function() {

                const filters = getFilters();

                const params = new URLSearchParams();

                Object.keys(filters).forEach(function(key) {

                    if (
                        filters[key] !== null &&
                        filters[key] !== ''
                    ) {
                        params.append(
                            key,
                            filters[key]
                        );
                    }

                });

                const baseUrl =
                    "{{ route('super.discounts.usages.export', $discount->id) }}";

                const url =
                    params.toString() ?
                    baseUrl + '?' + params.toString() :
                    baseUrl;

                window.location.href = url;

            });


            /*
            |--------------------------------------------------------------------------
            | FEATHER
            |--------------------------------------------------------------------------
            */

            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }

        });
    </script>
@endpush
