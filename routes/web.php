<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;
use App\Http\Controllers\DinasLuarController;

/*
|--------------------------------------------------------------------------
| AUTH KARYAWAN
|--------------------------------------------------------------------------
*/
Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', fn () => view('auth.login'))->name('login');
    Route::post('/proseslogin',[AuthController::class, 'proseslogin']);
});

/*
|--------------------------------------------------------------------------
| AUTH ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', fn () => view('auth.loginadmin'))->name('loginadmin');
    Route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);

});

/*
|--------------------------------------------------------------------------
| KARYAWAN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class,'index']);
    Route::get('/proseslogout',[AuthController::class, 'proseslogout']);

    // PRESENSI
    Route::get('/presensi/create',[PresensiController::class, 'create']);
    Route::post('/presensi/store',[PresensiController::class,'store']);

    // ✅ DINAS LUAR (LUAR RADIUS)
    Route::post('/presensi/luar-radius', [PresensiController::class, 'storeLuarRadius']);


    // PROFILE
    Route::get('/editprofile',[PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile',[PresensiController::class,'updateprofile']);

    // HISTORI
    Route::get('/presensi/histori',[PresensiController::class,'histori']);
    Route::post('/gethistori',[PresensiController::class,'gethistori']);

    // CUTI
    Route::get('/presensi/cuti',[PresensiController::class,'cuti']);
    Route::get('/presensi/buatcuti',[PresensiController::class,'buatcuti']);
    Route::post('/presensi/storecuti',[PresensiController::class,'storecuti']);
});

/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:user'])->group(function(){
    Route::get('/proseslogoutadmin',[AuthController::class,'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin',[DashboardController::class,'dashboardadmin']);

    // KARYAWAN
    Route::get('/karyawan',[KaryawanController::class,'index']);


    // PRESENSI
    Route::get('/presensi/monitoring',[PresensiController::class,'monitoring']);
    Route::post('/getpresensi',[PresensiController::class,'getpresensi']);

    // IZIN SAKIT
    Route::get('/presensi/izinsakit',[PresensiController::class,'izinsakit']);
    Route::post('/presensi/approveizinsakit',[PresensiController::class,'approveizinsakit']);
    Route::post('/presensi/batalkanizinsakit',[PresensiController::class,'batalkanizinsakit']);

    // ✅ DINAS LUAR (ADMIN)
    Route::get('/presensi/dinasluar', [DinasLuarController::class, 'dinasluar']);
    Route::post('/presensi/dinasluar/approve', [DinasLuarController::class, 'approve']);

    // KONFIGURASI
    Route::get('/konfigurasi/lokasikantor',[KonfigurasiController::class,'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor',[KonfigurasiController::class,'updatelokasikantor']);

    Route::post('/tampilkanpeta', [PresensiController::class, 'tampilkanPeta']);

    // ================= LAPORAN PRESENSI =================
    Route::get('/presensi/laporan', [PresensiController::class, 'laporan']);
    Route::post('/presensi/cetaklaporan', [PresensiController::class, 'cetaklaporan']);

    // ================= REKAP PRESENSI =================
    Route::get('/presensi/rekap', [PresensiController::class, 'rekap']);
    Route::post('/presensi/cetakrekap', [PresensiController::class, 'cetakrekap']);

    
    
    
});


    
