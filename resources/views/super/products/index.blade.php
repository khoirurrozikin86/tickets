@extends('layouts.admin')

@section('title', 'product')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Master</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                product
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
                            product
                        </h6>

                        <div class="d-flex gap-2">

                            <a href="javascript:void(0)" id="btnExport" class="btn btn-outline-secondary btn-sm">
                                Export Excel
                            </a>

                            @can('products.create')
                                <a href="javascript:void(0)" id="btnNewItem" class="btn btn-primary btn-sm">
                                    + New
                                </a>
                            @endcan

                        </div>

                    </div>


                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif


                    <div class="table-responsive">

                        <table id="product-table" class="table w-100">

                            <thead>

                                <tr>
                                    <th>Image</th>
                                    <th>Name</th>
                                    <th>Slug</th>
                                    <th>Status</th>
                                    <th>Order</th>
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


{{-- ========================================================= --}}
{{-- Modal Create / Edit --}}
{{-- ========================================================= --}}

<div class="modal fade" id="itemModal" tabindex="-1" aria-hidden="true" style="display: none;">

    <div class="modal-dialog modal-dialog-centered">

        <div class="modal-content">

            <form id="itemForm">

                <div class="modal-header">

                    <h5 class="modal-title" id="itemModalTitle">

                        New product

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                <div class="modal-body">

                    <div class="row g-3">


                        {{-- PRODUCT NAME --}}

                        <div class="col-md-12">

                            <label class="form-label">

                                Product Name

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" class="form-control" id="name" name="name" required>

                            <div class="invalid-feedback" id="nameErr">
                            </div>

                        </div>


                        {{-- SLUG --}}

                        <div class="col-md-12">

                            <label class="form-label">

                                Slug

                                <span class="text-danger">*</span>

                            </label>

                            <input type="text" class="form-control" id="slug" name="slug" required>

                            <div class="invalid-feedback" id="slugErr">
                            </div>

                        </div>


                        {{-- IMAGE --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Product Image
                            </label>

                            <input type="file" class="form-control" id="image" name="image"
                                accept="image/jpeg,image/png,image/webp">

                            <div class="invalid-feedback" id="imageErr">
                            </div>

                            <small class="text-muted">
                                JPG, PNG atau WEBP.
                            </small>

                        </div>


                        {{-- IMAGE PREVIEW --}}

                        <div class="col-md-12 d-none" id="imagePreviewWrapper">

                            <label class="form-label">
                                Preview
                            </label>

                            <div>

                                <img id="imagePreview" src="" class="img-fluid rounded"
                                    style="max-height: 180px;">

                            </div>

                        </div>


                        {{-- DESCRIPTION --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Description
                            </label>

                            <textarea class="form-control" id="description" name="description" rows="4" placeholder="Product description..."></textarea>

                            <div class="invalid-feedback" id="descriptionErr">
                            </div>

                        </div>


                        {{-- STATUS --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Status
                            </label>

                            <select class="form-select" id="is_active" name="is_active">

                                <option value="1">
                                    Active
                                </option>

                                <option value="0">
                                    Not Active
                                </option>

                            </select>

                            <div class="invalid-feedback" id="is_activeErr">
                            </div>

                        </div>


                        {{-- SORT ORDER --}}

                        <div class="col-md-12">

                            <label class="form-label">
                                Sort Order
                            </label>

                            <input type="number" class="form-control" id="sort_order" name="sort_order" value="0"
                                min="0">

                            <div class="invalid-feedback" id="sort_orderErr">
                            </div>

                        </div>


                    </div>

                </div>


                <div class="modal-footer">

                    <button type="button" class="btn btn-outline-secondary btn-sm" data-bs-dismiss="modal">

                        Cancel

                    </button>

                    <button type="submit" id="btnSaveItem" class="btn btn-primary btn-sm">

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


            /*
            |--------------------------------------------------------------------------
            | CONFIG
            |--------------------------------------------------------------------------
            */

            const DT_SEL = '#product-table';

            const modalEl = document.getElementById('itemModal');

            const bsModal = new bootstrap.Modal(modalEl);

            const $form = $('#itemForm');

            const $btnSave = $('#btnSaveItem');


            /*
            |--------------------------------------------------------------------------
            | INPUT
            |--------------------------------------------------------------------------
            */

            const $name = $('#name');
            const $slug = $('#slug');
            const $image = $('#image');
            const $description = $('#description');
            const $isActive = $('#is_active');
            const $sortOrder = $('#sort_order');


            /*
            |--------------------------------------------------------------------------
            | ERROR
            |--------------------------------------------------------------------------
            */

            const $nameErr = $('#nameErr');
            const $slugErr = $('#slugErr');
            const $imageErr = $('#imageErr');
            const $descriptionErr = $('#descriptionErr');
            const $isActiveErr = $('#is_activeErr');
            const $sortOrderErr = $('#sort_orderErr');


            /*
            |--------------------------------------------------------------------------
            | AJAX SETUP
            |--------------------------------------------------------------------------
            */

            $.ajaxSetup({

                headers: {

                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),

                    'X-Requested-With': 'XMLHttpRequest',

                    'Accept': 'application/json'

                }

            });


            /*
            |--------------------------------------------------------------------------
            | FEATHER
            |--------------------------------------------------------------------------
            */

            function safeFeatherReplace() {

                if (!window.feather || !feather.icons) {
                    return;
                }

                try {
                    feather.replace();
                } catch (e) {}

            }


            /*
            |--------------------------------------------------------------------------
            | CLEAR ERRORS
            |--------------------------------------------------------------------------
            */

            function clearErrors() {

                [
                    $name,
                    $slug,
                    $image,
                    $description,
                    $isActive,
                    $sortOrder

                ].forEach($el => {

                    $el.removeClass('is-invalid');

                });


                $nameErr.text('');
                $slugErr.text('');
                $imageErr.text('');
                $descriptionErr.text('');
                $isActiveErr.text('');
                $sortOrderErr.text('');

            }


            /*
            |--------------------------------------------------------------------------
            | TOAST SUCCESS
            |--------------------------------------------------------------------------
            */

            function toastOk(msg) {

                if (window.Swal) {

                    Swal.fire({

                        toast: true,

                        icon: 'success',

                        position: 'top-end',

                        timer: 1800,

                        showConfirmButton: false,

                        title: msg || 'Success'

                    });

                }

            }


            /*
            |--------------------------------------------------------------------------
            | TOAST ERROR
            |--------------------------------------------------------------------------
            */

            function toastErr(msg) {

                if (window.Swal) {

                    Swal.fire({

                        icon: 'error',

                        title: 'Error',

                        text: msg || 'Something went wrong'

                    });

                }

            }


            /*
            |--------------------------------------------------------------------------
            | RELOAD TABLE
            |--------------------------------------------------------------------------
            */

            function reloadTable() {

                $(DT_SEL)
                    .DataTable()
                    .ajax
                    .reload(null, false);

            }


            /*
            |--------------------------------------------------------------------------
            | DATATABLE
            |--------------------------------------------------------------------------
            */

            $(DT_SEL)
                .DataTable({

                    processing: true,

                    serverSide: true,

                    ajax: '{{ route('super.products.dt') }}',

                    columns: [

                        {
                            data: 'image',
                            name: 'image',
                            orderable: false,
                            searchable: false
                        },

                        {
                            data: 'name',
                            name: 'name'
                        },

                        {
                            data: 'slug',
                            name: 'slug'
                        },

                        {
                            data: 'is_active',
                            name: 'is_active'
                        },

                        {
                            data: 'sort_order',
                            name: 'sort_order'
                        },

                        {
                            data: 'actions',
                            name: 'actions',
                            orderable: false,
                            searchable: false
                        }

                    ],

                    order: [
                        [4, 'asc']
                    ]

                })

                .on(
                    'draw.dt',
                    safeFeatherReplace
                );


            /*
            |--------------------------------------------------------------------------
            | NEW PRODUCT
            |--------------------------------------------------------------------------
            */

            $('#btnNewItem').on('click', function() {

                clearErrors();

                $form[0].reset();

                $isActive.val('1');

                $sortOrder.val('0');

                $('#imagePreviewWrapper')
                    .addClass('d-none');

                $('#imagePreview')
                    .attr('src', '');

                $form

                    .data('mode', 'create')

                    .data(
                        'action',
                        '{{ route('super.products.store') }}'
                    );


                $('#itemModalTitle')
                    .text('New product');


                bsModal.show();

            });


            /*
            |--------------------------------------------------------------------------
            | EDIT PRODUCT
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-edit-role',
                function() {

                    clearErrors();

                    $form[0].reset();

                    let payload = {};


                    try {

                        payload = JSON.parse(
                            $(this).attr(
                                'data-payload'
                            ) || '{}'
                        );

                    } catch (e) {}


                    $form

                        .data('mode', 'edit')

                        .data(
                            'action',
                            $(this).data('update-url')
                        );


                    $('#itemModalTitle')
                        .text('Edit product');


                    $name.val(
                        payload.name || ''
                    );


                    $slug.val(
                        payload.slug || ''
                    );


                    $description.val(
                        payload.description || ''
                    );


                    $isActive.val(
                        payload.is_active ?
                        '1' :
                        '0'
                    );


                    $sortOrder.val(
                        payload.sort_order || 0
                    );


                    if (payload.image) {

                        $('#imagePreview')
                            .attr(
                                'src',
                                payload.image
                            );

                        $('#imagePreviewWrapper')
                            .removeClass('d-none');

                    } else {

                        $('#imagePreviewWrapper')
                            .addClass('d-none');

                    }


                    $image.val('');


                    bsModal.show();

                }
            );


            /*
            |--------------------------------------------------------------------------
            | IMAGE PREVIEW
            |--------------------------------------------------------------------------
            */

            $image.on('change', function() {

                const file = this.files[0];


                if (!file) {

                    $('#imagePreviewWrapper')
                        .addClass('d-none');

                    $('#imagePreview')
                        .attr('src', '');

                    return;

                }


                const reader =
                    new FileReader();


                reader.onload = function(e) {

                    $('#imagePreview')
                        .attr(
                            'src',
                            e.target.result
                        );

                    $('#imagePreviewWrapper')
                        .removeClass('d-none');

                };


                reader.readAsDataURL(file);

            });


            /*
            |--------------------------------------------------------------------------
            | SUBMIT
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

                        contentType: false

                    })

                    .done(function(res) {

                        toastOk(

                            res.message ||

                            (
                                mode === 'edit' ?
                                'Updated' :
                                'Created'
                            )

                        );


                        bsModal.hide();


                        reloadTable();

                    })


                    .fail(function(xhr) {


                        if (

                            xhr.status === 422 &&

                            xhr.responseJSON?.errors

                        ) {

                            const errors =
                                xhr.responseJSON.errors;


                            if (errors.name) {

                                $name
                                    .addClass('is-invalid');

                                $nameErr
                                    .text(errors.name[0]);

                            }


                            if (errors.slug) {

                                $slug
                                    .addClass('is-invalid');

                                $slugErr
                                    .text(errors.slug[0]);

                            }


                            if (errors.image) {

                                $image
                                    .addClass('is-invalid');

                                $imageErr
                                    .text(errors.image[0]);

                            }


                            if (errors.description) {

                                $description
                                    .addClass('is-invalid');

                                $descriptionErr
                                    .text(
                                        errors.description[0]
                                    );

                            }


                            if (errors.is_active) {

                                $isActive
                                    .addClass('is-invalid');

                                $isActiveErr
                                    .text(
                                        errors.is_active[0]
                                    );

                            }


                            if (errors.sort_order) {

                                $sortOrder
                                    .addClass('is-invalid');

                                $sortOrderErr
                                    .text(
                                        errors.sort_order[0]
                                    );

                            }


                        } else {

                            toastErr(
                                xhr.responseJSON?.message
                            );

                        }

                    })


                    .always(function() {

                        $btnSave

                            .prop('disabled', false)

                            .text('Save');

                    });

            });


            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            */

            $(document).on(
                'click',
                '.btn-delete-role',
                function() {

                    const url =
                        $(this).data('url');


                    Swal.fire({

                            icon: 'warning',

                            title: 'Delete?',

                            text: 'Delete product?',

                            showCancelButton: true,

                            confirmButtonText: 'Yes, delete'

                        })

                        .then(function(r) {

                            if (!r.isConfirmed) {
                                return;
                            }


                            $.post(

                                    url,

                                    {
                                        _method: 'DELETE'
                                    }

                                )

                                .done(function() {

                                    toastOk(
                                        'Deleted'
                                    );

                                    reloadTable();

                                })

                                .fail(function(xhr) {

                                    toastErr(

                                        xhr.responseJSON?.message ||

                                        'Failed'

                                    );

                                });

                        });

                }
            );


            /*
            |--------------------------------------------------------------------------
            | EXPORT
            |--------------------------------------------------------------------------
            */

            $('#btnExport').on(
                'click',
                function() {

                    const q =
                        $(DT_SEL)
                        .DataTable()
                        .search() || '';


                    const url =
                        @json(route('super.products.index'));


                    window.location =
                        url +
                        '?q=' +
                        encodeURIComponent(q);

                }
            );


            /*
            |--------------------------------------------------------------------------
            | MODAL CLOSED
            |--------------------------------------------------------------------------
            */

            modalEl.addEventListener(
                'hidden.bs.modal',
                clearErrors
            );


        })(jQuery);
    </script>
@endpush
