<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\DashboardController;


Route::post('/proseslogin',[AuthController::class, 'proseslogin']);

Route::middleware(['guest:karyawan'])->group(function() {
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');    
});

Route::middleware(['auth:karyawan'])->group(function (){
    Route::get('/dashboard',[DashboardController::class, 'index']);
    Route::get('/proseslogout', [AuthController::class, 'proseslogout']); 

    //Presensi
    Route::get('/presensi/create', [PresensiController::class, 'create']);
    Route::post('/presensi/store', [PresensiController::class, 'store']);
});