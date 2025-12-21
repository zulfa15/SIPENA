<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DinasLuarController extends Controller
{
    /* ================= LIST PENGAJUAN ================= */
    public function dinasluar()
    {
        $data = DB::table('presensi')
            ->join('karyawan','presensi.nik','=','karyawan.nik')
            ->whereNotNull('presensi.jenis_dinas')
            ->select(
                'presensi.*',
                'karyawan.nama_lengkap',
                'karyawan.jabatan'
            )
            ->orderByDesc('presensi.tgl_presensi')

            ->get();

        return view('presensi.dinasluar', compact('data'));
    }

    /* ================= APPROVE / REJECT ================= */
    public function approve(Request $request)
    {
        $request->validate([
            'id'     => 'required',
            'status' => 'required|in:1,2'
        ]);

        DB::table('presensi')
            ->where('id', $request->id)
            ->update([
                'status_approved' => $request->status
            ]);

        return back()->with('success','Status berhasil diperbarui');
    }
}
