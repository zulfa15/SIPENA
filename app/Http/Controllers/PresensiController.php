<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        // Pastikan nama tabel benar
        $cek = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('nik', $nik)
            ->count();

        return view('presensi.create', compact('cek'));
    }

    public function store(Request $request)
    {
        $nik          = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam          = date("H:i:s");
        $lokasi       = $request->lokasi;
        $image        = $request->image;

        $folderPath = 'public/uploads/absensi/';
        $formatName = $nik . "-" . $tgl_presensi;
        $fileName   = $formatName . ".png";
        $file       = $folderPath . $fileName;

        // Validasi gambar
        if (!$image) {
            return response()->json(['status' => 'error', 'message' => 'Gambar tidak ditemukan'], 400);
        }

        // Decode base64
        if (strpos($image, ';base64,') !== false) {
            $image_parts  = explode(';base64,', $image);
            $image_base64 = base64_decode($image_parts[1]);
        } else {
            return response()->json(['status' => 'error', 'message' => 'Format gambar tidak valid'], 400);
        }

        try {
            DB::beginTransaction();

            // Cek apakah sudah presensi masuk
            $cek = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->count();

            if ($cek > 0) {
                // Update data pulang
                $data_pulang = [
                    'jam_out'    => $jam,
                    'foto_out'   => $fileName,
                    'lokasi_out' => $lokasi,
                ];

                $update = DB::table('presensi')
                    ->where('tgl_presensi', $tgl_presensi)
                    ->where('nik', $nik)
                    ->update($data_pulang);

                if ($update) {
                   Storage::put($file, $image_base64);
                        DB::commit();
                        return response()->json([
                            'status'  => 'success',
                            // kirim pesan pakai | supaya bisa split di JS
                            'message' => 'success|Terimakasih, Hati-hati di jalan!|Out'
                        ]);
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status'  => 'fail',
                            'message' => 'fail|Maaf Gagal Absen, Hubungi Tim IT | Out'
                        ], 500);
                }

            } else {
                // Insert data presensi masuk
                $data_masuk = [
                    'nik'          => $nik,
                    'tgl_presensi' => $tgl_presensi,
                    'jam_in'       => $jam,
                    'foto_in'      => $fileName,
                    'lokasi_in'    => $lokasi,
                ];

                $simpan = DB::table('presensi')->insert($data_masuk);

                if ($simpan) {
                    Storage::put($file, $image_base64);
                        DB::commit();
                        return response()->json([
                            'status'  => 'success',
                            // kirim pesan pakai | juga
                            'message' => 'success|Terimakasih, Selamat Bekerja!|In'
                        ]);
                    } else {
                        DB::rollBack();
                        return response()->json([
                            'status'  => 'fail',
                            'message' => 'fail|Maaf Gagal Absen, Hubungi Tim IT | In'
                        ], 500);
                }
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
