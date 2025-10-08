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
        $bulanini = date("m") * 1; //1 atau januari
        $tahunini = date("Y"); // 2025
        $nik = Auth::guard('karyawan')->user()->nik;
        $presensihariini = DB::table('presensi')->where('nik', $nik)->where('tgl_presensi',$hariini)->first();
        $historibulanini = DB::table('presensi')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_presensi)="' .$bulanini. '"')
            ->whereRaw('YEAR(tgl_presensi)="' .$tahunini. '"')
            ->orderBy('tgl_presensi')
            ->get();
        
        $rekappresensi = DB::table('presensi')
            ->selectRaw('COUNT(nik) as jmlhadir, SUM(IF(jam_in > "07:30",1,0)) as jmlterlambat', )
            ->where('nik',$nik)
            ->whereRaw('MONTH(tgl_presensi)="' .$bulanini. '"')
            ->whereRaw('YEAR(tgl_presensi)="' .$tahunini. '"')
            ->first();

        $leaderboard = DB::table('presensi')
            ->join('karyawan', 'presensi.nik', '=', 'karyawan.nik')
            ->where('tgl_presensi', $hariini)
            ->orderBy('jam_in')
            ->get();
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        
        $rekapcuti = DB::table('pengajuan_cuti')
            ->selectRaw('SUM(IF(status="i" OR status="s", 1, 0)) as jmlcuti')
            ->where('nik', $nik)
            ->whereRaw('MONTH(tgl_cuti)="' .$bulanini. '"')
            ->whereRaw('YEAR(tgl_cuti)="' .$tahunini. '"')
            ->where('status_approved',1)
            ->first();


        // Hitung jumlah hari kerja (1 - tanggal hari ini)
        $tanggalsekarang = date('j'); // ambil tanggal sekarang, contoh: 7
        $harikerja = 0;

        // Hitung jumlah hari kerja (Senin–Jumat) dalam bulan ini
        $harikerja = 0;
        $hariTerakhir = date('d'); // hanya sampai tanggal hari ini
        for ($day = 1; $day <= $hariTerakhir; $day++) {
            $tanggal = date('Y-m-', strtotime($tahunini . '-' . $bulanini . '-01')) . str_pad($day, 2, '0', STR_PAD_LEFT);
            $haridalamminggu = date('N', strtotime($tanggal)); // 1 = Senin, 7 = Minggu
            if ($haridalamminggu < 6) { // Senin–Jumat saja
                $harikerja++;
            }
        }

        // Hitung jumlah alpa (hari kerja tanpa kehadiran, izin, atau sakit)
        $alpa = $harikerja - ($rekappresensi->jmlhadir + $rekapcuti->jmlcuti);
        if ($alpa < 0) {
            $alpa = 0; // biar gak minus kalau datanya gak seimbang
        }

        

        return view('dashboard.dashboard', compact('presensihariini','historibulanini', 'namabulan', 
        'bulanini', 'tahunini', 'rekappresensi','leaderboard','rekapcuti','alpa'));
    }
}
