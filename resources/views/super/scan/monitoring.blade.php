@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- =========================================================
        HEADER
        ========================================================== --}}
        <div
            class="d-flex flex-column flex-lg-row
                    justify-content-between align-items-lg-center
                    gap-3 mb-4">

            {{-- Title --}}
            <div>
                <h4 class="mb-1">
                    Scan Ticket Monitoring
                </h4>

                <p class="text-muted mb-0">
                    Monitoring validasi dan penggunaan tiket.
                </p>
            </div>

            {{-- Action Buttons --}}
            <div class="d-flex flex-column flex-sm-row gap-2">

                <a href="{{ route('super.scan.barcode') }}" class="btn btn-outline-primary">

                    <i data-feather="maximize" class="me-1"></i>

                    Scan Barcode

                </a>

                <a href="{{ route('super.scan.camera') }}" class="btn btn-primary">

                    <i data-feather="camera" class="me-1"></i>

                    Camera

                </a>

            </div>

        </div>


        {{-- =========================================================
        FILTER CARD
        ========================================================== --}}
        <div class="card mb-4">

            <div class="card-header">

                <div class="d-flex align-items-center gap-2">

                    <i data-feather="filter"></i>

                    <h6 class="card-title mb-0">
                        Filter Monitoring
                    </h6>

                </div>

            </div>

            <div class="card-body">

                <div class="row g-3">

                    {{-- Date From --}}
                    <div class="col-12 col-sm-6 col-md-3">

                        <label for="date_from" class="form-label">
                            Dari Tanggal
                        </label>

                        <input type="date" id="date_from" class="form-control" value="{{ now()->format('Y-m-d') }}">

                    </div>


                    {{-- Date To --}}
                    <div class="col-12 col-sm-6 col-md-3">

                        <label for="date_to" class="form-label">
                            Sampai Tanggal
                        </label>

                        <input type="date" id="date_to" class="form-control" value="{{ now()->format('Y-m-d') }}">

                    </div>


                    {{-- Result --}}
                    <div class="col-12 col-sm-6 col-md-2">

                        <label for="result" class="form-label">
                            Result
                        </label>

                        <select id="result" class="form-select">

                            <option value="">
                                Semua
                            </option>

                            <option value="SUCCESS">
                                Success
                            </option>

                            <option value="FAILED">
                                Failed
                            </option>

                        </select>

                    </div>


                    {{-- Reason --}}
                    <div class="col-12 col-sm-6 col-md-2">

                        <label for="reason" class="form-label">
                            Reason
                        </label>

                        <select id="reason" class="form-select">

                            <option value="">
                                Semua
                            </option>

                            <option value="VALID">
                                Valid
                            </option>

                            <option value="NOT_FOUND">
                                Not Found
                            </option>

                            <option value="ALREADY_USED">
                                Already Used
                            </option>

                            <option value="EXPIRED">
                                Expired
                            </option>

                            <option value="CANCELLED">
                                Cancelled
                            </option>

                            <option value="INVALID_STATUS">
                                Invalid Status
                            </option>

                        </select>

                    </div>


                    {{-- Filter Buttons --}}
                    <div class="col-12 col-md-2 d-flex align-items-end">

                        <div class="d-flex gap-2 w-100">

                            <button type="button" id="btnFilter" class="btn btn-primary flex-grow-1">

                                <i data-feather="filter" class="me-1"></i>

                                <span>Filter</span>

                            </button>


                            <button type="button" id="btnReset" class="btn btn-outline-secondary" title="Reset Filter">

                                <i data-feather="refresh-cw"></i>

                            </button>

                        </div>

                    </div>

                </div>

            </div>

        </div>


        {{-- =========================================================
        DATA TABLE CARD
        ========================================================== --}}
        <div class="card">

            {{-- Card Header --}}
            <div class="card-header">

                <div
                    class="d-flex flex-column flex-sm-row
                            justify-content-between
                            align-items-sm-center
                            gap-3">

                    {{-- Title --}}
                    <div>

                        <h6 class="card-title mb-1">
                            Riwayat Scan
                        </h6>

                        <small class="text-muted">
                            Aktivitas validasi tiket
                        </small>

                    </div>


                    {{-- Actions --}}
                    <div
                        class="d-flex flex-column flex-sm-row
                                align-items-stretch
                                align-items-sm-center
                                gap-2">

                        {{-- Export --}}
                        <button type="button" id="btnExportExcel" class="btn btn-success">

                            <i data-feather="download" class="me-1"></i>

                            <span>Export Excel</span>

                        </button>


                        {{-- Monitoring Badge --}}
                        <span class="badge bg-primary px-3 py-2 text-center">

                            Monitoring

                        </span>

                    </div>

                </div>

            </div>


            {{-- Card Body --}}
            <div class="card-body">

                <div class="table-responsive">

                    <table id="scanMonitoringTable" class="table table-hover align-middle w-100">

                        <thead>

                            <tr>

                                <th width="50">
                                    #
                                </th>

                                <th>
                                    Ticket
                                </th>

                                <th>
                                    Product
                                </th>

                                <th>
                                    Visit Date
                                </th>

                                <th>
                                    Result
                                </th>

                                <th>
                                    Reason
                                </th>

                                <th>
                                    Status
                                </th>

                                <th>
                                    Scanned By
                                </th>

                                <th>
                                    Scan Time
                                </th>

                            </tr>

                        </thead>

                        <tbody></tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection


