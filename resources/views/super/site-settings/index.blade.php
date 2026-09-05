@extends('layouts.admin')

@section('title', 'Site Settings')

@section('breadcrumb')
    <nav class="page-breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="#">Website</a>
            </li>
            <li class="breadcrumb-item active" aria-current="page">
                Site Settings
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
                        <div>
                            <h6 class="card-title mb-1">
                                Site Settings
                            </h6>

                            <p class="text-muted mb-0">
                                Pengaturan informasi utama website Dusun Semilir.
                            </p>
                        </div>
                    </div>

                    {{-- Success --}}
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            {{ session('success') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert">
                            </button>
                        </div>
                    @endif

                    {{-- Error --}}
                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            {{ session('error') }}

                            <button type="button" class="btn-close" data-bs-dismiss="alert">
                            </button>
                        </div>
                    @endif

                    {{-- Validation Error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif


                    <form id="site-setting-form" method="POST" action="{{ route('super.site-settings.update') }}"
                        enctype="multipart/form-data">

                        @csrf

                        @method('PUT')


                        {{-- ===================================== --}}
                        {{-- LOOP SEMUA GROUP DARI DATABASE --}}
                        {{-- ===================================== --}}

                        @forelse ($settings as $group => $groupSettings)

                            <div class="setting-section">

                                {{-- Section Header --}}
                                <div class="setting-section-title">

                                    <div class="setting-section-icon">

                                        @php
                                            $icons = [
                                                'GENERAL' => 'globe',
                                                'BRAND' => 'image',
                                                'CONTACT' => 'phone',
                                                'SOCIAL' => 'share-2',
                                                'FOOTER' => 'layout',
                                            ];

                                            $icon = $icons[$group] ?? 'settings';
                                        @endphp

                                        <i data-feather="{{ $icon }}"></i>

                                    </div>

                                    <div>

                                        <h6 class="mb-0">
                                            {{ ucwords(strtolower($group)) }}
                                        </h6>

                                        <small class="text-muted">
                                            Pengaturan
                                            {{ strtolower($group) }}
                                            website
                                        </small>

                                    </div>

                                </div>


                                <div class="row">

                                    {{-- ================================= --}}
                                    {{-- LOOP SETTING --}}
                                    {{-- ================================= --}}

                                    @foreach ($groupSettings as $setting)
                                        @php
                                            $key = $setting->key;
                                            $value = $setting->value;
                                            $type = $setting->type;
                                        @endphp


                                        {{-- ============================== --}}
                                        {{-- TEXT --}}
                                        {{-- ============================== --}}

                                        @if ($type === 'text')
                                            <div class="col-md-6 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <input type="text" name="settings[{{ $key }}]"
                                                    class="form-control" value="{{ old('settings.' . $key, $value) }}"
                                                    placeholder="{{ $setting->label }}">

                                                @if ($setting->description)
                                                    <small class="text-muted">
                                                        {{ $setting->description }}
                                                    </small>
                                                @endif

                                            </div>


                                            {{-- ============================== --}}
                                            {{-- EMAIL --}}
                                            {{-- ============================== --}}
                                        @elseif ($type === 'email')
                                            <div class="col-md-6 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <input type="email" name="settings[{{ $key }}]"
                                                    class="form-control" value="{{ old('settings.' . $key, $value) }}"
                                                    placeholder="{{ $setting->label }}">

                                                @if ($setting->description)
                                                    <small class="text-muted">
                                                        {{ $setting->description }}
                                                    </small>
                                                @endif

                                            </div>


                                            {{-- ============================== --}}
                                            {{-- PHONE --}}
                                            {{-- ============================== --}}
                                        @elseif ($type === 'phone')
                                            <div class="col-md-6 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <input type="text" name="settings[{{ $key }}]"
                                                    class="form-control" value="{{ old('settings.' . $key, $value) }}"
                                                    placeholder="{{ $setting->label }}">

                                                @if ($setting->description)
                                                    <small class="text-muted">
                                                        {{ $setting->description }}
                                                    </small>
                                                @endif

                                            </div>


                                            {{-- ============================== --}}
                                            {{-- URL --}}
                                            {{-- ============================== --}}
                                        @elseif ($type === 'url')
                                            <div class="col-md-6 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <div class="input-group">

                                                    <span class="input-group-text">
                                                        <i data-feather="link"></i>
                                                    </span>

                                                    <input type="url" name="settings[{{ $key }}]"
                                                        class="form-control" value="{{ old('settings.' . $key, $value) }}"
                                                        placeholder="https://">

                                                </div>

                                                @if ($setting->description)
                                                    <small class="text-muted">
                                                        {{ $setting->description }}
                                                    </small>
                                                @endif

                                            </div>


                                            {{-- ============================== --}}
                                            {{-- TEXTAREA --}}
                                            {{-- ============================== --}}
                                        @elseif ($type === 'textarea')
                                            <div class="col-md-12 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <textarea name="settings[{{ $key }}]" rows="3" class="form-control"
                                                    placeholder="{{ $setting->label }}">{{ old('settings.' . $key, $value) }}</textarea>

                                                @if ($setting->description)
                                                    <small class="text-muted">
                                                        {{ $setting->description }}
                                                    </small>
                                                @endif

                                            </div>


                                            {{-- ============================== --}}
                                            {{-- IMAGE --}}
                                            {{-- ============================== --}}
                                        @elseif ($type === 'image')
                                            <div class="col-md-6 mb-4">

                                                <label class="form-label">
                                                    {{ $setting->label }}
                                                </label>

                                                <div class="logo-upload-box">

                                                    <div class="logo-preview">

                                                        @if ($value)
                                                            <img src="{{ asset($value) }}"
                                                                id="preview-{{ $key }}"
                                                                alt="{{ $setting->label }}">
                                                        @else
                                                            <div class="logo-placeholder">

                                                                <i data-feather="image"></i>

                                                                <span>
                                                                    Belum ada gambar
                                                                </span>

                                                            </div>
                                                        @endif

                                                    </div>


                                                    <div class="mt-3">

                                                        <input type="file" name="files[{{ $key }}]"
                                                            id="file-{{ $key }}"
                                                            class="form-control setting-image-input"
                                                            data-preview="preview-{{ $key }}"
                                                            accept="image/png,image/jpeg,image/webp,image/svg+xml">

                                                        <small class="text-muted">
                                                            JPG, PNG, WEBP atau SVG.
                                                        </small>

                                                    </div>

                                                </div>

                                            </div>
                                        @endif
                                    @endforeach

                                </div>

                            </div>

                        @empty

                            <div class="alert alert-warning">
                                Belum ada data Site Settings.
                            </div>

                        @endforelse


                        {{-- Submit --}}
                        <div class="d-flex justify-content-end mt-4">

                            <button type="submit" class="btn btn-primary px-4" id="btn-save-settings">

                                <i data-feather="save"></i>

                                <span>
                                    Simpan Perubahan
                                </span>

                            </button>

                        </div>

                    </form>

                </div>
            </div>
        </div>
    </div>
@endsection


@push('styles')
    <style>
        .setting-section {
            border: 1px solid #e9ecef;
            border-radius: 8px;
            padding: 24px;
            margin-bottom: 20px;
            background: #fff;
        }

        .setting-section-title {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 24px;
            padding-bottom: 15px;
            border-bottom: 1px solid #edf0f2;
        }

        .setting-section-icon {
            width: 38px;
            height: 38px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f1f3ff;
            color: #6571ff;
        }

        .setting-section-icon svg {
            width: 18px;
            height: 18px;
        }

        .logo-upload-box {
            border: 1px dashed #d9dee3;
            border-radius: 8px;
            padding: 20px;
            background: #fafbfc;
        }

        .logo-preview {
            min-height: 130px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 6px;
            background: #fff;
            border: 1px solid #edf0f2;
            padding: 15px;
        }

        .logo-preview img {
            max-width: 220px;
            max-height: 100px;
            object-fit: contain;
        }

        .logo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 8px;
            color: #adb5bd;
        }

        .logo-placeholder svg {
            width: 30px;
            height: 30px;
        }

        .input-group-text svg {
            width: 15px;
            height: 15px;
        }

        #btn-save-settings {
            display: inline-flex;
            align-items: center;
            gap: 7px;
        }

        #btn-save-settings svg {
            width: 16px;
            height: 16px;
        }
    </style>
@endpush


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
            | Image Preview
            |--------------------------------------------------------------------------
            */

            document
                .querySelectorAll('.setting-image-input')
                .forEach(function(input) {

                    input.addEventListener('change', function(event) {

                        const file = event.target.files[0];

                        if (!file) {
                            return;
                        }

                        const previewId =
                            input.dataset.preview;

                        const preview =
                            document.getElementById(previewId);

                        if (!preview) {
                            return;
                        }

                        const reader =
                            new FileReader();

                        reader.onload = function(e) {

                            preview.src =
                                e.target.result;

                        };

                        reader.readAsDataURL(file);

                    });

                });


            /*
            |--------------------------------------------------------------------------
            | Submit
            |--------------------------------------------------------------------------
            */

            const form =
                document.getElementById('site-setting-form');

            const button =
                document.getElementById('btn-save-settings');

            if (form) {

                form.addEventListener('submit', function() {

                    if (button) {

                        button.disabled = true;

                        const text =
                            button.querySelector('span');

                        if (text) {
                            text.textContent =
                                'Menyimpan...';
                        }

                    }

                });

            }

        });
    </script>
@endpush
