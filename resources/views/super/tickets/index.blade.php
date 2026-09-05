@extends('layouts.admin')

@section('title', 'ticket')

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
        <div class="col-md-12 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">

                    {{-- HEADER --}}
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div>
                            <h6 class="card-title mb-1">Ticket</h6>
                            <p class="text-muted mb-0">
                                Monitoring dan kontrol e-ticket customer.
                            </p>
                        </div>

                        <div>
                            <a href="{{ route('super.tickets.export') }}" id="btn-export-ticket" class="btn btn-success">

                                <i data-feather="download"></i>

                                Export Excel
                            </a>
                        </div>
                    </div>

                    {{-- FILTER --}}
                    <div class="row mb-4">

                        <div class="col-md-3 mb-3">
                            <label class="form-label">
                                Ticket Number
                            </label>

                            <input type="text" id="filter-ticket" class="form-control" placeholder="Cari ticket...">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Produk
                            </label>

                            <select id="filter-product" class="form-select">
                                <option value="">Semua Produk</option>

                                @foreach ($products as $product)
                                    <option value="{{ $product->id }}">
                                        {{ $product->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Status
                            </label>

                            <select id="filter-status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="ACTIVE">ACTIVE</option>
                                <option value="USED">USED</option>
                                <option value="CANCELLED">CANCELLED</option>
                                <option value="EXPIRED">EXPIRED</option>
                            </select>
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Dari
                            </label>

                            <input type="date" id="filter-date-from" class="form-control">
                        </div>

                        <div class="col-md-2 mb-3">
                            <label class="form-label">
                                Sampai
                            </label>

                            <input type="date" id="filter-date-to" class="form-control">
                        </div>

                        <div class="col-md-1 mb-3 d-flex align-items-end">
                            <button type="button" id="btn-filter" class="btn btn-primary w-100" title="Filter">
                                <i data-feather="filter"></i>
                            </button>
                        </div>




                    </div>

                    {{-- TABLE --}}
                    <div class="table-responsive">

                        <table id="ticket-table" class="table table-bordered table-hover" style="width:100%">
                            <thead>
                                <tr>
                                    <th>Ticket Number</th>
                                    <th>Produk</th>
                                    <th>Customer</th>
                                    <th>Visit Date</th>
                                    <th>Status</th>
                                    <th>Scan</th>
                                    <th>Issued At</th>
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
                                 * Ticket status
                                 */
        .ticket-status {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
        }

        /*
                                 * Action icon
                                 */
        .ticket-action {
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
            const table = $('#ticket-table').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('super.tickets.dt') }}",

                    data: function(d) {

                        d.ticket_number =
                            $('#filter-ticket').val();

                        d.product_id =
                            $('#filter-product').val();

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
                        data: 'ticket_number',
                        name: 'ticket_number'
                    },

                    {
                        data: 'product_name',
                        name: 'product_name'
                    },

                    {
                        data: 'customer_name',
                        name: 'order.customer_name'
                    },

                    {
                        data: 'visit_date',
                        name: 'visit_date'
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'scan',
                        name: 'scan',
                        orderable: false,
                        searchable: false
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
                    },

                ],

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
                    zeroRecords: 'Ticket tidak ditemukan',
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
             * Enter pada ticket number
             */
            $('#filter-ticket').on('keypress', function(e) {

                if (e.which === 13) {

                    table.ajax.reload();

                }

            });


            /*
             * Reset otomatis ketika select berubah
             */
            $('#filter-product, #filter-status, #filter-date-from, #filter-date-to')
                .on('change', function() {

                    table.ajax.reload();

                });


            /*
             * DETAIL TICKET
             */
            $(document).on('click', '.btn-ticket-detail', function(e) {

                e.preventDefault();

                const url = $(this).attr('href');

                window.location.href = url;

            });


            /*
             * CANCEL TICKET
             */
            $(document).on('click', '.btn-ticket-cancel', function(e) {

                e.preventDefault();

                const url = $(this).data('url');

                Swal.fire({

                    title: 'Batalkan Ticket?',
                    text: 'Ticket yang dibatalkan tidak dapat digunakan kembali.',
                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Batalkan',
                    cancelButtonText: 'Tidak',

                    confirmButtonColor: '#dc3545'

                }).then((result) => {

                    if (!result.isConfirmed) {
                        return;
                    }

                    $.ajax({

                        url: url,

                        type: 'POST',

                        data: {
                            _token: "{{ csrf_token() }}",
                            _method: 'PUT'
                        },

                        success: function(response) {

                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message ??
                                    'Ticket berhasil dibatalkan.',
                                timer: 1500,
                                showConfirmButton: false
                            });

                            table.ajax.reload(null, false);

                        },

                        error: function(xhr) {

                            let message =
                                'Ticket gagal dibatalkan.';

                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {
                                message =
                                    xhr.responseJSON.message;
                            }

                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal',
                                text: message
                            });

                        }

                    });

                });

            });


            /*
             * Feather setelah DataTable redraw
             */
            $('#ticket-table').on(
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

        });



        $('#btn-export-ticket').on('click', function(e) {

            e.preventDefault();

            const url = new URL($(this).attr('href'), window.location.origin);

            url.searchParams.set(
                'ticket_number',
                $('#filter-ticket').val()
            );

            url.searchParams.set(
                'product_id',
                $('#filter-product').val()
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
