<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\EmployeeController;
use App\Http\Controllers\Api\AttendanceController;
use App\Http\Controllers\Api\KehadiranController;
use App\Http\Controllers\Api\PegawaiController;

Route::get('/', function () {
    return view('welcome');
});


Route::apiResource('pegawai', PegawaiController::class);

Route::post('kehadiran', [KehadiranController::class, 'store']);
Route::get('kehadiran/{pegawai}/{tahun}/{bulan}', [KehadiranController::class, 'report']);
