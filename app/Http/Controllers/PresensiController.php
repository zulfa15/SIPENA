<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;


class PresensiController extends Controller
{
    public function create()
    {
        $hariini = date("Y-m-d");
        $nik = Auth::guard('karyawan')->user()->nik;

        $cek = DB::table('presensi')
            ->where('tgl_presensi', $hariini)
            ->where('nik', $nik)
            ->count();

        $konfigurasi = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

        return view('presensi.create', [
            'cek'           => $cek,
            'lokasi_kantor' => $konfigurasi->lokasi_kantor, // "-7.xxx,110.xxx"
            'radius'        => $konfigurasi->radius         // angka
        ]);
    }


    public function store(Request $request)
    {
        $nik          = Auth::guard('karyawan')->user()->nik;
        $tgl_presensi = date("Y-m-d");
        $jam          = date("H:i:s");

        // Koordinat kantor
        $latitudekantor  = -7.595928021196008; 
        $longitudekantor =  110.94004006560145;

        // Lokasi user
        $lokasi     = $request->lokasi; // contoh: "-7.59,110.93"
        $lokasiuser = explode(",", $lokasi);

        $latitudeuser  = isset($lokasiuser[0]) ? $lokasiuser[0] : 0;
        $longitudeuser = isset($lokasiuser[1]) ? $lokasiuser[1] : 0;

        // Hitung jarak
        $jarak  = $this->distance($latitudekantor, $longitudekantor, $latitudeuser, $longitudeuser);
        $radius = round($jarak['meters']); // jarak meteran

     // Cek apakah sudah presensi masuk
        $cek = DB::table('presensi')
            ->where('tgl_presensi', $tgl_presensi)
            ->where('nik', $nik)->count();
    
        if($cek > 0){
            $ket = "out";
        }else{
            $ket = "in";
        }

        $image        = $request->image;
        $folderPath   = 'public/uploads/absensi/';
        $formatName   = $nik . "-" . $tgl_presensi. "-". $ket;
        $fileName     = $formatName . ".png";
        $file         = $folderPath . $fileName;

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

            // Jika berada di luar radius
          if ($radius > 1000) { // radius maksimal 40 m
            DB::rollBack();
            return response()->json([
                'status'  => 'fail',
                'message' => 'fail|Maaf Anda Berada Diluar Radius Kantor|Out'
            ]);
        }


           

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
                        'message' => 'success|Terimakasih, Hati-hati di jalan!|Out'
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'fail',
                        'message' => 'fail|Maaf Gagal Absen, Hubungi Tim IT|Out'
                    ], 500);
                }
            } else 
            {
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
                        'message' => 'success|Terimakasih, Selamat Bekerja!|In'
                    ]);
                } else {
                    DB::rollBack();
                    return response()->json([
                        'status'  => 'fail',
                        'message' => 'fail|Maaf Gagal Absen, Hubungi Tim IT|In'
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

    // Menghitung jarak dalam meter
    function distance($lat1, $lon1, $lat2, $lon2)
    {
        $theta = $lon1 - $lon2;
        $dist = sin(deg2rad($lat1)) * sin(deg2rad($lat2)) +
                cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * cos(deg2rad($theta));
        $dist = acos(min(max($dist, -1), 1)); // clamp supaya tidak error
        $dist = rad2deg($dist);
        $miles = $dist * 60 * 1.1515;
        $kilometers = $miles * 1.609344;
        $meters = $kilometers * 1000;
        return compact('meters');
    }

    public function editprofile()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $karyawan = DB::table('karyawan')->where('nik',$nik)->first();
        
        return view('presensi.editprofile',compact('karyawan'));

    }

    public function updateprofile(Request $request){
        $nik = Auth::guard('karyawan')->user()->nik;
        $nama_lengkap = $request->nama_lengkap;
        $no_hp = $request->no_hp;
        $password = Hash::make($request->password);
        $karyawan = DB::table('karyawan')->where('nik',$nik)->first();
        if($request->hasFile('foto')){
            $foto = $nik.".".$request->file('foto')->getClientOriginalExtension();
        }else{
            $foto = $karyawan->foto;
        }

        if(empty($request->password)){
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'foto' => $foto
            ];
        } else {
            $data = [
                'nama_lengkap' => $nama_lengkap,
                'no_hp' => $no_hp,
                'password' => $password
            ];
        }

        $update = DB::table('karyawan')->where('nik',$nik)->update($data);
        if($update){
            if($request->hasFile('foto')){
                $folderPath = "public/uploads/karyawan";
                $request->file('foto')->storeAs($folderPath, $foto);
            }
            return Redirect::back()->with(['success'=>'Data Berhasil di Update']);

        }else {
            return Redirect::back()->with(['error'=>'Data Gagal di Update']);
        }
        
    }

    public function histori(){
        $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
        return view('presensi.histori',compact('namabulan'));

    }

    public function gethistori(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $nik = Auth::guard('karyawan')->user()->nik;

        $histori = DB::table('presensi')
        ->whereRAw('MONTH(tgl_presensi)="'.$bulan.'"')
        ->whereRAw('YEAR(tgl_presensi)="'.$tahun.'"')
        ->where('nik',$nik)
        ->orderBy('tgl_presensi')
        ->get();

        return view('presensi.gethistori',compact('histori'));

    }


    public function cuti()
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $datacuti = DB::table('pengajuan_cuti')
            ->where('nik', $nik)
            ->get();

        // tampilkan daftar cuti
        return view('presensi.cuti', compact('datacuti'));
    }

    public function buatcuti()
    {
        // tampilkan form buat cuti
        return view('presensi.buatcuti');
    }


     public function storecuti(Request $request)
    {
        $nik = Auth::guard('karyawan')->user()->nik;
        $tgl_cuti = $request->tgl_cuti;
        $status = $request->status;
        $keterangan = $request->keterangan;

        $data = [
            'nik' => $nik,
            'tgl_cuti' => $tgl_cuti,
            'status' => $status,
            'keterangan' => $keterangan,
        ];

        $simpan = DB::table('pengajuan_cuti')->insert($data);

        if($simpan){
            return redirect('/presensi/cuti')->with(['success'=>'Data Berhasil Disimpan']);
        }else {
            return redirect('/presensi/cuti')->with(['error'=>'Data Gagal Disimpan']);
        }
    }

