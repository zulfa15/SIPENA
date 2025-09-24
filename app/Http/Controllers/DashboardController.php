<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $hariini= date("Y-m-d");
        $bulanini = date("m"); //1 atau januari
        $tahunini = date("Y"); // 2025
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensihariini = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi',$hariini)->first();
        $historibulanini = DB::table('presensi')
            ->whereMonth('tgl_presensi', $bulanini)
            ->whereYear('tgl_presensi', $tahunini)
            ->orderBy('tgl_presensi')
            ->get();

        return view('dashboard.dashboard', compact('presensihariini','historibulanini'));
    }
}
