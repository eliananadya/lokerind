@extends('layouts.main')
@php
    $hideNavbar = true;
    $hideFooter = true;
@endphp
@section('content')
    <div class="error-page">
        <div class="error-content">
            <h1 class="error-code">403</h1>
            <h2 class="error-title">Forbidden</h2>
            <p class="error-text">Maaf, halaman yang Anda cari Tidak Dapat Diakses</p>
            <a href="{{ url('/') }}" class="btn-home">Kembali ke Beranda</a>
        </div>
    </div>
@endsection
