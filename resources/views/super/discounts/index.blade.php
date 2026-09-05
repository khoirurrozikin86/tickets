@extends('layouts.admin')

@section('title', 'discount')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Master</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                discount
            </li>
        </ol>
    </nav>
@endsection

@section('content')

    <div class="row">
        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">
                <div class="card-body">

                    <div class="d-flex align-items-center justify-content-between mb-3">

                        <h6 class="card-title mb-0">
                            Discount
                        </h6>

                        <div class="d-flex gap-2">

                            <a href="javascript:void(0)" id="btnNewItem" class="btn btn-primary btn-sm">
                                + New
                            </a>

                        </div>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table id="discount-table" class="table w-100">

                            <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Name</th>
                                    <th>Type</th>
                                    <th>Value</th>
                                    <th>Min Purchase</th>
                                    <th>Period</th>
                                    <th>Usage</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>

                        </table>

                    </div>

                </div>
            </div>

        </div>
    </div>

@endsection


{{-- ========================= --}}
{{-- MODAL --}}
{{-- ========================= --}}

<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true" style="display: none;">

    <div class="modal-dialog modal-md">

        <div class="modal-content">

            <div class="modal-header">

                <h5 class="modal-title" id="itemModalLabel">
                    Add Discount
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <form id="itemForm">

                <div class="modal-body">

                    {{-- Code --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Code
                        </label>

                        <input type="text" name="code" id="code" class="form-control"
                            placeholder="Example: DUSEM10" style="text-transform: uppercase;">

                        <div class="invalid-feedback" data-error="code">
                        </div>

                    </div>


                    {{-- Name --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Discount Name
                        </label>

                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Example: Promo Dusun Semilir 10%">

                        <div class="invalid-feedback" data-error="name">
                        </div>

                    </div>


                    {{-- Type --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Type
                        </label>

                        <select name="type" id="type" class="form-select">

                            <option value="">
                                Select Type
                            </option>

                            <option value="PERCENTAGE">
                                Percentage
                            </option>

                            <option value="FIXED">
                                Fixed Amount
                            </option>

                        </select>

                        <div class="invalid-feedback" data-error="type">
                        </div>

                    </div>


                    {{-- Value --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Discount Value
                        </label>

                        <div class="input-group">

                            <span class="input-group-text" id="value-prefix">
                                Rp
                            </span>

                            <input type="number" name="value" id="value" class="form-control" min="0"
                                step="1" placeholder="0">

                        </div>

                        <div class="invalid-feedback" data-error="value">
                        </div>

                        <small class="text-muted" id="value-help">
                            Select discount type first.
                        </small>

                    </div>


                    {{-- Max Discount --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Max Discount
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input type="number" name="max_discount" id="max_discount" class="form-control"
                                min="0" step="1" placeholder="Optional">

                        </div>

                        <div class="invalid-feedback" data-error="max_discount">
                        </div>

                        <small class="text-muted">
                            Optional. Used for percentage discount.
                        </small>

                    </div>


                    {{-- Minimum Purchase --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Minimum Purchase
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input type="number" name="min_purchase" id="min_purchase" class="form-control"
                                min="0" step="1" placeholder="0">

                        </div>

                        <div class="invalid-feedback" data-error="min_purchase">
                        </div>

                    </div>


                    {{-- Start At --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Start At
                        </label>

                        <input type="datetime-local" name="start_at" id="start_at" class="form-control">

                        <div class="invalid-feedback" data-error="start_at">
                        </div>

                    </div>


                    {{-- End At --}}
                    <div class="mb-3">

                        <label class="form-label">
                            End At
                        </label>

                        <input type="datetime-local" name="end_at" id="end_at" class="form-control">

                        <div class="invalid-feedback" data-error="end_at">
                        </div>

                    </div>


                    {{-- Usage Limit --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Usage Limit
                        </label>

                        <input type="number" name="usage_limit" id="usage_limit" class="form-control"
                            min="1" step="1" placeholder="Unlimited">

                        <div class="invalid-feedback" data-error="usage_limit">
                        </div>

                        <small class="text-muted">
                            Leave empty for unlimited usage.
                        </small>

                    </div>


                    {{-- Status --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Status
                        </label>

                        <select name="is_active" id="is_active" class="form-select">

                            <option value="1">
                                Active
                            </option>

                            <option value="0">
                                Not Active
                            </option>

                        </select>

                        <div class="invalid-feedback" data-error="is_active">
                        </div>

                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cancel
                    </button>

                    <button type="submit" id="btnSaveItem" class="btn btn-primary">
                        Save
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>


@push('scripts')
    <script>
        (function($) {

            'use strict';


            const DT_SEL = '#discount-table';

            const modalEl =
                document.getElementById('itemModal');

            const bsModal =
                new bootstrap.Modal(modalEl);

            const $form =
                $('#itemForm');

            const $btnSave =
                $('#btnSaveItem');


            /*
            |--------------------------------------------------------------------------
            | CSRF
            |--------------------------------------------------------------------------
            */

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector(
                            'meta[name="csrf-token"]'
                        )
                        ?.getAttribute('content')
                }

            });


            /*
            |--------------------------------------------------------------------------
            | Feather
            |--------------------------------------------------------------------------
            */

            function safeFeatherReplace() {

                if (
                    window.feather &&
                    typeof window.feather.replace === 'function'
                ) {
                    window.feather.replace();
                }

            }


            /*
            |--------------------------------------------------------------------------
            | Clear Errors
            |--------------------------------------------------------------------------
            */

            function clearErrors() {

                $form.find('.is-invalid')
                    .removeClass('is-invalid');

                $form.find('.invalid-feedback')
                    .text('');

            }


            /*
            |--------------------------------------------------------------------------
            | Toast
            |--------------------------------------------------------------------------
            */

            function toastOk(message) {

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        icon: 'success',
                        title: 'Success',
                        text: message,
                        timer: 1800,
                        showConfirmButton: false
                    });

                } else {

                    alert(message);

                }

            }


            function toastErr(message) {

                if (typeof Swal !== 'undefined') {

                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: message
                    });

                } else {

                    alert(message);

                }

            }


            /*
            |--------------------------------------------------------------------------
            | Discount Type
            |--------------------------------------------------------------------------
            */

            function updateValueInput() {

                const type =
                    $('#type').val();

                const $prefix =
                    $('#value-prefix');

                const $help =
                    $('#value-help');

                if (type === 'PERCENTAGE') {

                    $prefix.text('%');

                    $('#value')
                        .attr('max', '100')
                        .attr('placeholder', 'Example: 10');

                    $help.text(
                        'Enter percentage value. Example: 10 = 10%.'
                    );

                } else if (type === 'FIXED') {

                    $prefix.text('Rp');

                    $('#value')
                        .removeAttr('max')
                        .attr('placeholder', 'Example: 50000');

                    $help.text(
                        'Enter fixed discount amount.'
                    );

                } else {

                    $prefix.text('Rp');

                    $('#value')
                        .removeAttr('max')
                        .attr('placeholder', '0');

                    $help.text(
                        'Select discount type first.'
                    );

                }

            }


            $('#type').on(
                'change',
                updateValueInput
            );


            /*
            |--------------------------------------------------------------------------
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = $(DT_SEL).DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('super.discounts.dt') }}',

                columns: [

                    {
                        data: 'code',
                        name: 'code'
                    },

                    {
                        data: 'name',
                        name: 'name'
                    },

                    {
                        data: 'type',
                        name: 'type'
                    },

                    {
                        data: 'value',
                        name: 'value'
                    },

                    {
                        data: 'min_purchase',
                        name: 'min_purchase'
                    },

                    {
                        data: 'period',
                        name: 'period',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'usage',
                        name: 'usage',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'is_active',
                        name: 'is_active'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [0, 'asc']
                ]

            });


            table.on(
                'draw.dt',
                safeFeatherReplace
            );


            /*
            |--------------------------------------------------------------------------
            | New
            |--------------------------------------------------------------------------
            */

            $('#btnNewItem').on('click', function() {

                $form[0].reset();

                clearErrors();

                $form
                    .data('mode', 'create')
                    .data(
                        'action',
                        '{{ route('super.discounts.store') }}'
                    );

                $('#itemModalLabel').text(
                    'Add Discount'
                );

                $('#is_active').val('1');

                updateValueInput();

                bsModal.show();

            });


            /*
            |--------------------------------------------------------------------------
            | Edit
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-edit-role',
                function() {

                    clearErrors();

                    const payload = JSON.parse(
                        $(this).attr(
                            'data-payload'
                        ) || '{}'
                    );

                    $form
                        .data('mode', 'edit')
                        .data(
                            'action',
                            $(this).data(
                                'update-url'
                            )
                        );

                    $('#itemModalLabel').text(
                        'Edit Discount'
                    );

                    $('#code').val(
                        payload.code
                    );

                    $('#name').val(
                        payload.name
                    );

                    $('#type').val(
                        payload.type
                    );

                    $('#value').val(
                        payload.value
                    );

                    $('#max_discount').val(
                        payload.max_discount
                    );

                    $('#min_purchase').val(
                        payload.min_purchase
                    );

                    $('#start_at').val(
                        payload.start_at
                    );

                    $('#end_at').val(
                        payload.end_at
                    );

                    $('#usage_limit').val(
                        payload.usage_limit
                    );

                    $('#is_active').val(
                        payload.is_active ?
                        '1' :
                        '0'
                    );

                    updateValueInput();

                    bsModal.show();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

            $form.on('submit', function(e) {

                e.preventDefault();

                clearErrors();

                const mode =
                    $form.data('mode');

                const action =
                    $form.data('action');

                const fd =
                    new FormData($form[0]);


                if (mode === 'edit') {

                    fd.append(
                        '_method',
                        'PUT'
                    );

                }


                $btnSave
                    .prop('disabled', true)
                    .text('Saving...');


                $.ajax({

                    url: action,

                    method: 'POST',

                    data: fd,

                    processData: false,

                    contentType: false,

                    success: function(response) {

                        bsModal.hide();

                        table.ajax.reload(
                            null,
                            false
                        );

                        toastOk(
                            response.message ||
                            'Data berhasil disimpan.'
                        );

                    },

                    error: function(xhr) {

                        if (xhr.status === 422) {

                            const errors =
                                xhr.responseJSON?.errors || {};

                            Object.keys(errors).forEach(
                                function(field) {

                                    const message =
                                        errors[field][0];

                                    $('#' + field)
                                        .addClass(
                                            'is-invalid'
                                        );

                                    $(
                                        '[data-error="' +
                                        field +
                                        '"]'
                                    ).text(
                                        message
                                    );

                                }
                            );

                            return;

                        }

                        toastErr(
                            xhr.responseJSON?.message ||
                            'Terjadi kesalahan.'
                        );

                    },

                    complete: function() {

                        $btnSave
                            .prop('disabled', false)
                            .text('Save');

                    }

                });

            });


            /*
            |--------------------------------------------------------------------------
            | Delete
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-delete-role',
                function() {

                    const url =
                        $(this).data('url');

                    const confirmMessage =
                        $(this).data('confirm') ||
                        'Delete this data?';


                    function doDelete() {

                        $.ajax({

                            url: url,

                            method: 'POST',

                            data: {
                                _method: 'DELETE'
                            },

                            success: function(response) {

                                table.ajax.reload(
                                    null,
                                    false
                                );

                                toastOk(
                                    response.message ||
                                    'Data berhasil dihapus.'
                                );

                            },

                            error: function(xhr) {

                                toastErr(
                                    xhr.responseJSON?.message ||
                                    'Gagal menghapus data.'
                                );

                            }

                        });

                    }


                    if (
                        typeof Swal !== 'undefined'
                    ) {

                        Swal.fire({

                            icon: 'warning',

                            title: 'Are you sure?',

                            text: confirmMessage,

                            showCancelButton: true,

                            confirmButtonText: 'Yes, delete it',

                            cancelButtonText: 'Cancel'

                        }).then(function(result) {

                            if (result.isConfirmed) {

                                doDelete();

                            }

                        });

                    } else {

                        if (confirm(confirmMessage)) {

                            doDelete();

                        }

                    }

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Modal Hidden
            |--------------------------------------------------------------------------
            */

            modalEl.addEventListener(
                'hidden.bs.modal',
                function() {

                    $form[0].reset();

                    clearErrors();

                    updateValueInput();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | Initial
            |--------------------------------------------------------------------------
            */

            updateValueInput();


        })(jQuery);
    </script>
@endpush
