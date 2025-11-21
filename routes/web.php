<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Support\Facades\Auth;


Route::get('/', function () {
    return view('candidates.lowongan');
});

Route::get('/register', function () {
    return view('auth.register');
});
