<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\NotificationController;

// ── CLIENT: Cek status peserta ───────────────────────────
Route::post('/check-status', [ApplicantController::class, 'checkStatus']);

// ── CLIENT: Dokumen & Notifikasi (via kode akses) ────────
Route::prefix('applicant/{code}')->group(function () {
    // Dokumen
    Route::get('documents',                 [DocumentController::class, 'index']);
    Route::post('documents',                [DocumentController::class, 'store']);
    Route::post('documents/{id}/replace',   [DocumentController::class, 'replace']);  // ← FIX: ganti file
    Route::delete('documents/{id}',         [DocumentController::class, 'destroy']);
    // Notifikasi
    Route::get('notifications',             [NotificationController::class, 'index']);
    Route::post('notifications/read-all',   [NotificationController::class, 'readAll']);
    Route::patch('notifications/{id}/read', [NotificationController::class, 'read']);
});

// ── ADMIN: Auth ──────────────────────────────────────────
Route::post('/admin/login', [AuthController::class, 'login']);

// ── ADMIN: CRUD Peserta ───────────────────────────────────
Route::apiResource('applicants', ApplicantController::class);

// ── ADMIN: Dokumen Peserta ────────────────────────────────
Route::get('admin/documents',                        [DocumentController::class, 'adminIndex']);
Route::post('admin/documents',                       [DocumentController::class, 'adminStore']);
Route::delete('admin/documents/{id}',                [DocumentController::class, 'adminDestroy']);
Route::patch('admin/documents/{id}/verify',          [DocumentController::class, 'verify']);
Route::get('admin/applicants/{id}/documents',        [DocumentController::class, 'applicantDocuments']); // ← baru

// ── ADMIN: Laporan ────────────────────────────────────────
Route::get('/report/summary',    [ReportController::class, 'summary']);
Route::get('/report/export-csv', [ReportController::class, 'exportCsv']);