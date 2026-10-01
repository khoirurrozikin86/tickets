@extends('errors.layout')

@section('title', 'Akses Tidak Diizinkan')

@section('content')
    <p class="error-code">403</p>
    <p class="error-kicker">Area dengan akses terbatas</p>
    <h1 class="error-title">Kamu belum memiliki izin.</h1>
    <p class="error-description">
        Halaman ini hanya tersedia untuk akun dengan akses tertentu.
        Silakan kembali ke halaman sebelumnya atau kunjungi halaman utama.
    </p>
    <div class="error-actions">
        <a class="error-button" href="{{ url('/') }}">Kembali ke Beranda</a>
        <button class="error-button error-button-secondary" type="button" onclick="history.length > 1 ? history.back() : window.location.assign('/')">
            Halaman Sebelumnya
        </button>
    </div>
@endsection
