<?php

namespace App\Http\Controllers;

use App\Models\Pengajuanizin;
use Illuminate\Http\Request;
use Illuminate\Queue\Jobs\RedisJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;


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

    // ================= KONFIGURASI LOKASI =================
    $konfigurasi = DB::table('konfigurasi_lokasi')->where('id', 1)->first();

    if (!$konfigurasi) {
        return response()->json([
            'status' => 'error',
            'message' => 'Konfigurasi lokasi belum diatur'
        ], 500);
    }

    // lokasi_kantor: "-7.xxx,110.xxx"
    $lokasi_kantor = explode(',', $konfigurasi->lokasi_kantor);
    $latitudekantor  = $lokasi_kantor[0];
    $longitudekantor = $lokasi_kantor[1];
    $radius_kantor   = (int) $konfigurasi->radius; // meter

    // ================= LOKASI USER =================
    if (!$request->lokasi) {
        return response()->json([
            'status' => 'error',
            'message' => 'Lokasi tidak ditemukan'
        ], 400);
    }

    $lokasiuser = explode(',', $request->lokasi);
    $latitudeuser  = $lokasiuser[0];
    $longitudeuser = $lokasiuser[1];

    // ================= HITUNG JARAK =================
    $jarak = $this->distance(
        $latitudekantor,
        $longitudekantor,
        $latitudeuser,
        $longitudeuser
    );

    $jarak_meter = round($jarak['meters']);

    // ================= CEK ABSENSI =================
    $cek = DB::table('presensi')
        ->where('tgl_presensi', $tgl_presensi)
        ->where('nik', $nik)
        ->count();

    $ket = $cek > 0 ? 'out' : 'in';

    // ================= VALIDASI FOTO =================
    if (!$request->image || strpos($request->image, ';base64,') === false) {
        return response()->json([
            'status' => 'error',
            'message' => 'Foto tidak valid'
        ], 400);
    }

    $image_parts  = explode(';base64,', $request->image);
    $image_base64 = base64_decode($image_parts[1]);

    $folderPath = 'public/uploads/absensi/';
    $fileName   = $nik . '-' . $tgl_presensi . '-' . $ket . '.png';
    $file       = $folderPath . $fileName;

    try {
        DB::beginTransaction();

        // ================= VALIDASI RADIUS =================
        if ($jarak_meter > $radius_kantor && $cek == 0) {
            // LUAR RADIUS + BELUM ABSEN MASUK
            DB::rollBack();
            return response()->json([
                'status' => 'outside',
                'message' => 'outside|Anda berada di luar radius|showForm'
            ]);
        }
        // 👉 jika $cek > 0 (absen pulang) → LOLOS


        // ================= ABSEN =================
        if ($cek > 0) {
            // PULANG
            $update = DB::table('presensi')
                ->where('tgl_presensi', $tgl_presensi)
                ->where('nik', $nik)
                ->update([
                    'jam_out'    => $jam,
                    'foto_out'   => $fileName,
                    'lokasi_out' => $request->lokasi
                ]);

            if (!$update) {
                DB::rollBack();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'fail|Gagal Absen Pulang|Out'
                ], 500);
            }

        } else {
            // MASUK
            $insert = DB::table('presensi')->insert([
                'nik'          => $nik,
                'tgl_presensi' => $tgl_presensi,
                'jam_in'       => $jam,
                'foto_in'      => $fileName,
                'lokasi_in'    => $request->lokasi
            ]);

            if (!$insert) {
                DB::rollBack();
                return response()->json([
                    'status' => 'fail',
                    'message' => 'fail|Gagal Absen Masuk|In'
                ], 500);
            }
        }

        Storage::put($file, $image_base64);
        DB::commit();

        return response()->json([
            'status'  => 'success',
            'message' => $cek > 0
                ? 'success|Terimakasih, Hati-hati di jalan!|Out'
                : 'success|Terimakasih, Selamat Bekerja!|In'
        ]);

    } catch (\Exception $e) {
        DB::rollBack();
        return response()->json([
            'status' => 'error',
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


 public function izinsakit(Request $request)
    {
        $query = DB::table('pengajuan_cuti')
    ->leftJoin(
        'karyawan',
        DB::raw('pengajuan_cuti.nik COLLATE utf8mb4_unicode_ci'),
        '=',
        DB::raw('karyawan.nik COLLATE utf8mb4_unicode_ci')
    )

            ->whereIn('pengajuan_cuti.status', ['i', 's'])
            ->select(
                'pengajuan_cuti.id',
                'pengajuan_cuti.tgl_cuti',
                'pengajuan_cuti.nik',
                'pengajuan_cuti.status',
                'pengajuan_cuti.keterangan',
                'pengajuan_cuti.status_approved',
                'karyawan.nama_lengkap',
                'karyawan.jabatan'
            );

        // ================= FILTER =================
        // Filter tanggal
        if ($request->filled('dari') && $request->filled('sampai')) {
            $query->whereBetween('pengajuan_cuti.tgl_cuti', [
                $request->dari,
                $request->sampai
            ]);
        }

        // Filter NIK
        if ($request->filled('nik')) {
            $query->where('pengajuan_cuti.nik', 'like', '%' . $request->nik . '%');
        }

        // Filter nama karyawan
        if ($request->filled('nama_karyawan')) {
            $query->where('karyawan.nama_lengkap', 'like', '%' . $request->nama_karyawan . '%');
        }

        // Filter status
       if ($request->status_approved !== null && $request->status_approved !== '') {
            $status = (int) $request->status_approved;
            if ($status === 0) {
                $query->where(function($q) {
                    $q->where('pengajuan_cuti.status_approved', 0)
                    ->orWhereNull('pengajuan_cuti.status_approved');
                });
            } else {
                $query->where('pengajuan_cuti.status_approved', $status);
            }
        }

 



        // ================= EKSEKUSI TERAKHIR =================
        $izinsakit = $query->orderByDesc('pengajuan_cuti.id')->paginate(5);
        $izinsakit->appends($request->all());

        return view('presensi.izinsakit', compact('izinsakit'));
    }

    // ================= APPROVE IZIN/SAKIT =================
    public function approveizinsakit(Request $request)
    {
        Pengajuanizin::where('id', $request->id_izinsakit_form)
            ->update([
                'status_approved' => (int) $request->status_approved // pastikan integer
            ]);

        return back()->with('success', 'Status berhasil diupdate');
    }

    // ================= BATALKAN IZIN/SAKIT =================
    public function batalkanizinsakit(Request $request)
    {
        Pengajuanizin::where('id', $request->id_izinsakit)
            ->update([
                'status_approved' => 0 // pastikan integer 0
            ]);

        return back()->with('success', 'Status berhasil dibatalkan');
    }


    public function storeLuarRadius(Request $request)
    {
        $request->validate([
            'jenis_dinas' => 'required|in:DLDD,DL,TK',
            'keterangan'  => 'required',
            'lokasi'      => 'required',
            'foto'        => 'required'
        ]);
    
        $nik     = auth()->guard('karyawan')->user()->nik;
        $tanggal = date('Y-m-d');
        $jam     = date('H:i:s');
    
        /* ================= FOTO ================= */
        $fotoBase64 = $request->foto;
    
        // bersihkan prefix base64 (AMAN untuk jpeg/png)
        if (strpos($fotoBase64, ';base64,') !== false) {
            $fotoBase64 = explode(';base64,', $fotoBase64)[1];
        }
    
        $fotoBase64 = base64_decode($fotoBase64);
    
        $jenis = $request->jenis_dinas; // DL / DLDD / TK

        $namaFoto = $jenis . '_' . $nik . '_' . time() . '.jpg';
        
    
        /* ================= FOLDER (INI KUNCI ERROR KAMU) ================= */
        $folderPath = public_path('uploads/absensi');
    
        // kalau folder belum ada → buat
        if (!file_exists($folderPath)) {
            mkdir($folderPath, 0777, true);
        }
    
        $path = $folderPath . '/' . $namaFoto;
    
        file_put_contents($path, $fotoBase64);
    
        /* ================= INSERT PRESENSI ================= */
        DB::table('presensi')->insert([
            'nik'             => $nik,
            'tgl_presensi'    => $tanggal,
            'jam_in'          => $jam,
            'foto_in'         => $namaFoto,
            'lokasi_in'       => $request->lokasi,
            'jenis_dinas'     => $request->jenis_dinas,
            'keterangan'      => $request->keterangan,
            'status_approved' => 0,
            'created_at'      => now()
        ]);
    
        return response()->json([
            'status'  => 'success',
            'message' => 'Pengajuan dinas luar berhasil'
        ]);

        
    }
    
    




/*
|--------------------------------------------------------------------------
| ADMIN - LIST DINAS LUAR
|--------------------------------------------------------------------------
*/
public function dinasLuar()
{
    $data = DB::table('dinas_luar')
        ->join('karyawan','dinas_luar.nik','=','karyawan.nik')
        ->select('dinas_luar.*','karyawan.nama_lengkap')
        ->orderByDesc('dinas_luar.id')
        ->get();

    return view('admin.dinas_luar', compact('data'));
}



/*
|--------------------------------------------------------------------------
| ADMIN - APPROVE DINAS LUAR
|--------------------------------------------------------------------------
*/
public function approveDinasLuar(Request $request)
{
    DB::table('dinas_luar')
        ->where('id', $request->id)
        ->update([
            'status_approved' => $request->status,
            'approved_at'     => now()
        ]);

    if ($request->status == 1) {
        $data = DB::table('dinas_luar')->where('id', $request->id)->first();

        DB::table('presensi')->insert([
            'nik' => $data->nik,
            'tgl_presensi' => $data->tanggal,
            'jam_in' => date('H:i:s'),
            'status' => 'DL',
            'lokasi_in' => $data->lokasi
        ]);
    }

    return back()->with('success','Berhasil memperbarui status');
}






}