{{-- =============================================================
STYLES
============================================================= --}}
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.3/css/dataTables.bootstrap5.min.css">

    <style>
        /*
            |--------------------------------------------------------------------------
            | Scan Monitoring
            |--------------------------------------------------------------------------
            */

        #scanMonitoringTable th,
        #scanMonitoringTable td {
            vertical-align: middle;
            white-space: nowrap;
        }


        /*
            |--------------------------------------------------------------------------
            | Mobile
            |--------------------------------------------------------------------------
            */

        @media (max-width: 575.98px) {

            .card-header {
                padding: 1rem;
            }

            .card-body {
                padding: 1rem;
            }

            #btnExportExcel,
            #btnFilter {
                min-height: 38px;
            }

        }
    </style>
@endpush


{{-- =============================================================
SCRIPTS
============================================================= --}}
@push('scripts')
    {{-- DataTables --}}
    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.min.js"></script>

    <script src="https://cdn.datatables.net/2.3.3/js/dataTables.bootstrap5.min.js"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
            |--------------------------------------------------------------------------
            | Elements
            |--------------------------------------------------------------------------
            */

            const dateFrom = document.getElementById('date_from');

            const dateTo = document.getElementById('date_to');

            const result = document.getElementById('result');

            const reason = document.getElementById('reason');

            const btnFilter = document.getElementById('btnFilter');

            const btnReset = document.getElementById('btnReset');

            const btnExportExcel =
                document.getElementById('btnExportExcel');


            /*
            |--------------------------------------------------------------------------
            | Get Current Filters
            |--------------------------------------------------------------------------
            */

            function getFilters() {

                return {

                    date_from: dateFrom.value,

                    date_to: dateTo.value,

                    result: result.value,

                    reason: reason.value,

                };

            }


            /*
            |--------------------------------------------------------------------------
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = new DataTable(
                '#scanMonitoringTable', {

                    processing: true,

                    serverSide: true,

                    responsive: false,

                    autoWidth: false,

                    ajax: {

                        url: "{{ route('super.scan.monitoring.dt') }}",

                        data: function(d) {

                            const filters = getFilters();

                            d.date_from = filters.date_from;

                            d.date_to = filters.date_to;

                            d.result = filters.result;

                            d.reason = filters.reason;

                        },

                        dataSrc: function(json) {

                            /*
                            |--------------------------------------------------------------------------
                            | Dashboard Stats
                            |--------------------------------------------------------------------------
                            */

                            if (json.stats) {

                                const total =
                                    document.getElementById('totalScan');

                                const success =
                                    document.getElementById('successScan');

                                const failed =
                                    document.getElementById('failedScan');

                                const used =
                                    document.getElementById('usedScan');


                                if (total) {

                                    total.textContent =
                                        Number(
                                            json.stats.total ?? 0
                                        ).toLocaleString('id-ID');

                                }


                                if (success) {

                                    success.textContent =
                                        Number(
                                            json.stats.success ?? 0
                                        ).toLocaleString('id-ID');

                                }


                                if (failed) {

                                    failed.textContent =
                                        Number(
                                            json.stats.failed ?? 0
                                        ).toLocaleString('id-ID');

                                }


                                if (used) {

                                    used.textContent =
                                        Number(
                                            json.stats.used ?? 0
                                        ).toLocaleString('id-ID');

                                }

                            }


                            return json.data || [];

                        }

                    },


                    /*
                    |--------------------------------------------------------------------------
                    | Columns
                    |--------------------------------------------------------------------------
                    */

                    columns: [

                        {
                            data: null,

                            name: 'id',

                            searchable: false,

                            orderable: false,

                            width: '50px',

                            render: function(
                                data,
                                type,
                                row,
                                meta
                            ) {

                                return (
                                    meta.row +
                                    meta.settings._iDisplayStart +
                                    1
                                );

                            }

                        },


                        {
                            data: 'ticket_number',

                            name: 'ticket.ticket_number',

                            defaultContent: '-'
                        },


                        {
                            data: 'product_name',

                            name: 'ticket.product_name',

                            defaultContent: '-'
                        },


                        {
                            data: 'visit_date',

                            name: 'ticket.visit_date',

                            defaultContent: '-'
                        },


                        {
                            data: 'result',

                            name: 'result',

                            defaultContent: '-'
                        },


                        {
                            data: 'reason',

                            name: 'reason',

                            defaultContent: '-'
                        },


                        {
                            data: 'ticket_status',

                            name: 'ticket.status',

                            orderable: false,

                            searchable: false,

                            defaultContent: '-'
                        },


                        {
                            data: 'scanner',

                            name: 'scannedBy.name',

                            defaultContent: '-'
                        },


                        {
                            data: 'scanned_at',

                            name: 'scanned_at',

                            defaultContent: '-'
                        }

                    ],


                    /*
                    |--------------------------------------------------------------------------
                    | Default Order
                    |--------------------------------------------------------------------------
                    */

                    order: [

                        [8, 'desc']

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

                        processing: 'Memuat data...',

                        search: 'Cari:',

                        lengthMenu: 'Tampilkan _MENU_ data',

                        info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',

                        infoEmpty: 'Tidak ada data',

                        zeroRecords: 'Data tidak ditemukan',

                        emptyTable: 'Belum ada aktivitas scan',

                        paginate: {

                            first: '«',

                            last: '»',

                            next: '›',

                            previous: '‹'

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Filter
            |--------------------------------------------------------------------------
            */

            btnFilter.addEventListener(
                'click',
                function() {

                    table.ajax.reload();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Enter = Filter
            |--------------------------------------------------------------------------
            */

            [
                dateFrom,
                dateTo,
                result,
                reason
            ].forEach(function(element) {

                element.addEventListener(
                    'keydown',
                    function(event) {

                        if (event.key === 'Enter') {

                            event.preventDefault();

                            table.ajax.reload();

                        }

                    }
                );

            });


            /*
            |--------------------------------------------------------------------------
            | Reset
            |--------------------------------------------------------------------------
            */

            btnReset.addEventListener(
                'click',
                function() {

                    const today =
                        new Date()
                        .toISOString()
                        .slice(0, 10);


                    dateFrom.value = today;

                    dateTo.value = today;

                    result.value = '';

                    reason.value = '';


                    table.search('');

                    table.ajax.reload();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Export Excel
            |--------------------------------------------------------------------------
            */

            btnExportExcel.addEventListener(
                'click',
                function() {

                    const filters = getFilters();

                    const params =
                        new URLSearchParams();


                    /*
                    |--------------------------------------------------------------------------
                    | Date From
                    |--------------------------------------------------------------------------
                    */

                    if (filters.date_from) {

                        params.append(
                            'date_from',
                            filters.date_from
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Date To
                    |--------------------------------------------------------------------------
                    */

                    if (filters.date_to) {

                        params.append(
                            'date_to',
                            filters.date_to
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Result
                    |--------------------------------------------------------------------------
                    */

                    if (filters.result) {

                        params.append(
                            'result',
                            filters.result
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Reason
                    |--------------------------------------------------------------------------
                    */

                    if (filters.reason) {

                        params.append(
                            'reason',
                            filters.reason
                        );

                    }


                    /*
                    |--------------------------------------------------------------------------
                    | Download
                    |--------------------------------------------------------------------------
                    */

                    const exportUrl =
                        "{{ route('super.scan.monitoring.export') }}" +
                        '?' +
                        params.toString();


                    window.location.href = exportUrl;

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Feather Icons
            |--------------------------------------------------------------------------
            */

            if (
                typeof feather !== 'undefined'
            ) {

                feather.replace();

            }

        });
    </script>
@endpush
