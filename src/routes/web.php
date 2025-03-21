<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\RowController;
use App\Http\Controllers\UploadController;
use App\Http\Middleware\AuthenticateOnceWithBasicAuth;

Route::middleware(AuthenticateOnceWithBasicAuth::class)->group(function () {
    Route::get('/upload', [UploadController::class, 'showForm'])->name('upload.form');
    Route::post('/upload', [UploadController::class, 'upload'])->name('upload.submit');
});

Route::get('/rows', [RowController::class, 'index'])->name('rows');

Route::get('/get-progress', [RowController::class, 'getProgress'])->name('get-progress');