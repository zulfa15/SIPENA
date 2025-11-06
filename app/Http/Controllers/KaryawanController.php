<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;


class KaryawanController extends Controller
{
    
    public function index(Request $request)
    {
        $query = Karyawan::query();
        $query->select('karyawan.*');
        $query->orderBy('nama_lengkap');
        if(!empty($request->nama_karyawan)){
            $query->where('nama_lengkap','like','%' . $request->nama_karyawan . '%');
        }
        $karyawan = $query->paginate(2);
        return view('karyawan.index',compact('karyawan'));
    }

   public function store(Request $request)
{
    $request->validate([
        'nik' => 'required|unique:karyawan,nik',
        'nama_lengkap' => 'required',
        'jabatan' => 'required',
        'no_hp' => 'required',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $foto = null;

        if ($request->hasFile('foto')) {
            $foto = $request->nik . '.' . $request->file('foto')->getClientOriginalExtension();
            $request->file('foto')->storeAs('public/uploads/karyawan', $foto);
        }

        Karyawan::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'foto' => $foto,
            'password' => Hash::make('12345'),
        ]);

        return redirect()->back()->with('success', 'Data Berhasil Disimpan');
    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

}