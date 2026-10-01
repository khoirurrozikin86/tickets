@extends('errors.layout')

@section('title', 'Halaman Tidak Ditemukan')

@section('content')
    <p class="error-code">404</p>
    <p class="error-kicker">Sepertinya kita salah jalan</p>
    <h1 class="error-title">Halaman ini tidak ditemukan.</h1>
    <p class="error-description">
        Halaman yang kamu cari mungkin sudah dipindahkan atau alamatnya kurang tepat.
        Yuk kembali dan lanjutkan menjelajahi Dusun Semilir.
    </p>
    <div class="error-actions">
        <a class="error-button" href="{{ url('/') }}">Kembali ke Beranda</a>
        <a class="error-button error-button-secondary" href="{{ url('/tickets') }}">Lihat Tiket</a>
    </div>
@endsection
