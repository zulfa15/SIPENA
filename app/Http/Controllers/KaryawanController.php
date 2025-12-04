<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;



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
        $karyawan = $query->paginate(10);
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

public function edit(Request $request){
    $nik = $request->nik;
    $karyawan = DB::table('karyawan')->where('nik',$nik)->first();
    return view('karyawan.edit', compact('karyawan'));
}

public function update($nik, Request $request)
{
    $request->validate([
        'nama_lengkap' => 'required',
        'jabatan' => 'required',
        'no_hp' => 'required',
        'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
    ]);

    try {
        $karyawan = Karyawan::where('nik', $nik)->firstOrFail();

        // Ambil nama file lama dari database
        $old_foto = $karyawan->foto;
        $folderPath = 'public/uploads/karyawan/';

        // Default: pakai foto lama
        $foto = $old_foto;

        // Jika upload foto baru
        if ($request->hasFile('foto')) {

            // Hapus foto lama jika ada
            if ($old_foto && Storage::exists($folderPath . $old_foto)) {
                Storage::delete($folderPath . $old_foto);
            }

            // Ambil ekstensi baru
            $extension = $request->file('foto')->getClientOriginalExtension();

            // Generate nama file baru (nik.ext baru)
            $foto = $nik . '.' . $extension;

            // Upload foto baru
            $request->file('foto')->storeAs($folderPath, $foto);
        }

        // Update data
        $karyawan->update([
            'nama_lengkap' => $request->nama_lengkap,
            'jabatan' => $request->jabatan,
            'no_hp' => $request->no_hp,
            'foto' => $foto,
        ]);

        return redirect()->route('karyawan.index')->with('success', 'Data Berhasil Diperbarui');

    } catch (\Exception $e) {
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

public function delete($nik){
    $delete = DB::table('karyawan')->where('nik',$nik)->delete();
    if($delete){
        return Redirect::back()->with(['success'=>'Data Berhasil Dihapus']);
    }else{
        return Redirect::back()->with(['warning'=>'Data Gagal Dihapus']);
    }
}

}