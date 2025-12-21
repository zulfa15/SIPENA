<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;
use App\Http\Controllers\KonfigurasiController;

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
    Route::get('/admin/dinas-luar',[PresensiController::class,'dinasLuar']);
    Route::post('/admin/dinas-luar/approve',[PresensiController::class,'approveDinasLuar']);

    // KONFIGURASI
    Route::get('/konfigurasi/lokasikantor',[KonfigurasiController::class,'lokasikantor']);
    Route::post('/konfigurasi/updatelokasikantor',[KonfigurasiController::class,'updatelokasikantor']);
});

Route::middleware(['auth:admin'])->group(function () {

    Route::get('/admin/dinas-luar', [PresensiController::class, 'adminDinasLuar']);
    Route::post('/admin/dinas-luar/approve', [PresensiController::class, 'approveDinasLuar']);
    

});