private function hitungalpa($nik, $bulan, $tahun)
{
    // Ambil tanggal hari ini
    $today = now();

    // Tentukan rentang tanggal awal bulan sampai hari ini
    $startDate = Carbon::createFromDate($tahun, $bulan, 1);
    $endDate = $today;

    // Hitung total hari kerja dari tanggal 1 sampai hari ini (Senin–Jumat)
    $workdays = 0;
    $current = $startDate->copy();
    while ($current->lte($endDate)) {
        if ($current->isWeekday()) {
            $workdays++;
        }
        $current->addDay();
    }

    // Hitung total hari yang sudah absen (hadir)
    // PERBAIKAN: Menggunakan DB::table('presensi') untuk menentukan tabel
    $hadir = DB::table('presensi')->where('nik', $nik) 
    ->whereMonth('tgl_presensi', $bulan)
    ->whereYear('tgl_presensi', $tahun)
    ->whereDate('tgl_presensi', '<=', Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth())

    ->count();


    // Hitung alpa (hari kerja - hadir)
    $alpa = max($workdays - $hadir, 0);

    return $alpa;
}

public function monitoring(){
    return view('presensi.monitoring');
}

public function getpresensi(Request $request){
    $tanggal = $request->tanggal;
    $presensi = DB::table('presensi')
    ->select('presensi.*','nama_lengkap',)
    ->join('karyawan','presensi.nik','=','karyawan.nik')
    ->where('tgl_presensi',$tanggal)
    ->get();

    return view('presensi.getpresensi', compact('presensi'));
}

public function tampilkanpeta(Request $request){
    $id = $request->id;
    $presensi = DB::table('presensi')->where('id',$id)
    ->join('karyawan','presensi.nik', '=', 'karyawan.nik')
    ->first();
    return view('presensi.showmap', compact('presensi'));
}

public function laporan(){
    $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    $karyawan = DB::table('karyawan')->orderBy('nama_lengkap')->get();
    return view('presensi.laporan', compact('namabulan','karyawan'));
}

