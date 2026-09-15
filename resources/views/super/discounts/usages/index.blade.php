@extends('layouts.admin')

@section('content')
    <div class="container-fluid">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <div>
                <h4 class="mb-1">Discount Usage History</h4>

                <div class="text-muted">
                    {{ $discount->code }}
                    — {{ $discount->name }}
                </div>
            </div>

            <a href="{{ route('super.discounts.index') }}" class="btn btn-secondary">
                ← Back
            </a>

        </div>


        {{-- SUMMARY --}}
        <div class="row mb-4">

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Code</small>

                        <h5 class="mb-0">
                            {{ $discount->code }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Usage</small>

                        <h5 class="mb-0">
                            {{ $discount->usage_count }}
                            /
                            {{ $discount->usage_limit ?? '∞' }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Type</small>

                        <h5 class="mb-0">
                            {{ $discount->type }}
                        </h5>
                    </div>
                </div>
            </div>


            <div class="col-md-3">
                <div class="card">
                    <div class="card-body">
                        <small class="text-muted">Total Discount</small>

                        <h5 class="mb-0">
                            Rp
                            {{ number_format((float) $usages->sum('discount_amount'), 0, ',', '.') }}
                        </h5>
                    </div>
                </div>
            </div>

        </div>


        {{-- HISTORY --}}
        <div class="card">

            <div class="card-header">
                <strong>Usage History</strong>
            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-hover mb-0">

                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order</th>
                                <th>Customer</th>
                                <th>Email</th>
                                <th>Discount</th>
                                <th>Used At</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($usages as $usage)
                                <tr>

                                    <td>
                                        {{ $usages->firstItem() + $loop->index }}
                                    </td>

                                    <td>
                                        <strong>
                                            {{ $usage->order?->order_number ?? '-' }}
                                        </strong>
                                    </td>

                                    <td>
                                        {{ $usage->order?->customer_name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $usage->order?->customer_email ?? '-' }}
                                    </td>

                                    <td>
                                        Rp
                                        {{ number_format((float) $usage->discount_amount, 0, ',', '.') }}
                                    </td>

                                    <td>
                                        {{ $usage->used_at?->format('d/m/Y H:i:s') ?? '-' }}
                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="6" class="text-center py-4">
                                        Belum ada penggunaan discount.
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

            @if ($usages->hasPages())
                <div class="card-footer">
                    {{ $usages->links() }}
                </div>
            @endif

        </div>

    </div>
@endsection
