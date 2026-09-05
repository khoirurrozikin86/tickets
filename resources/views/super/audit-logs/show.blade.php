@extends('layouts.admin')

@section('title', 'audit log detail')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('super.audit-logs.index') }}">
                    Audit Log
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

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">

                    {{-- Header --}}
                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <h6 class="card-title mb-0">
                            Audit Log Detail
                        </h6>

                        <a href="{{ route('super.audit-logs.index') }}" class="btn btn-outline-secondary btn-sm">
                            <i data-feather="arrow-left" class="me-1"></i>
                            Kembali
                        </a>

                    </div>


                    {{-- Information --}}
                    <div class="row">

                        {{-- Date --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                Date
                            </label>

                            <div class="fw-semibold">
                                {{ $audit->created_at?->format('d/m/Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        {{-- User --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                User
                            </label>

                            <div class="fw-semibold">
                                {{ $audit->user?->name ?? 'System' }}
                            </div>

                        </div>


                        {{-- Action --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                Action
                            </label>

                            <div>

                                @php
                                    $actionClass = match ($audit->action) {
                                        'CREATE' => 'success',
                                        'UPDATE' => 'warning',
                                        'DELETE' => 'danger',
                                        'LOGIN' => 'primary',
                                        'LOGOUT' => 'secondary',
                                        'SCAN' => 'info',
                                        'PAYMENT', 'PAYMENT_CALLBACK' => 'success',
                                        'CANCEL', 'REFUND' => 'danger',
                                        default => 'secondary',
                                    };
                                @endphp

                                <span class="badge bg-{{ $actionClass }}">
                                    {{ $audit->action }}
                                </span>

                            </div>

                        </div>


                        {{-- Module --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                Module
                            </label>

                            <div class="fw-semibold">
                                {{ $audit->module ?? '-' }}
                            </div>

                        </div>


                        {{-- Record --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                Record
                            </label>

                            <div class="fw-semibold">

                                @if ($audit->auditable_id)
                                    {{ $audit->auditable_type ? class_basename($audit->auditable_type) : 'Record' }}

                                    #{{ $audit->auditable_id }}
                                @else
                                    -
                                @endif

                            </div>

                        </div>


                        {{-- IP Address --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label text-muted mb-1">
                                IP Address
                            </label>

                            <div class="fw-semibold">
                                {{ $audit->ip_address ?? '-' }}
                            </div>

                        </div>


                        {{-- Description --}}
                        <div class="col-md-12 mb-4">

                            <label class="form-label text-muted mb-1">
                                Description
                            </label>

                            <div class="fw-semibold">
                                {{ $audit->description ?? '-' }}
                            </div>

                        </div>

                    </div>


                    <hr class="my-4">


                    {{-- Before / After --}}
                    <div class="row">

                        {{-- Before --}}
                        <div class="col-md-6 mb-4">

                            <div class="card border">

                                <div class="card-header bg-light">

                                    <h6 class="mb-0">
                                        Before
                                    </h6>

                                </div>

                                <div class="card-body p-0">

                                    @if (!empty($audit->old_values))
                                        <pre class="mb-0 p-3"
                                            style="
                                                white-space: pre-wrap;
                                                word-break: break-word;
                                                font-size: 13px;
                                                background: #fafafa;
                                                min-height: 200px;
                                            ">{{ is_array($audit->old_values)
                                                ? json_encode($audit->old_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                                : $audit->old_values }}</pre>
                                    @else
                                        <div class="p-3 text-muted">
                                            Tidak ada data sebelum perubahan.
                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>


                        {{-- After --}}
                        <div class="col-md-6 mb-4">

                            <div class="card border">

                                <div class="card-header bg-light">

                                    <h6 class="mb-0">
                                        After
                                    </h6>

                                </div>

                                <div class="card-body p-0">

                                    @if (!empty($audit->new_values))
                                        <pre class="mb-0 p-3"
                                            style="
                                                white-space: pre-wrap;
                                                word-break: break-word;
                                                font-size: 13px;
                                                background: #fafafa;
                                                min-height: 200px;
                                            ">{{ is_array($audit->new_values)
                                                ? json_encode($audit->new_values, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
                                                : $audit->new_values }}</pre>
                                    @else
                                        <div class="p-3 text-muted">
                                            Tidak ada data setelah perubahan.
                                        </div>
                                    @endif

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }

        });
    </script>
@endpush
