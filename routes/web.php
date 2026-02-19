<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DownloadController;

Route::get('/', function () {
    return redirect('/download-app');
});

Route::get('/download-app', [DownloadController::class, 'index']);
Route::get('/download-app/{slug}', [DownloadController::class, 'bySlug']);