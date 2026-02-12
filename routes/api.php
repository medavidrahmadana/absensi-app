<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\KehadiranController;
use App\Http\Controllers\Api\PegawaiController;

Route::apiResource('pegawai', PegawaiController::class);

Route::post('kehadiran', [KehadiranController::class, 'store']);
Route::get('kehadiran/{pegawai}/{tahun}/{bulan}', [KehadiranController::class, 'report']);
