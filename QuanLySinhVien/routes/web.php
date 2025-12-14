<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TranscriptController;

Route::get('/', function () {
    return view('welcome');
});

// Transcript routes (protected by auth middleware)
Route::middleware(['auth'])->group(function () {
    Route::get('/transcript/download', [TranscriptController::class, 'download'])->name('transcript.download');
    Route::get('/transcript/view', [TranscriptController::class, 'view'])->name('transcript.view');
});
