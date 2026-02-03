@extends('layouts.main')
@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp
@section('content')
    <div class="error-page">
        <div class="error-content">
            <h1 class="error-code">404</h1>
            <h2 class="error-title">Halaman Tidak Ditemukan</h2>
            <p class="error-text">Maaf, halaman yang Anda cari tidak ada atau telah dipindahkan</p>
            <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
        </div>
    </div>
@endsection
