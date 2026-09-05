@extends('layouts.admin')

@section('title', 'product price')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Master</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                product price
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
                            Product Price
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

                        <table id="product-price-table" class="table w-100">

                            <thead>
                                <tr>
                                    <th>Product</th>
                                    <th>Day Type</th>
                                    <th>Price</th>
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
                    Add Product Price
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <form id="itemForm">

                <div class="modal-body">

                    {{-- Product --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Product
                        </label>

                        <select name="product_id" id="product_id" class="form-select">

                            <option value="">
                                Select Product
                            </option>

                            @foreach ($products as $product)
                                <option value="{{ $product->id }}">
                                    {{ $product->name }}
                                </option>
                            @endforeach

                        </select>

                        <div class="invalid-feedback" data-error="product_id">
                        </div>

                    </div>


                    {{-- Day Type --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Day Type
                        </label>

                        <select name="day_type" id="day_type" class="form-select">

                            <option value="">
                                Select Day Type
                            </option>

                            <option value="WEEKDAY">
                                Weekday
                            </option>

                            <option value="WEEKEND">
                                Weekend
                            </option>

                            <option value="HOLIDAY">
                                Holiday
                            </option>

                        </select>

                        <div class="invalid-feedback" data-error="day_type">
                        </div>

                    </div>


                    {{-- Price --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Price
                        </label>

                        <div class="input-group">

                            <span class="input-group-text">
                                Rp
                            </span>

                            <input type="number" name="price" id="price" class="form-control" min="0"
                                step="1" placeholder="0">

                        </div>

                        <div class="invalid-feedback" data-error="price">
                        </div>

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


            const DT_SEL = '#product-price-table';

            const modalEl = document.getElementById('itemModal');

            const bsModal = new bootstrap.Modal(modalEl);

            const $form = $('#itemForm');

            const $btnSave = $('#btnSaveItem');


            /*
            |--------------------------------------------------------------------------
            | CSRF
            |--------------------------------------------------------------------------
            */

            $.ajaxSetup({

                headers: {
                    'X-CSRF-TOKEN': document
                        .querySelector('meta[name="csrf-token"]')
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
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = $(DT_SEL).DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('super.product-prices.dt') }}',

                columns: [

                    {
                        data: 'product',
                        name: 'product.name',
                        orderable: false,
                        searchable: true
                    },

                    {
                        data: 'day_type',
                        name: 'day_type'
                    },

                    {
                        data: 'price',
                        name: 'price'
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
                        '{{ route('super.product-prices.store') }}'
                    );

                $('#itemModalLabel').text(
                    'Add Product Price'
                );

                $('#is_active').val('1');

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
                        $(this).attr('data-payload') || '{}'
                    );

                    $form
                        .data('mode', 'edit')
                        .data(
                            'action',
                            $(this).data('update-url')
                        );

                    $('#itemModalLabel').text(
                        'Edit Product Price'
                    );

                    $('#product_id').val(
                        payload.product_id
                    );

                    $('#day_type').val(
                        payload.day_type
                    );

                    $('#price').val(
                        payload.price
                    );

                    $('#is_active').val(
                        payload.is_active ? '1' : '0'
                    );

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

                const mode = $form.data('mode');

                const action = $form.data('action');

                const fd = new FormData($form[0]);


                if (mode === 'edit') {

                    fd.append('_method', 'PUT');

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

                        table.ajax.reload(null, false);

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
                                        .addClass('is-invalid');

                                    $('[data-error="' + field + '"]')
                                        .text(message);

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

                }
            );


        })(jQuery);
    </script>
@endpush
