<?php

use App\Exports\AttendenceExport;
use App\Http\Controllers\ExportCutiController;
use App\Livewire\ExportCutiPdf;
use Illuminate\Support\Facades\Route;
use App\Livewire\Presensi;
use Maatwebsite\Excel\Facades\Excel;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

Route::group(['middleware' => 'auth'], function () {
    Route::get('presensi', Presensi::class)->name('presensi');
    Route::get('attendence/export', function () {
        return Excel::download(new AttendenceExport, 'attendence.xlsx');
    })->name('attendence-export');

    Route::get('/cuti/{cuti}/export-pdf', [ExportCutiController::class, 'exportPdf'])->name('cuti.export-pdf');
});

Route::get('/login', function () {
    return redirect('/internal/login');
})->name('login');
Route::get('/register', function () {
    return redirect('/internal/register');
})->name('register');
