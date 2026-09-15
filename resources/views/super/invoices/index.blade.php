@extends('layouts.admin')

@section('title', 'Invoice')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Transaksi</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Invoice
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
                    <div class="d-flex flex-column flex-md-row align-items-md-center justify-content-between mb-4">

                        <div>
                            <h6 class="card-title mb-1">
                                Invoice
                            </h6>

                            <p class="text-muted mb-0">
                                Monitoring invoice transaksi customer.
                            </p>
                        </div>

                    </div>


                    {{-- =====================================================
                        FILTER
                    ====================================================== --}}
                    <div class="invoice-filter-card mb-4">

                        <div class="row g-3">

                            {{-- Invoice Number --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-invoice" class="form-label">
                                    Invoice Number
                                </label>

                                <input type="text" id="filter-invoice" class="form-control" placeholder="Cari invoice..."
                                    autocomplete="off">

                            </div>


                            {{-- Order Number --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-order" class="form-label">
                                    Order Number
                                </label>

                                <input type="text" id="filter-order" class="form-control" placeholder="Cari order..."
                                    autocomplete="off">

                            </div>


                            {{-- Customer --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-customer" class="form-label">
                                    Customer
                                </label>

                                <input type="text" id="filter-customer" class="form-control"
                                    placeholder="Nama / email / phone" autocomplete="off">

                            </div>


                            {{-- Status --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-status" class="form-label">
                                    Status
                                </label>

                                <select id="filter-status" class="form-select">
                                    <option value="">
                                        Semua Status
                                    </option>

                                    <option value="ISSUED">
                                        ISSUED
                                    </option>

                                    <option value="CANCELLED">
                                        CANCELLED
                                    </option>

                                    <option value="VOID">
                                        VOID
                                    </option>

                                </select>

                            </div>


                            {{-- Date From --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-date-from" class="form-label">
                                    Dari
                                </label>

                                <input type="date" id="filter-date-from" class="form-control">

                            </div>


                            {{-- Date To --}}
                            <div class="col-12 col-sm-6 col-lg-3">

                                <label for="filter-date-to" class="form-label">
                                    Sampai
                                </label>

                                <input type="date" id="filter-date-to" class="form-control">

                            </div>


                            {{-- ACTION BUTTON --}}
                            <div class="col-12 col-lg-6">

                                <label class="form-label d-none d-lg-block">
                                    &nbsp;
                                </label>

                                <div class="invoice-filter-actions">

                                    {{-- Filter --}}
                                    <button type="button" id="btn-filter" class="btn btn-primary">
                                        <i data-feather="filter"></i>
                                        <span>Filter</span>
                                    </button>


                                    {{-- Reset --}}
                                    <button type="button" id="btn-reset" class="btn btn-outline-secondary">
                                        <i data-feather="refresh-cw"></i>
                                        <span>Reset</span>
                                    </button>


                                    {{-- Export --}}
                                    <button type="button" id="btnExportExcel" class="btn btn-success">
                                        <i data-feather="file-text"></i>
                                        <span>Export Excel</span>
                                    </button>

                                </div>

                            </div>

                        </div>

                    </div>


                    {{-- =====================================================
                        TABLE
                    ====================================================== --}}
                    <div class="table-responsive">

                        <table id="invoice-table" class="table table-bordered table-hover align-middle" style="width:100%">

                            <thead>

                                <tr>

                                    <th>
                                        Invoice Number
                                    </th>

                                    <th>
                                        Order Number
                                    </th>

                                    <th>
                                        Customer
                                    </th>

                                    <th>
                                        Total
                                    </th>

                                    <th>
                                        Status
                                    </th>

                                    <th>
                                        Invoice Date
                                    </th>

                                    <th>
                                        Issued At
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


{{-- =============================================================
    STYLES
============================================================= --}}
@push('styles')
    <style>
        /*
        |--------------------------------------------------------------------------
        | Filter Card
        |--------------------------------------------------------------------------
        */

        .invoice-filter-card {
            padding: 16px;
            border: 1px solid #e9ecef;
            border-radius: 8px;
            background: #fafbfc;
        }


        /*
        |--------------------------------------------------------------------------
        | Filter Actions
        |--------------------------------------------------------------------------
        */

        .invoice-filter-actions {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }


        .invoice-filter-actions .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;

            min-height: 38px;

            white-space: nowrap;
        }


        .invoice-filter-actions svg {
            width: 16px;
            height: 16px;
        }


        /*
        |--------------------------------------------------------------------------
        | Invoice Status
        |--------------------------------------------------------------------------
        */

        .invoice-status {
            font-size: 11px;
            font-weight: 600;

            padding: 5px 8px;

            border-radius: 5px;
        }


        /*
        |--------------------------------------------------------------------------
        | Action Icon
        |--------------------------------------------------------------------------
        */

        .invoice-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 30px;
            height: 30px;

            color: #6571ff;
            text-decoration: none;

            border-radius: 4px;

            transition: all .15s ease;
        }


        .invoice-action svg {
            width: 15px;
            height: 15px;
        }


        .invoice-action:hover {
            color: #3f4bd8;
            background: #f1f3ff;
        }


        /*
        |--------------------------------------------------------------------------
        | Mobile
        |--------------------------------------------------------------------------
        */

        @media (max-width: 575.98px) {

            .invoice-filter-card {
                padding: 12px;
            }


            .invoice-filter-actions {
                display: grid;

                grid-template-columns: 1fr;

                width: 100%;
            }


            .invoice-filter-actions .btn {
                width: 100%;
            }

        }


        /*
        |--------------------------------------------------------------------------
        | Tablet
        |--------------------------------------------------------------------------
        */

        @media (min-width: 576px) and (max-width: 991.98px) {

            .invoice-filter-actions {
                width: 100%;
            }

        }
    </style>
@endpush


{{-- =============================================================
    SCRIPTS
============================================================= --}}
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | FEATHER ICON
            |--------------------------------------------------------------------------
            */

            function refreshFeatherIcons() {

                if (
                    window.feather &&
                    typeof window.feather.replace === 'function'
                ) {
                    window.feather.replace();
                }

            }

            refreshFeatherIcons();


            /*
            |--------------------------------------------------------------------------
            | GET TODAY
            |--------------------------------------------------------------------------
            */

            function getToday() {

                const now = new Date();

                const year = now.getFullYear();

                const month = String(
                    now.getMonth() + 1
                ).padStart(2, '0');

                const day = String(
                    now.getDate()
                ).padStart(2, '0');

                return `${year}-${month}-${day}`;

            }


            /*
            |--------------------------------------------------------------------------
            | SET DEFAULT DATE
            |--------------------------------------------------------------------------
            */

            function setDefaultDate() {

                const today = getToday();

                $('#filter-date-from').val(today);

                $('#filter-date-to').val(today);

            }


            /*
            |--------------------------------------------------------------------------
            | DEFAULT DATE
            |--------------------------------------------------------------------------
            */

            setDefaultDate();


            /*
            |--------------------------------------------------------------------------
            | GET FILTER
            |--------------------------------------------------------------------------
            | Satu sumber filter digunakan oleh:
            |
            | - DataTables
            | - Export Excel
            |--------------------------------------------------------------------------
            */

            function getInvoiceFilters() {

                return {

                    invoice_number: $('#filter-invoice').val() || '',

                    order_number: $('#filter-order').val() || '',

                    customer: $('#filter-customer').val() || '',

                    status: $('#filter-status').val() || '',

                    date_from: $('#filter-date-from').val() || getToday(),

                    date_to: $('#filter-date-to').val() || getToday()

                };

            }


            /*
            |--------------------------------------------------------------------------
            | DATATABLE
            |--------------------------------------------------------------------------
            */

            const table = $('#invoice-table').DataTable({

                processing: true,

                serverSide: true,

                responsive: true,

                ajax: {

                    url: "{{ route('super.invoices.dt') }}",

                    data: function(d) {

                        Object.assign(
                            d,
                            getInvoiceFilters()
                        );

                    }

                },


                columns: [

                    {
                        data: 'invoice_number',
                        name: 'invoice_number'
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
                        data: 'total_amount',
                        name: 'total_amount'
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'invoice_date',
                        name: 'invoice_date'
                    },

                    {
                        data: 'issued_at',
                        name: 'issued_at'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false,
                        className: 'text-center'
                    }

                ],


                /*
                |--------------------------------------------------------------------------
                | DEFAULT SORT
                |--------------------------------------------------------------------------
                */

                order: [
                    [6, 'desc']
                ],


                /*
                |--------------------------------------------------------------------------
                | PAGE LENGTH
                |--------------------------------------------------------------------------
                */

                pageLength: 25,


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

                    zeroRecords: 'Invoice tidak ditemukan',

                    emptyTable: 'Belum ada invoice',

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
            | RESET FILTER
            |--------------------------------------------------------------------------
            */

            $('#btn-reset').on('click', function() {

                /*
                 * Clear text filter
                 */

                $('#filter-invoice').val('');

                $('#filter-order').val('');

                $('#filter-customer').val('');

                $('#filter-status').val('');


                /*
                 * Reset date ke hari ini
                 */

                setDefaultDate();


                /*
                 * Reload halaman pertama
                 */

                table.ajax.reload(null, true);

            });


            /*
            |--------------------------------------------------------------------------
            | ENTER SEARCH
            |--------------------------------------------------------------------------
            */

            $(
                '#filter-invoice, ' +
                '#filter-order, ' +
                '#filter-customer'
            ).on('keypress', function(e) {

                if (e.which === 13) {

                    table.ajax.reload(null, true);

                }

            });


            /*
            |--------------------------------------------------------------------------
            | EXPORT EXCEL
            |--------------------------------------------------------------------------
            | Menggunakan filter yang SAMA dengan DataTables.
            |--------------------------------------------------------------------------
            */

            $('#btnExportExcel').on('click', function() {

                const filters = getInvoiceFilters();

                const params = new URLSearchParams();


                Object.entries(filters).forEach(function([key, value]) {

                    if (value !== '') {

                        params.append(
                            key,
                            value
                        );

                    }

                });


                const baseUrl =
                    "{{ route('super.invoices.export') }}";


                const exportUrl =
                    params.toString() ?
                    baseUrl + '?' + params.toString() :
                    baseUrl;


                /*
                 * Jalankan export
                 */

                window.location.href = exportUrl;

            });


            /*
            |--------------------------------------------------------------------------
            | DETAIL INVOICE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-invoice-detail',
                function(e) {

                    e.preventDefault();

                    const url = $(this).attr('href');

                    if (url) {

                        window.location.href = url;

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | FEATHER AFTER DATATABLE DRAW
            |--------------------------------------------------------------------------
            */

            $('#invoice-table').on(
                'draw.dt',
                function() {

                    refreshFeatherIcons();

                }
            );

        });
    </script>
@endpush
