@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Scan Ticket</h4>
            </div>

            <div class="card-body">

                <form id="scan-form">
                    @csrf

                    <div class="mb-3">
                        <label for="token" class="form-label">
                            Ticket Token
                        </label>

                        <input type="text" id="token" name="token" class="form-control"
                            placeholder="Masukkan token tiket" autocomplete="off" autofocus>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Scan
                    </button>
                </form>

                <div id="scan-result" class="mt-4" style="display: none;"></div>

            </div>
        </div>

    </div>

    <script>
        document.getElementById('scan-form').addEventListener('submit', async function(event) {
            event.preventDefault();

            const token = document.getElementById('token').value.trim();
            const resultBox = document.getElementById('scan-result');

            if (!token) {
                return;
            }

            resultBox.style.display = 'block';
            resultBox.innerHTML = 'Memproses scan...';

            try {
                const response = await fetch('{{ route('super.scan.scan') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({
                        token: token,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    resultBox.innerHTML = `
                <div class="alert alert-success">
                    <strong>✓ TIKET VALID</strong>
                    <hr>

                    <div>
                        <strong>Ticket:</strong>
                        ${data.ticket?.ticket_number ?? '-'}
                    </div>

                    <div>
                        <strong>Produk:</strong>
                        ${data.ticket?.product_name ?? '-'}
                    </div>

                    <div>
                        <strong>Tanggal:</strong>
                        ${data.ticket?.visit_date ?? '-'}
                    </div>

                    <div>
                        <strong>Status:</strong>
                        ${data.ticket?.status ?? '-'}
                    </div>
                </div>
            `;
                } else {
                    resultBox.innerHTML = `
                <div class="alert alert-danger">
                    <strong>✕ SCAN GAGAL</strong>
                    <hr>
                    ${data.message}
                </div>
            `;
                }

                document.getElementById('token').value = '';
                document.getElementById('token').focus();

            } catch (error) {
                console.error(error);

                resultBox.innerHTML = `
            <div class="alert alert-danger">
                Terjadi kesalahan saat memproses scan.
            </div>
        `;
            }
        });
    </script>
@endsection
