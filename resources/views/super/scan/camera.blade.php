@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        {{-- Header --}}
        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">Scan Ticket</h4>
                <p class="text-muted mb-0">
                    Scan QR Code tiket untuk melakukan validasi.
                </p>
            </div>

            <div>
                <a href="{{ route('super.scan.monitoring') }}" class="btn btn-outline-primary">

                    <i data-feather="activity" class="me-1"></i>

                    Monitoring

                </a>
            </div>

        </div>


        <div class="row justify-content-center">

            {{-- Scanner --}}
            <div class="col-xl-7 col-lg-8">

                <div class="card">

                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            QR Code Scanner
                        </h6>
                    </div>

                    <div class="card-body">

                        {{-- Camera --}}
                        <div id="reader"
                            style="
                            width: 100%;
                            max-width: 500px;
                            margin: 0 auto;
                        ">
                        </div>


                        {{-- Scanner Status --}}
                        <div id="scanner-status" class="text-center text-muted mt-3">

                            Arahkan kamera ke QR Code tiket.

                        </div>


                        {{-- Manual Token --}}
                        <div class="mt-4">

                            <div class="text-center mb-3">
                                <span class="text-muted">
                                    atau masukkan token secara manual
                                </span>
                            </div>

                            <form id="manual-scan-form">

                                @csrf

                                <div class="input-group">

                                    <input type="text" id="token" name="token" class="form-control"
                                        placeholder="Masukkan token tiket..." autocomplete="off">

                                    <button type="submit" class="btn btn-primary">

                                        <i data-feather="search" class="me-1"></i>

                                        Validasi

                                    </button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>


            {{-- Result --}}
            <div class="col-xl-5 col-lg-8 mt-4 mt-xl-0">

                <div id="scan-result-card" class="card d-none">

                    <div class="card-header">

                        <h6 class="card-title mb-0">
                            Scan Result
                        </h6>

                    </div>

                    <div class="card-body">

                        {{-- Result Status --}}
                        <div id="result-status" class="text-center mb-4">

                        </div>


                        {{-- Ticket Information --}}
                        <div id="ticket-information">

                        </div>

                    </div>

                </div>


                {{-- Initial Info --}}
                <div id="scan-info-card" class="card">

                    <div class="card-body text-center py-5">

                        <i data-feather="camera" style="width:48px;height:48px;" class="text-muted">
                        </i>

                        <h6 class="mt-3">
                            Ready to Scan
                        </h6>

                        <p class="text-muted mb-0">
                            Hasil validasi tiket akan muncul di sini.
                        </p>

                    </div>

                </div>

            </div>

        </div>

    </div>


    {{-- QR Scanner Library --}}
    <script src="https://unpkg.com/html5-qrcode" type="text/javascript"></script>


    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const readerElement = document.getElementById('reader');
            const statusElement = document.getElementById('scanner-status');

            const resultCard = document.getElementById('scan-result-card');
            const infoCard = document.getElementById('scan-info-card');

            const resultStatus = document.getElementById('result-status');
            const ticketInformation = document.getElementById('ticket-information');

            const manualForm = document.getElementById('manual-scan-form');
            const tokenInput = document.getElementById('token');


            let isProcessing = false;


            /*
            |--------------------------------------------------------------------------
            | Process Scan
            |--------------------------------------------------------------------------
            */

            async function processScan(token) {

                if (!token || isProcessing) {
                    return;
                }

                isProcessing = true;

                statusElement.innerHTML = `
            <span class="text-primary">
                Memvalidasi tiket...
            </span>
        `;


                try {

                    const response = await fetch(
                        "{{ route('super.scan.scan') }}", {
                            method: 'POST',

                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                        'input[name="_token"]'
                                    )?.value ||
                                    "{{ csrf_token() }}"
                            },

                            body: JSON.stringify({
                                token: token
                            })
                        }
                    );


                    const data = await response.json();


                    showResult(data);


                } catch (error) {

                    console.error(error);

                    showError(
                        'Terjadi kesalahan saat memproses scan.'
                    );

                } finally {

                    isProcessing = false;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Show Result
            |--------------------------------------------------------------------------
            */

            function showResult(data) {

                resultCard.classList.remove('d-none');
                infoCard.classList.add('d-none');


                if (data.success) {

                    resultStatus.innerHTML = `
                <div class="text-success">

                    <i data-feather="check-circle"
                        style="width:64px;height:64px;">
                    </i>

                    <h4 class="mt-3 mb-1">
                        TIKET VALID
                    </h4>

                    <p class="mb-0">
                        ${escapeHtml(data.message)}
                    </p>

                </div>
            `;

                } else {

                    resultStatus.innerHTML = `
                <div class="text-danger">

                    <i data-feather="x-circle"
                        style="width:64px;height:64px;">
                    </i>

                    <h4 class="mt-3 mb-1">
                        TIKET TIDAK VALID
                    </h4>

                    <p class="mb-0">
                        ${escapeHtml(data.message)}
                    </p>

                </div>
            `;

                }


                /*
                |--------------------------------------------------------------------------
                | Ticket Data
                |--------------------------------------------------------------------------
                */

                if (data.ticket) {

                    const ticket = data.ticket;

                    ticketInformation.innerHTML = `

                <div class="border rounded p-3">

                    <div class="mb-3">

                        <small class="text-muted">
                            Ticket Number
                        </small>

                        <div class="fw-bold">
                            ${escapeHtml(ticket.ticket_number)}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Product
                        </small>

                        <div class="fw-bold">
                            ${escapeHtml(ticket.product_name)}
                        </div>

                    </div>


                    <div class="mb-3">

                        <small class="text-muted">
                            Visit Date
                        </small>

                        <div>
                            ${escapeHtml(ticket.visit_date ?? '-')}
                        </div>

                    </div>


                    <div>

                        <small class="text-muted">
                            Status
                        </small>

                        <div>

                            <span class="badge ${
                                ticket.status === 'USED'
                                    ? 'bg-secondary'
                                    : ticket.status === 'ACTIVE'
                                        ? 'bg-success'
                                        : 'bg-danger'
                            }">

                                ${escapeHtml(ticket.status)}

                            </span>

                        </div>

                    </div>

                </div>

            `;

                } else {

                    ticketInformation.innerHTML = '';

                }


                /*
                |--------------------------------------------------------------------------
                | Refresh Feather Icons
                |--------------------------------------------------------------------------
                */

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }


                /*
                |--------------------------------------------------------------------------
                | Clear Manual Input
                |--------------------------------------------------------------------------
                */

                tokenInput.value = '';


                /*
                |--------------------------------------------------------------------------
                | Scanner Status
                |--------------------------------------------------------------------------
                */

                if (data.success) {

                    statusElement.innerHTML = `
                <span class="text-success">
                    Scan berhasil.
                    Silakan scan tiket berikutnya.
                </span>
            `;

                } else {

                    statusElement.innerHTML = `
                <span class="text-danger">
                    ${escapeHtml(data.message)}
                </span>
            `;

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Error
            |--------------------------------------------------------------------------
            */

            function showError(message) {

                resultCard.classList.remove('d-none');
                infoCard.classList.add('d-none');

                resultStatus.innerHTML = `
            <div class="text-danger">

                <i data-feather="alert-triangle"
                    style="width:64px;height:64px;">
                </i>

                <h5 class="mt-3">
                    Error
                </h5>

                <p>
                    ${escapeHtml(message)}
                </p>

            </div>
        `;

                ticketInformation.innerHTML = '';

                if (typeof feather !== 'undefined') {
                    feather.replace();
                }

            }


            /*
            |--------------------------------------------------------------------------
            | Manual Scan
            |--------------------------------------------------------------------------
            */

            manualForm.addEventListener('submit', function(event) {

                event.preventDefault();

                const token = tokenInput.value.trim();

                if (!token) {

                    tokenInput.focus();

                    return;
                }

                processScan(token);

            });


            /*
            |--------------------------------------------------------------------------
            | QR Code Scanner
            |--------------------------------------------------------------------------
            */

            const scanner = new Html5Qrcode("reader");


            function onScanSuccess(decodedText) {

                if (isProcessing) {
                    return;
                }

                processScan(decodedText);

            }


            function onScanFailure(errorMessage) {

                // Jangan tampilkan error terus-menerus.
                // html5-qrcode memang akan memanggil callback
                // ini ketika QR belum ditemukan.

            }


            scanner.start({
                    facingMode: "environment"
                },

                {
                    fps: 10,
                    qrbox: {
                        width: 250,
                        height: 250
                    }
                },

                onScanSuccess,
                onScanFailure

            ).then(function() {

                statusElement.innerHTML = `
            <span class="text-success">
                Kamera siap. Arahkan ke QR Code tiket.
            </span>
        `;

            }).catch(function(error) {

                console.error(error);

                statusElement.innerHTML = `
            <span class="text-danger">
                Kamera tidak dapat diakses.
                Gunakan input token manual.
            </span>
        `;

            });


            /*
            |--------------------------------------------------------------------------
            | Escape HTML
            |--------------------------------------------------------------------------
            */

            function escapeHtml(value) {

                if (value === null || value === undefined) {
                    return '';
                }

                return String(value)
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');

            }

        });
    </script>
@endsection
