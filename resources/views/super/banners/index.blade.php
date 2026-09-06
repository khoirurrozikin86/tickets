@extends('layouts.admin')

@section('title', 'banner')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">CMS</a>
            </li>

            <li class="breadcrumb-item active" aria-current="page">
                Banner
            </li>
        </ol>
    </nav>
@endsection


@section('content')

    <div class="row">

        <div class="col-md-12 grid-margin stretch-card">

            <div class="card">

                <div class="card-body">

                    {{-- =========================================================
                HEADER
                ========================================================== --}}

                    <div class="d-flex align-items-center justify-content-between mb-4">

                        <div>

                            <h6 class="card-title mb-1">
                                Banner
                            </h6>

                            <p class="text-muted mb-0">
                                Kelola banner yang tampil pada halaman website.
                            </p>

                        </div>


                        @can('banners.create')
                            <button type="button" class="btn btn-primary" id="btn-new-banner">

                                <i data-feather="plus"></i>

                                New

                            </button>
                        @endcan

                    </div>


                    {{-- =========================================================
                SUCCESS ALERT
                ========================================================== --}}

                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">

                            {{ session('success') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert">
                            </button>

                        </div>
                    @endif


                    {{-- =========================================================
                FILTER
                ========================================================== --}}

                    <div class="row mb-4">

                        <div class="col-md-4 mb-3">

                            <label class="form-label">
                                Banner
                            </label>

                            <input type="text" id="filter-title" class="form-control"
                                placeholder="Cari title / subtitle...">

                        </div>


                        <div class="col-md-3 mb-3">

                            <label class="form-label">
                                Status
                            </label>

                            <select id="filter-status" class="form-select">

                                <option value="">
                                    Semua Status
                                </option>

                                <option value="ACTIVE">
                                    ACTIVE
                                </option>

                                <option value="INACTIVE">
                                    INACTIVE
                                </option>

                            </select>

                        </div>


                        <div class="col-md-2 mb-3 d-flex align-items-end">

                            <button type="button" id="btn-filter" class="btn btn-primary w-100" title="Filter">

                                <i data-feather="filter"></i>

                            </button>

                        </div>

                    </div>


                    {{-- =========================================================
                DATATABLE
                ========================================================== --}}

                    <div class="table-responsive">

                        <table id="banner-table" class="table table-bordered table-hover" style="width:100%">

                            <thead>

                                <tr>

                                    <th width="100">
                                        Image
                                    </th>

                                    <th>
                                        Banner
                                    </th>

                                    <th width="120">
                                        Button
                                    </th>

                                    <th width="80">
                                        Order
                                    </th>

                                    <th width="100">
                                        Status
                                    </th>

                                    <th width="80">
                                        Actions
                                    </th>

                                </tr>

                            </thead>

                            <tbody></tbody>

                        </table>

                    </div>

                </div>

            </div>

        </div>

    </div>



    {{-- =============================================================
MODAL NEW / EDIT
============================================================= --}}

    <div class="modal fade" id="banner-modal" tabindex="-1" aria-hidden="true">

        <div class="modal-dialog modal-lg modal-dialog-centered">

            <div class="modal-content">


                {{-- HEADER --}}

                <div class="modal-header">

                    <h5 class="modal-title" id="banner-modal-title">

                        New Banner

                    </h5>

                    <button type="button" class="btn-close" data-bs-dismiss="modal">
                    </button>

                </div>


                {{-- FORM --}}

                <form id="banner-form" method="POST" enctype="multipart/form-data">

                    @csrf


                    <div class="modal-body">

                        <div class="row">


                            {{-- =================================================
                        IMAGE
                        ================================================== --}}

                            <div class="col-md-5 mb-3">

                                <label class="form-label">

                                    Banner Image

                                    <span class="text-danger">
                                        *
                                    </span>

                                </label>


                                <input type="file" name="image" id="banner-image" class="form-control"
                                    accept=".jpg,.jpeg,.png,.webp">


                                <small class="text-muted d-block mt-1">

                                    JPG, JPEG, PNG, WEBP.

                                    Maksimal 5 MB.

                                </small>


                                {{-- IMAGE PREVIEW --}}

                                <div id="image-preview-container" class="mt-3 d-none">

                                    <div class="mb-2 text-muted small">
                                        Preview
                                    </div>

                                    <img id="image-preview" src="" alt="Banner Preview" class="banner-preview">

                                </div>

                            </div>



                            {{-- =================================================
                        CONTENT
                        ================================================== --}}

                            <div class="col-md-7">


                                {{-- TITLE --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Title
                                    </label>

                                    <input type="text" name="title" id="banner-title" class="form-control"
                                        placeholder="Contoh: Liburan Seru di Dusun Semilir">

                                </div>



                                {{-- SUBTITLE --}}

                                <div class="mb-3">

                                    <label class="form-label">
                                        Subtitle
                                    </label>

                                    <textarea name="subtitle" id="banner-subtitle" class="form-control" rows="3"
                                        placeholder="Deskripsi singkat banner..."></textarea>

                                </div>



                                {{-- BUTTON --}}

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Button Text
                                        </label>

                                        <input type="text" name="button_text" id="banner-button-text"
                                            class="form-control" placeholder="Beli Sekarang">

                                    </div>


                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Button URL
                                        </label>

                                        <input type="text" name="button_url" id="banner-button-url"
                                            class="form-control" placeholder="/tickets">

                                    </div>

                                </div>



                                {{-- ORDER + STATUS --}}

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Sort Order
                                        </label>

                                        <input type="number" name="sort_order" id="banner-sort-order"
                                            class="form-control" value="0" min="0">

                                    </div>


                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Status
                                        </label>

                                        <select name="is_active" id="banner-status" class="form-select">

                                            <option value="1">
                                                ACTIVE
                                            </option>

                                            <option value="0">
                                                INACTIVE
                                            </option>

                                        </select>

                                    </div>

                                </div>

                            </div>

                        </div>

                    </div>



                    {{-- FOOTER --}}

                    <div class="modal-footer">

                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">

                            Cancel

                        </button>


                        <button type="submit" class="btn btn-primary" id="btn-save-banner">

                            <i data-feather="save"></i>

                            <span>
                                Save
                            </span>

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

@endsection



{{-- =============================================================
STYLE
============================================================= --}}

@push('styles')
    <style>
        .banner-thumb {
            width: 90px;
            height: 50px;
            object-fit: cover;
            border-radius: 6px;
            border: 1px solid #e5e7eb;
        }


        .banner-preview {
            width: 100%;
            max-height: 190px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #e5e7eb;
            background: #f8f9fa;
        }


        .banner-status {
            font-size: 11px;
            font-weight: 600;
            padding: 5px 8px;
            border-radius: 5px;
        }


        .banner-action {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            width: 24px;
            height: 24px;

            color: #6571ff;

            text-decoration: none;

            border-radius: 4px;

            transition: all .15s ease;
        }


        .banner-action svg {
            width: 15px;
            height: 15px;
        }


        .banner-action:hover {
            color: #3f4bd8;
            background: #f1f3ff;
        }


        #banner-table td {
            vertical-align: middle;
        }
    </style>
@endpush



{{-- =============================================================
SCRIPT
============================================================= --}}

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {


            /*
            |--------------------------------------------------------------------------
            | Feather
            |--------------------------------------------------------------------------
            */

            if (
                window.feather &&
                typeof window.feather.replace === 'function'
            ) {
                window.feather.replace();
            }



            /*
            |--------------------------------------------------------------------------
            | Elements
            |--------------------------------------------------------------------------
            */

            const modalElement =
                document.getElementById('banner-modal');


            const modal =
                new bootstrap.Modal(modalElement);


            const form =
                document.getElementById('banner-form');


            let editingId = null;



            /*
            |--------------------------------------------------------------------------
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = $('#banner-table').DataTable({

                processing: true,

                serverSide: true,


                ajax: {

                    url: "{{ route('super.banners.dt') }}",

                    data: function(d) {

                        d.title =
                            $('#filter-title').val();

                        d.status =
                            $('#filter-status').val();

                    }

                },


                columns: [

                    {
                        data: 'image',
                        name: 'image',

                        orderable: false,
                        searchable: false,

                        className: 'text-center'
                    },


                    {
                        data: 'title',
                        name: 'title'
                    },


                    {
                        data: 'button_text',
                        name: 'button_text',

                        orderable: false
                    },


                    {
                        data: 'sort_order',
                        name: 'sort_order',

                        className: 'text-center'
                    },


                    {
                        data: 'is_active',
                        name: 'is_active',

                        orderable: false,
                        searchable: false,

                        className: 'text-center'
                    },


                    {
                        data: 'actions',
                        name: 'actions',

                        orderable: false,
                        searchable: false,

                        className: 'text-center'
                    }

                ],


                order: [
                    [3, 'asc']
                ],


                pageLength: 25,


                language: {

                    processing: 'Memuat...',

                    search: 'Cari:',

                    lengthMenu: 'Tampilkan _MENU_ data',

                    info: 'Menampilkan _START_ sampai _END_ dari _TOTAL_ data',

                    infoEmpty: 'Tidak ada data',

                    zeroRecords: 'Banner tidak ditemukan',

                    paginate: {

                        first: 'Awal',

                        last: 'Akhir',

                        next: '›',

                        previous: '‹'

                    }

                }

            });



            /*
            |--------------------------------------------------------------------------
            | Filter
            |--------------------------------------------------------------------------
            */

            $('#btn-filter').on(
                'click',
                function() {

                    table.ajax.reload();

                }
            );


            $('#filter-title').on(
                'keypress',
                function(e) {

                    if (e.which === 13) {

                        table.ajax.reload();

                    }

                }
            );


            $('#filter-status').on(
                'change',
                function() {

                    table.ajax.reload();

                }
            );



            /*
            |--------------------------------------------------------------------------
            | NEW
            |--------------------------------------------------------------------------
            */

            $('#btn-new-banner').on(
                'click',
                function() {

                    editingId = null;


                    form.reset();


                    /*
                    | Form kembali ke STORE
                    */

                    form.action =
                        "{{ route('super.banners.store') }}";


                    /*
                    | Hilangkan method PUT
                    */

                    $(form)
                        .find('input[name="_method"]')
                        .remove();


                    /*
                    | Modal title
                    */

                    $('#banner-modal-title')
                        .text('New Banner');


                    /*
                    | Default
                    */

                    $('#banner-status')
                        .val('1');


                    $('#banner-sort-order')
                        .val('0');


                    /*
                    | Reset preview
                    */

                    $('#image-preview')
                        .attr('src', '');


                    $('#image-preview-container')
                        .addClass('d-none');


                    modal.show();

                }
            );



            /*
            |--------------------------------------------------------------------------
            | IMAGE PREVIEW
            |--------------------------------------------------------------------------
            */

            $('#banner-image').on(
                'change',
                function() {

                    const file =
                        this.files[0];


                    if (!file) {

                        $('#image-preview-container')
                            .addClass('d-none');

                        $('#image-preview')
                            .attr('src', '');

                        return;
                    }


                    const reader =
                        new FileReader();


                    reader.onload =
                        function(e) {

                            $('#image-preview')
                                .attr(
                                    'src',
                                    e.target.result
                                );


                            $('#image-preview-container')
                                .removeClass('d-none');

                        };


                    reader.readAsDataURL(file);

                }
            );



            /*
            |--------------------------------------------------------------------------
            | EDIT
            |--------------------------------------------------------------------------
            |
            | Mengikuti table-actions milik Product.
            |
            | .btn-edit-role
            | data-update-url
            | data-payload
            |
            */

            $(document).on(
                'click',
                '.btn-edit-role',
                function(e) {

                    e.preventDefault();


                    const button =
                        $(this);


                    /*
                    | Ambil payload dari table-actions
                    */

                    let payload = {};


                    try {

                        payload =
                            JSON.parse(
                                button.attr('data-payload') || '{}'
                            );

                    } catch (error) {

                        console.error(
                            'Banner payload tidak valid:',
                            error
                        );

                        Swal.fire({

                            icon: 'error',

                            title: 'Gagal',

                            text: 'Data banner tidak dapat dibaca.'

                        });

                        return;
                    }


                    /*
                    | ID
                    */

                    editingId =
                        payload.id;


                    /*
                    | Modal title
                    */

                    $('#banner-modal-title')
                        .text('Edit Banner');


                    /*
                    | Isi form
                    */

                    $('#banner-title')
                        .val(
                            payload.title || ''
                        );


                    $('#banner-subtitle')
                        .val(
                            payload.subtitle || ''
                        );


                    $('#banner-button-text')
                        .val(
                            payload.button_text || ''
                        );


                    $('#banner-button-url')
                        .val(
                            payload.button_url || ''
                        );


                    $('#banner-sort-order')
                        .val(
                            payload.sort_order ?? 0
                        );


                    $('#banner-status')
                        .val(
                            payload.is_active ? '1' : '0'
                        );


                    /*
                    | File input tidak boleh diisi
                    */

                    $('#banner-image')
                        .val('');


                    /*
                    | Preview gambar lama
                    */

                    if (payload.image) {

                        $('#image-preview')
                            .attr(
                                'src',
                                payload.image
                            );


                        $('#image-preview-container')
                            .removeClass('d-none');

                    } else {

                        $('#image-preview')
                            .attr('src', '');


                        $('#image-preview-container')
                            .addClass('d-none');

                    }


                    /*
                    | Tambahkan method PUT
                    */

                    $(form)
                        .find('input[name="_method"]')
                        .remove();


                    $(form).prepend(
                        '<input type="hidden" name="_method" value="PUT">'
                    );


                    /*
                    | URL UPDATE
                    |
                    | Diambil langsung dari table-actions
                    */

                    form.action =
                        button.data('update-url');


                    modal.show();

                }
            );



            /*
            |--------------------------------------------------------------------------
            | SUBMIT
            |--------------------------------------------------------------------------
            */

            $('#banner-form').on(
                'submit',
                function(e) {

                    e.preventDefault();


                    const submitButton =
                        $('#btn-save-banner');


                    const formData =
                        new FormData(this);


                    /*
                    | Jika CREATE
                    */

                    if (!editingId) {

                        formData.delete('_method');

                    }


                    submitButton
                        .prop('disabled', true);


                    /*
                    | Ubah text button
                    */

                    submitButton
                        .find('span')
                        .text(
                            editingId ?
                            'Updating...' :
                            'Saving...'
                        );


                    $.ajax({

                        url: form.action,

                        type: 'POST',

                        data: formData,

                        processData: false,

                        contentType: false,


                        headers: {

                            'Accept': 'application/json',

                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                .attr('content')

                        },


                        success: function(response) {


                            modal.hide();


                            table.ajax.reload(
                                null,
                                false
                            );


                            Swal.fire({

                                icon: 'success',

                                title: 'Berhasil',

                                text: response.message ||
                                    (
                                        editingId ?
                                        'Banner berhasil diperbarui.' :
                                        'Banner berhasil ditambahkan.'
                                    ),

                                timer: 1800,

                                showConfirmButton: false

                            });


                            /*
                            | Reset state
                            */

                            editingId = null;

                        },


                        error: function(xhr) {


                            let message =
                                'Terjadi kesalahan.';


                            /*
                            | Validation error
                            */

                            if (
                                xhr.responseJSON &&
                                xhr.responseJSON.errors
                            ) {

                                message =
                                    Object.values(
                                        xhr.responseJSON.errors
                                    )
                                    .flat()
                                    .join('<br>');

                            }


                            /*
                            | General error
                            */
                            else if (
                                xhr.responseJSON &&
                                xhr.responseJSON.message
                            ) {

                                message =
                                    xhr.responseJSON.message;

                            }


                            Swal.fire({

                                icon: 'error',

                                title: 'Gagal',

                                html: message

                            });

                        },


                        complete: function() {


                            submitButton
                                .prop(
                                    'disabled',
                                    false
                                );


                            submitButton
                                .find('span')
                                .text('Save');

                        }

                    });

                }
            );



            /*
            |--------------------------------------------------------------------------
            | DELETE
            |--------------------------------------------------------------------------
            |
            | Mengikuti table-actions milik Product.
            |
            | .btn-delete-role
            | data-url
            | data-confirm
            |
            */

            $(document).on(
                'click',
                '.btn-delete-role',
                function(e) {

                    e.preventDefault();


                    const button =
                        $(this);


                    const url =
                        button.data('url');


                    const confirmMessage =
                        button.data('confirm') ||
                        'Delete Banner?';


                    Swal.fire({

                            icon: 'warning',

                            title: 'Hapus Banner?',

                            text: confirmMessage,

                            showCancelButton: true,

                            confirmButtonText: 'Ya, Hapus',

                            cancelButtonText: 'Batal',

                            reverseButtons: true

                        })
                        .then(
                            function(result) {

                                if (!result.isConfirmed) {
                                    return;
                                }


                                /*
                                | Disable action sementara
                                */

                                button.css(
                                    'pointer-events',
                                    'none'
                                );


                                $.ajax({

                                    url: url,

                                    type: 'POST',

                                    data: {

                                        _method: 'DELETE',

                                        _token: $('meta[name="csrf-token"]')
                                            .attr('content')

                                    },


                                    headers: {

                                        'Accept': 'application/json'

                                    },


                                    success: function(response) {


                                        table.ajax.reload(
                                            null,
                                            false
                                        );


                                        Swal.fire({

                                            icon: 'success',

                                            title: 'Berhasil',

                                            text: response.message ||
                                                'Banner berhasil dihapus.',

                                            timer: 1800,

                                            showConfirmButton: false

                                        });

                                    },


                                    error: function(xhr) {


                                        Swal.fire({

                                            icon: 'error',

                                            title: 'Gagal',

                                            text: xhr.responseJSON?.message ||
                                                'Banner gagal dihapus.'

                                        });

                                    },


                                    complete: function() {

                                        button.css(
                                            'pointer-events',
                                            ''
                                        );

                                    }

                                });

                            }
                        );

                }
            );



            /*
            |--------------------------------------------------------------------------
            | FEATHER SETELAH DATATABLE DRAW
            |--------------------------------------------------------------------------
            */

            $('#banner-table').on(
                'draw.dt',
                function() {

                    if (
                        window.feather &&
                        typeof window.feather.replace === 'function'
                    ) {

                        window.feather.replace();

                    }

                }
            );

        });
    </script>
@endpush
