<?php

use Illuminate\Support\Facades\Route;

// Halaman Utama (Client/Peserta)
Route::get('/', function () {
    return view('client.index');
});

// Halaman Login Admin
Route::get('/admin', function () {
    return view('admin.login');
});

// Halaman Dashboard Admin
Route::get('/admin/dashboard', function () {
    return view('admin.dashboard');
});