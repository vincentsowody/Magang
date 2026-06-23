<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApplicantController;
use App\Http\Controllers\Api\DocumentController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\AuthController;

// ── AUTH ──────────────────────────────────────────────────────────────
Route::post('auth/login', [AuthController::class, 'login']);

// ── CLIENT: Cek Status (semua method & semua format URL) ──────────────
Route::match(['get','post'], 'check-status',      [ApplicantController::class, 'checkStatus']);
Route::match(['get','post'], 'applicants/status', [ApplicantController::class, 'checkStatus']);

// ── CLIENT: Dokumen Pelamar ───────────────────────────────────────────
Route::get   ('documents/{code}',               [DocumentController::class, 'index']);
Route::post  ('documents/{code}',               [DocumentController::class, 'store']);
Route::post  ('documents/{code}/{id}/replace',  [DocumentController::class, 'replace']);
Route::delete('documents/{code}/{id}',          [DocumentController::class, 'destroy']);

// Alias /applicant/{code}/documents (format yang dipakai JS client)
Route::get   ('applicant/{code}/documents',              [DocumentController::class, 'index']);
Route::post  ('applicant/{code}/documents',              [DocumentController::class, 'store']);
Route::post  ('applicant/{code}/documents/{id}/replace', [DocumentController::class, 'replace']);
Route::delete('applicant/{code}/documents/{id}',         [DocumentController::class, 'destroy']);

// ── CLIENT: Notifikasi ────────────────────────────────────────────────
Route::get  ('notifications/{code}',                [NotificationController::class, 'index']);
Route::post ('notifications/{code}/read-all',       [NotificationController::class, 'readAll']);
Route::match(['post','patch'], 'notifications/{code}/{id}/read', [NotificationController::class, 'read']);

// Alias /applicant/{code}/notifications
Route::get  ('applicant/{code}/notifications',                        [NotificationController::class, 'index']);
Route::post ('applicant/{code}/notifications/read-all',               [NotificationController::class, 'readAll']);
Route::match(['post','patch'], 'applicant/{code}/notifications/{id}/read', [NotificationController::class, 'read']);

// ── ADMIN: Kandidat (CRUD) ────────────────────────────────────────────
Route::get   ('applicants',      [ApplicantController::class, 'index']);
Route::post  ('applicants',      [ApplicantController::class, 'store']);
Route::put   ('applicants/{id}', [ApplicantController::class, 'update']);
Route::delete('applicants/{id}', [ApplicantController::class, 'destroy']);
Route::delete('applicants',      [ApplicantController::class, 'bulkDestroy']);

// FIX BUG: sebelumnya hanya ada GET ke method 'replyLetter' yang TIDAK ADA
// di controller (controller-nya bernama uploadReplyLetter/getReplyLetter).
// Akibatnya request upload (POST dari placement-modal.blade.php) selalu
// gagal 404 secara diam-diam — surat balasan tidak pernah benar-benar
// tersimpan walau toast di admin menampilkan "Berhasil".
Route::post('applicants/{id}/reply-letter', [ApplicantController::class, 'uploadReplyLetter']);
Route::get ('applicants/{id}/reply-letter', [ApplicantController::class, 'getReplyLetter']);

// ── ADMIN: Dokumen ────────────────────────────────────────────────────
Route::post  ('admin/documents',               [DocumentController::class, 'adminStore']);
Route::get   ('admin/documents',               [DocumentController::class, 'adminIndex']);
Route::delete('admin/documents/{id}',          [DocumentController::class, 'adminDestroy']);
Route::get   ('admin/documents/applicant/{id}',[DocumentController::class, 'applicantDocuments']);
Route::get   ('admin/applicants/{id}/documents',[DocumentController::class, 'applicantDocuments']);
Route::post  ('admin/documents/{id}/verify',   [DocumentController::class, 'verify']);

// ── ADMIN: Laporan ────────────────────────────────────────────────────
Route::get('admin/report/summary',     [ReportController::class, 'summary']);
Route::get('admin/report/export-csv',  [ReportController::class, 'exportCsv']);