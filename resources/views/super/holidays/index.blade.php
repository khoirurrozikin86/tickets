@extends('layouts.admin')

@section('title', 'holiday')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Master</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                holiday
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
                            Holiday
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

                        <table id="holiday-table" class="table w-100">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Holiday Name</th>
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
                    Add Holiday
                </h5>

                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>

            </div>

            <form id="itemForm">

                <div class="modal-body">

                    {{-- Date --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Date
                        </label>

                        <input type="date" name="date" id="date" class="form-control">

                        <div class="invalid-feedback" data-error="date">
                        </div>

                    </div>


                    {{-- Holiday Name --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Holiday Name
                        </label>

                        <input type="text" name="name" id="name" class="form-control"
                            placeholder="Example: Independence Day">

                        <div class="invalid-feedback" data-error="name">
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


            const DT_SEL = '#holiday-table';

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
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = $(DT_SEL).DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('super.holidays.dt') }}',

                columns: [

                    {
                        data: 'date',
                        name: 'date'
                    },

                    {
                        data: 'name',
                        name: 'name'
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
                        '{{ route('super.holidays.store') }}'
                    );

                $('#itemModalLabel').text(
                    'Add Holiday'
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
                        'Edit Holiday'
                    );

                    $('#date').val(
                        payload.date
                    );

                    $('#name').val(
                        payload.name
                    );

                    $('#is_active').val(
                        payload.is_active ?
                        '1' :
                        '0'
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

                }
            );


        })(jQuery);
    </script>
@endpush
