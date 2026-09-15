@extends('layouts.admin')

@section('title', 'Notification Detail')

@section('content')

    <div class="container-fluid">

        {{-- HEADER --}}
        <div class="d-flex align-items-center justify-content-between mb-3">

            <div>
                <h4 class="mb-1">
                    Notification Detail
                </h4>

                <p class="text-muted mb-0">
                    Detail pengiriman notifikasi
                </p>
            </div>

            <a href="{{ route('super.notifications.index') }}" class="btn btn-outline-secondary">
                <i data-feather="arrow-left" class="icon-sm me-1"></i>
                Back
            </a>

        </div>


        <div class="row">

            {{-- NOTIFICATION INFORMATION --}}
            <div class="col-md-6 mb-4">

                <div class="card h-100">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Notification Information
                        </h5>
                    </div>

                    <div class="card-body">

                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Channel
                            </div>

                            <div class="col-sm-7">
                                @if ($notification->channel === 'EMAIL')
                                    <span class="badge bg-primary">
                                        EMAIL
                                    </span>
                                @elseif ($notification->channel === 'WHATSAPP')
                                    <span class="badge bg-success">
                                        WHATSAPP
                                    </span>
                                @else
                                    <span class="badge bg-secondary">
                                        {{ $notification->channel }}
                                    </span>
                                @endif
                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Type
                            </div>

                            <div class="col-sm-7">
                                <span class="badge bg-secondary">
                                    {{ $notification->type }}
                                </span>
                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Recipient
                            </div>

                            <div class="col-sm-7">
                                <strong>
                                    {{ $notification->recipient }}
                                </strong>
                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Status
                            </div>

                            <div class="col-sm-7">

                                @php
                                    $statusClass = match ($notification->status) {
                                        'SENT' => 'bg-success',
                                        'FAILED' => 'bg-danger',
                                        'QUEUED' => 'bg-warning text-dark',
                                        default => 'bg-secondary',
                                    };
                                @endphp

                                <span class="badge {{ $statusClass }}">
                                    {{ $notification->status }}
                                </span>

                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Queued At
                            </div>

                            <div class="col-sm-7">
                                {{ $notification->queued_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="row mb-3">

                            <div class="col-sm-5 text-muted">
                                Sent At
                            </div>

                            <div class="col-sm-7">
                                {{ $notification->sent_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </div>

                        </div>


                        <div class="row">

                            <div class="col-sm-5 text-muted">
                                Created At
                            </div>

                            <div class="col-sm-7">
                                {{ $notification->created_at?->format('d-m-Y H:i:s') ?? '-' }}
                            </div>

                        </div>

                    </div>

                </div>

            </div>


            {{-- ORDER INFORMATION --}}
            <div class="col-md-6 mb-4">

                <div class="card h-100">

                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            Order Information
                        </h5>
                    </div>

                    <div class="card-body">

                        @if ($notification->order)
                            <div class="row mb-3">

                                <div class="col-sm-5 text-muted">
                                    Order Number
                                </div>

                                <div class="col-sm-7">

                                    <a href="{{ route('super.orders.show', $notification->order->id) }}"
                                        class="fw-semibold">
                                        {{ $notification->order->order_number }}
                                    </a>

                                </div>

                            </div>


                            <div class="row mb-3">

                                <div class="col-sm-5 text-muted">
                                    Customer
                                </div>

                                <div class="col-sm-7">
                                    {{ $notification->order->customer_name ?? '-' }}
                                </div>

                            </div>


                            <div class="row mb-3">

                                <div class="col-sm-5 text-muted">
                                    Email
                                </div>

                                <div class="col-sm-7">
                                    {{ $notification->order->customer_email ?? '-' }}
                                </div>

                            </div>


                            <div class="row mb-3">

                                <div class="col-sm-5 text-muted">
                                    Phone
                                </div>

                                <div class="col-sm-7">
                                    {{ $notification->order->customer_phone ?? '-' }}
                                </div>

                            </div>


                            <div class="row mb-3">

                                <div class="col-sm-5 text-muted">
                                    Order Status
                                </div>

                                <div class="col-sm-7">

                                    @php
                                        $orderStatusClass = match ($notification->order->status) {
                                            'PAID' => 'bg-success',
                                            'PENDING' => 'bg-warning text-dark',
                                            'EXPIRED', 'CANCELLED' => 'bg-danger',
                                            default => 'bg-secondary',
                                        };
                                    @endphp

                                    <span class="badge {{ $orderStatusClass }}">
                                        {{ $notification->order->status }}
                                    </span>

                                </div>

                            </div>


                            <div class="row">

                                <div class="col-sm-5 text-muted">
                                    Total Amount
                                </div>

                                <div class="col-sm-7">
                                    <strong>
                                        Rp
                                        {{ number_format((float) $notification->order->total_amount, 0, ',', '.') }}
                                    </strong>
                                </div>

                            </div>
                        @else
                            <div class="text-muted">
                                Order tidak ditemukan.
                            </div>
                        @endif

                    </div>

                </div>

            </div>


            {{-- ERROR --}}
            @if ($notification->error_message)
                <div class="col-12 mb-4">

                    <div class="card border-danger">

                        <div class="card-header text-danger">
                            <h5 class="card-title mb-0">
                                <i data-feather="alert-circle" class="icon-sm me-1"></i>
                                Error Information
                            </h5>
                        </div>

                        <div class="card-body">

                            <div class="alert alert-danger mb-0">
                                {{ $notification->error_message }}
                            </div>

                        </div>

                    </div>

                </div>
            @endif


            {{-- METADATA --}}
            @if ($notification->metadata)
                <div class="col-12 mb-4">

                    <div class="card">

                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                Metadata
                            </h5>
                        </div>

                        <div class="card-body">

                            <pre class="bg-light p-3 rounded mb-0">{{ json_encode($notification->metadata, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) }}</pre>

                        </div>

                    </div>

                </div>
            @endif

        </div>

    </div>

@endsection


@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            if (typeof feather !== 'undefined') {
                feather.replace();
            }

        });
    </script>
@endpush
