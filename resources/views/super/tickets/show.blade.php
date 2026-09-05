@extends('layouts.admin')

@section('title', 'ticket detail')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('super.tickets.index') }}">
                    Ticket
                </a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Detail
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">

        {{-- LEFT --}}
        <div class="col-md-8 grid-margin stretch-card">
            <div class="card">

                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>
                            <h6 class="card-title mb-1">
                                Ticket Detail
                            </h6>

                            <p class="text-muted mb-0">
                                Informasi detail e-ticket.
                            </p>
                        </div>

                        <a href="{{ route('super.tickets.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i data-feather="arrow-left" class="me-1"></i>
                            Kembali
                        </a>

                    </div>


                    {{-- STATUS --}}
                    <div class="ticket-status-box mb-4">

                        <div>
                            <small class="text-muted d-block">
                                Status Ticket
                            </small>

                            @php
                                $statusClass = match ($ticket->status) {
                                    'ACTIVE' => 'success',
                                    'USED' => 'primary',
                                    'CANCELLED' => 'danger',
                                    'EXPIRED' => 'secondary',
                                    default => 'secondary',
                                };
                            @endphp

                            <span class="badge bg-{{ $statusClass }} mt-1">
                                {{ $ticket->status }}
                            </span>
                        </div>

                        @if ($ticket->status === 'ACTIVE')
                            <button type="button" class="btn btn-outline-danger btn-sm" id="btn-cancel-ticket"
                                data-url="{{ route('super.tickets.cancel', $ticket->id) }}">
                                <i data-feather="x-circle" class="me-1"></i>
                                Batalkan Ticket
                            </button>
                        @endif

                    </div>


                    {{-- TICKET INFORMATION --}}
                    <h6 class="section-title">
                        Informasi Ticket
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Ticket Number
                            </label>

                            <div class="detail-value ticket-number">
                                {{ $ticket->ticket_number }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Produk
                            </label>

                            <div class="detail-value">
                                {{ $ticket->product_name }}
                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Tanggal Kunjungan
                            </label>

                            <div class="detail-value">

                                @if ($ticket->visit_date)
                                    {{ $ticket->visit_date->format('d/m/Y') }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Order Number
                            </label>

                            <div class="detail-value">

                                {{ $ticket->order?->order_number ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Issued At
                            </label>

                            <div class="detail-value">

                                {{ $ticket->issued_at?->format('d/m/Y H:i:s') ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Created At
                            </label>

                            <div class="detail-value">

                                {{ $ticket->created_at?->format('d/m/Y H:i:s') ?? '-' }}

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    {{-- CUSTOMER --}}
                    <h6 class="section-title">
                        Informasi Customer
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Nama
                            </label>

                            <div class="detail-value">

                                {{ $ticket->order?->customer_name ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Email
                            </label>

                            <div class="detail-value">

                                {{ $ticket->order?->customer_email ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                No. Handphone
                            </label>

                            <div class="detail-value">

                                {{ $ticket->order?->customer_phone ?? '-' }}

                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    {{-- SCAN INFORMATION --}}
                    <h6 class="section-title">
                        Informasi Scan
                    </h6>

                    <div class="row">

                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Status Scan
                            </label>

                            <div class="detail-value">

                                @if ($ticket->used_at)
                                    <span class="text-success">
                                        Sudah digunakan
                                    </span>
                                @else
                                    <span class="text-muted">
                                        Belum digunakan
                                    </span>
                                @endif

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Scan At
                            </label>

                            <div class="detail-value">

                                {{ $ticket->used_at?->format('d/m/Y H:i:s') ?? '-' }}

                            </div>

                        </div>


                        <div class="col-md-6 mb-3">

                            <label class="detail-label">
                                Scan By
                            </label>

                            <div class="detail-value">

                                {{ $ticket->usedBy?->name ?? '-' }}

                            </div>

                        </div>

                    </div>

                </div>

            </div>
        </div>


        {{-- RIGHT --}}
        <div class="col-md-4 grid-margin stretch-card">

            <div class="card">

                <div class="card-body text-center">

                    <h6 class="card-title">
                        QR Code Ticket
                    </h6>

                    <p class="text-muted small mb-4">
                        QR Code digunakan untuk validasi tiket
                        pada saat kunjungan.
                    </p>


                    {{-- QR --}}
                    <div class="qr-wrapper">

                        <div id="ticket-qrcode"></div>

                    </div>


                    <div class="mt-4">

                        <div class="small text-muted">
                            Ticket Number
                        </div>

                        <div class="fw-semibold mt-1">
                            {{ $ticket->ticket_number }}
                        </div>

                    </div>


                    @if ($ticket->status === 'ACTIVE')
                        <div class="alert alert-success mt-4 mb-0">

                            <i data-feather="check-circle" class="me-1"></i>

                            Ticket dapat digunakan.

                        </div>
                    @elseif ($ticket->status === 'USED')
                        <div class="alert alert-primary mt-4 mb-0">

                            <i data-feather="check-circle" class="me-1"></i>

                            Ticket sudah digunakan.

                        </div>
                    @elseif ($ticket->status === 'CANCELLED')
                        <div class="alert alert-danger mt-4 mb-0">

                            <i data-feather="x-circle" class="me-1"></i>

                            Ticket telah dibatalkan.

                        </div>
                    @elseif ($ticket->status === 'EXPIRED')
                        <div class="alert alert-secondary mt-4 mb-0">

                            <i data-feather="clock" class="me-1"></i>

                            Ticket telah expired.

                        </div>
                    @endif

                </div>

            </div>

        </div>

    </div>

@endsection


@push('styles')
    <style>
        .ticket-status-box {
            display: flex;
            align-items: center;
            justify-content: space-between;

            padding: 15px 18px;

            border: 1px solid #e9ecef;
            border-radius: 8px;

            background: #fff;
        }


        .section-title {
            font-size: 13px;
            font-weight: 600;

            margin-bottom: 20px;

            color: #343a40;
        }


        .detail-label {
            display: block;

            font-size: 11px;
            font-weight: 500;

            color: #8c8c8c;

            margin-bottom: 5px;
        }


        .detail-value {
            font-size: 13px;
            font-weight: 500;

            color: #343a40;
        }


        .ticket-number {
            font-family: monospace;

            font-size: 13px;

            color: #6571ff;
        }


        .qr-wrapper {
            display: flex;

            justify-content: center;
            align-items: center;

            min-height: 260px;

            padding: 20px;

            border: 1px solid #e9ecef;

            border-radius: 10px;

            background: #fff;
        }


        #ticket-qrcode canvas,
        #ticket-qrcode img {
            max-width: 220px;
            height: auto;
        }


        .card-title {
            font-size: 14px;
        }
    </style>
@endpush


@push('scripts')
    {{-- QR Code Library --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            /*
             * Feather
             */
            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }


            /*
             * Generate QR Code
             *
             * QR hanya berisi token random.
             */
            new QRCode(
                document.getElementById('ticket-qrcode'), {
                    text: @json('TKT:' . $ticket->token),

                    width: 220,
                    height: 220,

                    correctLevel: QRCode.CorrectLevel.H
                }
            );


            /*
             * CANCEL TICKET
             */
            $('#btn-cancel-ticket').on('click', function() {

                const button = $(this);

                const url = button.data('url');


                Swal.fire({

                    title: 'Batalkan Ticket?',

                    text: 'Ticket yang dibatalkan tidak dapat digunakan kembali.',

                    icon: 'warning',

                    showCancelButton: true,

                    confirmButtonText: 'Ya, Batalkan',

                    cancelButtonText: 'Tidak',

                    confirmButtonColor: '#dc3545',

                }).then(function(result) {

                    if (!result.isConfirmed) {
                        return;
                    }


                    button.prop('disabled', true);


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

                            }).then(function() {

                                window.location.reload();

                            });

                        },


                        error: function(xhr) {

                            button.prop('disabled', false);


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

        });
    </script>
@endpush
