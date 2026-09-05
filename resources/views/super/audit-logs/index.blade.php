@extends('layouts.admin')

@section('title', 'audit log')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">System</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                audit log
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
                            Audit Log
                        </h6>

                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">

                        <table id="audit-log-table" class="table w-100">

                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Module</th>
                                    <th>Record</th>
                                    <th>Description</th>
                                    <th>IP Address</th>
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


@push('scripts')
    <script>
        (function($) {

            'use strict';


            const DT_SEL = '#audit-log-table';


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
            | DataTable
            |--------------------------------------------------------------------------
            */

            const table = $(DT_SEL).DataTable({

                processing: true,

                serverSide: true,

                ajax: '{{ route('super.audit-logs.dt') }}',

                columns: [

                    {
                        data: 'created_at',
                        name: 'created_at'
                    },

                    {
                        data: 'user_name',
                        name: 'user.name',
                        orderable: false
                    },

                    {
                        data: 'action',
                        name: 'action'
                    },

                    {
                        data: 'module',
                        name: 'module'
                    },

                    {
                        data: 'record',
                        name: 'auditable_id',
                        orderable: false,
                        searchable: false
                    },

                    {
                        data: 'description',
                        name: 'description'
                    },

                    {
                        data: 'ip_address',
                        name: 'ip_address'
                    },

                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    }

                ],

                order: [
                    [0, 'desc']
                ]

            });


            table.on(
                'draw.dt',
                safeFeatherReplace
            );


        })(jQuery);
    </script>
@endpush
