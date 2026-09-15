@extends('layouts.admin')

@section('title', 'Notification Logs')

@section('content')

    <div class="container-fluid">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header d-flex align-items-center justify-content-between">
                <div>
                    <h4 class="card-title mb-1">
                        Notification Logs
                    </h4>

                    <p class="text-muted mb-0">
                        Monitoring pengiriman notifikasi sistem
                    </p>
                </div>

                <i data-feather="bell" class="text-muted"></i>
            </div>

            <div class="card-body">

                {{-- FILTER --}}
                <form id="notificationFilter">

                    <div class="row g-3">

                        {{-- ORDER --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Order Number
                            </label>

                            <input type="text" name="order_number" class="form-control" placeholder="ORD-...">
                        </div>

                        {{-- RECIPIENT --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Recipient
                            </label>

                            <input type="text" name="recipient" class="form-control" placeholder="Email / WhatsApp">
                        </div>

                        {{-- CHANNEL --}}
                        <div class="col-md-2">
                            <label class="form-label">
                                Channel
                            </label>

                            <select name="channel" class="form-select">
                                <option value="">All</option>
                                <option value="EMAIL">EMAIL</option>
                                <option value="WHATSAPP">WHATSAPP</option>
                            </select>
                        </div>

                        {{-- TYPE --}}
                        <div class="col-md-2">
                            <label class="form-label">
                                Type
                            </label>

                            <select name="type" class="form-select">
                                <option value="">All</option>
                                <option value="E_TICKET">E-TICKET</option>
                            </select>
                        </div>

                        {{-- STATUS --}}
                        <div class="col-md-2">
                            <label class="form-label">
                                Status
                            </label>

                            <select name="status" class="form-select">
                                <option value="">All</option>
                                <option value="QUEUED">QUEUED</option>
                                <option value="SENT">SENT</option>
                                <option value="FAILED">FAILED</option>
                            </select>
                        </div>

                        {{-- DATE FROM --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Date From
                            </label>

                            <input type="date" name="date_from" class="form-control" value="{{ $defaultDate }}">
                        </div>

                        {{-- DATE TO --}}
                        <div class="col-md-3">
                            <label class="form-label">
                                Date To
                            </label>

                            <input type="date" name="date_to" class="form-control" value="{{ $defaultDate }}">
                        </div>

                        {{-- BUTTON --}}
                        <div class="col-md-6 d-flex align-items-end gap-2">

                            <button type="button" id="btnFilter" class="btn btn-primary">
                                <i data-feather="filter" class="icon-sm me-1"></i>
                                Filter
                            </button>

                            <button type="button" id="btnReset" class="btn btn-outline-secondary">
                                <i data-feather="refresh-cw" class="icon-sm me-1"></i>
                                Reset
                            </button>

                        </div>

                    </div>

                </form>

                <hr class="my-4">

                {{-- TABLE --}}
                <div class="table-responsive">

                    <table id="notificationsTable" class="table table-bordered table-striped align-middle w-100">

                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Channel</th>
                                <th>Type</th>
                                <th>Recipient</th>
                                <th>Status</th>
                                <th>Queued At</th>
                                <th>Sent At</th>
                                <th width="80">Action</th>
                            </tr>
                        </thead>

                        <tbody></tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        $(function() {

            const defaultDate = "{{ $defaultDate }}";

            const table = $('#notificationsTable').DataTable({

                processing: true,
                serverSide: true,

                ajax: {
                    url: "{{ route('super.notifications.dt') }}",

                    data: function(d) {

                        const formData = $('#notificationFilter')
                            .serializeArray();

                        formData.forEach(function(item) {
                            d[item.name] = item.value;
                        });

                    }
                },

                columns: [

                    {
                        data: 'order_number',
                        name: 'order_number',
                        defaultContent: '-'
                    },

                    {
                        data: 'channel',
                        name: 'channel',
                        orderable: true
                    },

                    {
                        data: 'type',
                        name: 'type',
                        orderable: true
                    },

                    {
                        data: 'recipient',
                        name: 'recipient',
                        orderable: true
                    },

                    {
                        data: 'status',
                        name: 'status',
                        orderable: true
                    },

                    {
                        data: 'queued_at',
                        name: 'queued_at',
                        orderable: true
                    },

                    {
                        data: 'sent_at',
                        name: 'sent_at',
                        orderable: true
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
                    [5, 'desc']
                ],

                pageLength: 25,

                lengthMenu: [
                    [10, 25, 50, 100],
                    [10, 25, 50, 100]
                ],

                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.8/i18n/id.json'
                },

                drawCallback: function() {

                    if (typeof feather !== 'undefined') {
                        feather.replace();
                    }

                }

            });


            /*
            |--------------------------------------------------------------------------
            | FILTER
            |--------------------------------------------------------------------------
            */

            $('#btnFilter').on('click', function() {

                table.ajax.reload();

            });


            /*
            |--------------------------------------------------------------------------
            | RESET
            |--------------------------------------------------------------------------
            */

            $('#btnReset').on('click', function() {

                $('#notificationFilter')[0].reset();

                $('input[name="date_from"]').val(defaultDate);
                $('input[name="date_to"]').val(defaultDate);

                table.ajax.reload();

            });


            /*
            |--------------------------------------------------------------------------
            | ENTER TO FILTER
            |--------------------------------------------------------------------------
            */

            $('#notificationFilter input').on('keypress', function(e) {

                if (e.which === 13) {

                    e.preventDefault();

                    table.ajax.reload();

                }

            });

        });
    </script>
@endpush
