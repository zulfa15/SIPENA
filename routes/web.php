<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\KaryawanController;


Route::middleware(['guest:karyawan'])->group(function(){
    Route::get('/', function () {
        return view('auth.login');
    })->name('login');

    Route::post('/proseslogin',[AuthController::class, 'proseslogin']);
    Route::get('/proseslogin', function () {
        return redirect('/');
    });
});



Route::middleware(['guest:user'])->group(function(){
    Route::get('/panel', function () {
        return view('auth.loginadmin');
    })->name('loginadmin');

    Route::post('/prosesloginadmin',[AuthController::class, 'prosesloginadmin']);
   
});

Route::middleware(['auth:karyawan'])->group(function(){
    Route::get('/dashboard',[DashboardController::class,'index']);
    Route::get('/proseslogout',[AuthController::class, 'proseslogout']);

    //presensi
    Route::get('/presensi/create',[PresensiController::class, 'create']);
    Route::post('/presensi/store',[PresensiController::class,'store']);

    //edit profile
    Route::get('/editprofile',[PresensiController::class,'editprofile']);
    Route::post('/presensi/{nik}/updateprofile',[PresensiController::class,'updateprofile']);

    //histori
    Route::get('/presensi/histori',[PresensiController::class,'histori']);
    Route::post('/gethistori',[PresensiController::class,'gethistori']);

    //Cuti
    Route::get('/presensi/cuti',[PresensiController::class,'cuti']);
    Route::get('/presensi/buatcuti', [PresensiController::class, 'buatcuti']);
    Route::post('/presensi/storecuti', [PresensiController::class, 'storecuti']);

});

Route::middleware(['auth:user'])->group(function()
{
    Route::get('/proseslogoutadmin',[AuthController::class, 'proseslogoutadmin']);
    Route::get('/panel/dashboardadmin',[DashboardController::class, 'dashboardadmin']);

    //Karyawan
    Route::get('/karyawan',[KaryawanController::class,'index']);
    Route::post('/karyawan/store',[KaryawanController::class,'store']);
});

