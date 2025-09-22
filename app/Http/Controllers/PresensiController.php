<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB; 


class PresensiController extends Controller
{
    public function create(){
        return view('presensi.create');
    }


    public function store(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam = date("H:i:s");
        $lokasi = $request->lokasi;
        $image = $request->image; 
        $folderPath = "public/uploads/absensi/";
        $formatName = $nik . "-" . $tgl_presensi;
        $image_parts = explode(";base64", $image);
        $image_base64 = base64_decode($image_parts[1]);
        $fileName = $formatName . ".png";
        $file = $folderPath . $fileName;

        $data = [
            'nik' => $nik,
            'tgl_presensi' => $tgl_presensi,
            'jam_in' => $jam,
            'foto_in' => $fileName,
            'lokasi_in' => $lokasi,
        ];

        try {
            $simpan = DB::table('presensi')->insert($data);

            if ($simpan) {
                Storage::put($file, $image_base64);
                return response()->json(['status' => 'success']);
            } else {
                return response()->json(['status' => 'fail'], 500);
            }
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
        }
    }

}
