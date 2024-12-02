<?php

use App\Http\Controllers\ExportCutiController;
use App\Livewire\ExportCutiPdf;
use Illuminate\Support\Facades\Route;
use App\Livewire\Presensi;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['middleware' => 'auth'], function () {
    Route::get('presensi', Presensi::class)->name('presensi');
});

Route::get('/cuti/{cuti}/export-pdf', [ExportCutiController::class, 'exportPdf'])->name('cuti.export-pdf');

Route::get('/login', function () {
    return redirect('/internal/login');
})->name('login');
