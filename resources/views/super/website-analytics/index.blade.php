@extends('layouts.admin')

@section('title', 'Trafik Website')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('super.dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active" aria-current="page">Trafik Website</li>
        </ol>
    </nav>
@endsection

@section('content')
    <div class="d-flex flex-wrap align-items-center justify-content-between gap-3 mb-4">
        <div>
            <h4 class="mb-1">Laporan Trafik Website</h4>
            <p class="text-muted mb-0">Kunjungan anonim, terpisah dari atribusi UTM pembelian.</p>
        </div>
        <a class="btn btn-success"
            href="{{ route('super.website-analytics.export', ['date_from' => $dateFrom->toDateString(), 'date_to' => $dateTo->toDateString()]) }}">
            <i data-feather="download"></i> Export Excel
        </a>
    </div>

    <form method="GET" class="row g-3 align-items-end mb-4">
        <div class="col-sm-4 col-md-3">
            <label class="form-label" for="date_from">Dari tanggal</label>
            <input class="form-control" type="date" id="date_from" name="date_from"
                value="{{ $dateFrom->toDateString() }}">
        </div>
        <div class="col-sm-4 col-md-3">
            <label class="form-label" for="date_to">Sampai tanggal</label>
            <input class="form-control" type="date" id="date_to" name="date_to" value="{{ $dateTo->toDateString() }}">
        </div>
        <div class="col-sm-4 col-md-2">
            <button class="btn btn-primary w-100" type="submit"><i data-feather="filter"></i> Terapkan</button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Pengunjung unik (perkiraan)</div>
                    <div class="fs-3 fw-bold">{{ number_format($visitors) }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card h-100">
                <div class="card-body">
                    <div class="text-muted small">Pageview</div>
                    <div class="fs-3 fw-bold">{{ number_format($pageviews) }}</div>
                </div>
            </div>
        </div>
    </div>

    <div class="row g-3 mb-4">
        <div class="col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Halaman Terpopuler</h6>
                    @forelse ($topPages as $page)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span
                                class="text-truncate me-3">{{ $page->path }}</span><strong>{{ number_format($page->total) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada kunjungan pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Referrer</h6>
                    @forelse ($referrers as $referrer)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $referrer->source }}</span><strong>{{ number_format($referrer->total) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada referrer pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Perangkat</h6>
                    @forelse ($devices as $device)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ ucfirst($device->device) }}</span><strong>{{ number_format($device->total) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada data perangkat pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
        <div class="col-lg-3">
            <div class="card h-100">
                <div class="card-body">
                    <h6 class="mb-3">Browser</h6>
                    @forelse ($browsers as $browser)
                        <div class="d-flex justify-content-between border-bottom py-2 small">
                            <span>{{ $browser->browser }}</span><strong>{{ number_format($browser->total) }}</strong>
                        </div>
                    @empty
                        <p class="text-muted small mb-0">Belum ada data browser pada periode ini.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header d-flex flex-wrap justify-content-between align-items-center gap-2">
            <h6 class="mb-0">Rincian Pengunjung Anonim</h6>
            <small class="text-muted">Menampilkan maksimal 100 visitor terbaru pada periode terpilih. ID bukan nama atau
                identitas pribadi.</small>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Visitor ID</th>
                        <th>Asal Kunjungan</th>
                        <th>Perangkat / Browser</th>
                        <th class="text-end">Pageview</th>
                        <th>Terakhir Dilihat</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($visitorDetails as $visitor)
                        <tr>
                            <td><code>{{ substr($visitor->visitor_hash, 0, 12) }}</code></td>
                            <td>{{ $visitor->referrer_host ?: 'Direct' }}</td>
                            <td>{{ ucfirst($visitor->device) }} / {{ $visitor->browser }}</td>
                            <td class="text-end">{{ number_format($visitor->pageviews) }}</td>
                            <td>{{ \Illuminate\Support\Carbon::parse($visitor->last_seen)->format('d/m/Y H:i') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">Belum ada kunjungan pada periode ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