public function cetaklaporan(Request $request){
    $nik = $request->nik;
    $bulan = $request->bulan;
    $tahun = $request->tahun;
    $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    $karyawan = DB::table('karyawan')->where('nik', $nik)
    ->first();
    $presensi = DB::table('presensi')
    ->where('nik',$nik)
    ->whereRaw('MONTH(tgl_presensi)="'.$bulan.'"')
    ->whereRaw('YEAR(tgl_presensi)="'.$tahun.'"')
    ->orderBy('tgl_presensi', )
    ->get();
    return view('presensi.cetaklaporan', compact('bulan','tahun','namabulan','karyawan', 'presensi'));
}

public function rekap(){
    $namabulan = ["","Januari","Februari","Maret","April","Mei","Juni","Juli","Agustus","September","Oktober","November","Desember"];
    return view('presensi.rekap', compact('namabulan'));
}



public function cetakrekap(Request $request)
{
    $bulan = $request->bulan;
    $tahun = $request->tahun;

    $namabulan = [
        "", "Januari", "Februari", "Maret", "April", "Mei",
        "Juni", "Juli", "Agustus", "September",
        "Oktober", "November", "Desember"
    ];

    $rekap = DB::table('presensi as p')
        ->join('karyawan as k', 'p.nik', '=', 'k.nik')
        ->selectRaw('
            p.nik,
            k.nama_lengkap,

            MAX(IF(DAY(p.tgl_presensi)=1,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_1,
            MAX(IF(DAY(p.tgl_presensi)=2,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_2,
            MAX(IF(DAY(p.tgl_presensi)=3,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_3,
            MAX(IF(DAY(p.tgl_presensi)=4,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_4,
            MAX(IF(DAY(p.tgl_presensi)=5,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_5,
            MAX(IF(DAY(p.tgl_presensi)=6,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_6,
            MAX(IF(DAY(p.tgl_presensi)=7,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_7,
            MAX(IF(DAY(p.tgl_presensi)=8,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_8,
            MAX(IF(DAY(p.tgl_presensi)=9,  CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_9,
            MAX(IF(DAY(p.tgl_presensi)=10, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_10,
            MAX(IF(DAY(p.tgl_presensi)=11, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_11,
            MAX(IF(DAY(p.tgl_presensi)=12, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_12,
            MAX(IF(DAY(p.tgl_presensi)=13, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_13,
            MAX(IF(DAY(p.tgl_presensi)=14, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_14,
            MAX(IF(DAY(p.tgl_presensi)=15, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_15,
            MAX(IF(DAY(p.tgl_presensi)=16, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_16,
            MAX(IF(DAY(p.tgl_presensi)=17, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_17,
            MAX(IF(DAY(p.tgl_presensi)=18, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_18,
            MAX(IF(DAY(p.tgl_presensi)=19, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_19,
            MAX(IF(DAY(p.tgl_presensi)=20, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_20,
            MAX(IF(DAY(p.tgl_presensi)=21, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_21,
            MAX(IF(DAY(p.tgl_presensi)=22, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_22,
            MAX(IF(DAY(p.tgl_presensi)=23, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_23,
            MAX(IF(DAY(p.tgl_presensi)=24, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_24,
            MAX(IF(DAY(p.tgl_presensi)=25, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_25,
            MAX(IF(DAY(p.tgl_presensi)=26, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_26,
            MAX(IF(DAY(p.tgl_presensi)=27, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_27,
            MAX(IF(DAY(p.tgl_presensi)=28, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_28,
            MAX(IF(DAY(p.tgl_presensi)=29, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_29,
            MAX(IF(DAY(p.tgl_presensi)=30, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_30,
            MAX(IF(DAY(p.tgl_presensi)=31, CONCAT(p.jam_in,"-",IFNULL(p.jam_out,"00:00")), "")) AS tgl_31
        ')
        ->whereMonth('p.tgl_presensi', $bulan)
        ->whereYear('p.tgl_presensi', $tahun)
        ->groupBy('p.nik', 'k.nama_lengkap')
        ->get();

    return view('presensi.cetakrekap', compact(
        'bulan',
        'tahun',
        'namabulan',
        'rekap'
    ));
}




}