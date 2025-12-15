<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Laporan Presensi Karyawan</title>

    <!-- Normalize -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Paper CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <style>
        /* 1. PENGATURAN HALAMAN CETAK: Gunakan margin minimum (5mm) dan Landscape */
        @page {
            size: A4 landscape;
            margin: 5mm; /* Dikurangi dari 10mm/20mm agar lebih muat */
        }

        /* Khusus untuk cetak (print media) */
        @media print {
            @page {
                size: A4 landscape;
                margin: 5mm;
            }
        }

        /* 2. STYLING UMUM (Diambil dari blok kode kedua) */
        body.A4 {
            background: #f0f0f0;
        }

        .sheet {
            border: 1.5px solid #000;
            padding: 20px;
            font-family: Arial, Helvetica, sans-serif;
        }

        h3 {
            margin: 4px 0;
            font-size: 18px;
            font-weight: bold;
        }

        .tabeldatakaryawan {
            margin-top: 30px;
            font-size: 13px;
        }

        .tabeldatakaryawan td {
            padding: 4px;
        }

        .foto {
            width: 40px;
            height: 30px;
        }

        /* 3. STYLING TABEL REKAP PRESENSI: Minimalisir Ruang */
        .tabelpresensi {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            font-size: 7px; /* Dikecilkan lagi ke 7px untuk data (td) */
            table-layout: fixed; /* Penting untuk mengontrol lebar kolom */
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 1px; /* Dikurangi menjadi 1px */
            background: #d4d4d4;
            text-align: center;
            font-size: 7px; /* Dipertahankan kecil */
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 1px; /* Dikurangi menjadi 1px */
            line-height: 1.0; /* Dikurangi untuk meminimalisir tinggi baris */
            text-align: center;
            word-wrap: break-word;
            height: 15px; /* Tambahkan tinggi minimal untuk menghemat ruang vertikal */
            
            /* Tambahkan font size yang lebih kecil lagi untuk waktu */
            font-size: 7px; 
        }
        
        /* Coba paksa lebar untuk kolom NIK dan Nama Karyawan agar kolom tanggal bisa sempit */
        .tabelpresensi tr td:nth-child(1), .tabelpresensi tr th:nth-child(1) {
            width: 5%; /* NIK */
        }
        .tabelpresensi tr td:nth-child(2), .tabelpresensi tr th:nth-child(2) {
            width: 10%; /* Nama Karyawan */
        }

    </style>
</head>

<body class="A4 landscape">



<section class="sheet">

    <!-- HEADER -->
    <table width="100%" style="border-bottom:2px solid #000; padding-bottom:10px;">
        <tr>
            <td width="100" align="center">
                <img src="{{ asset('assets/img/logo sementara.png') }}" width="90">
            </td>
            <td align="center">
                <h3>REKAP PRESENSI KARYAWAN</h3>
                <h3>PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}</h3>
                <h3>DINAS KOMUNIKASI DAN INFORMATIKA KAB. KARANGANYAR</h3>
                <div style="font-size:12px;color:#555">
                    <i>No. 385 B, Jl. Lawu, Karanganyar, Jawa Tengah 57715</i>
                </div>
            </td>
        </tr>
    </table>
    <table class="tabelpresensi">
        <tr>
            <th rowspan="2">NIK</th>
            <th rowspan="2">Nama Karyawan</th>
            <th colspan="31">Tanggal</th>
            <th rowspan="2">TH</th>
            <th rowspan="2">TT</th>
        </tr>
        <tr>
            <?php
            for ($i = 1; $i <= 31; $i++) {
            ?>
                <th>{{ $i }}</th>
            <?php
            }
            ?>
        </tr> 
        @foreach ($rekap as $d)
        <tr>
            <td>{{ $d->nik }}</td>
            <td>{{ $d->nama_lengkap }}</td>

            <?php
            $totalhadir = 0;
            $totalterlambat = 0;
            for ($i = 1; $i <= 31; $i++) {
                $tgl = "tgl_" . $i;
                $hadir = explode("-",$d->$tgl);
                if(empty($d->$tgl)){
                    $hadir = ['',''];
                    $totalhadir += 0;
                    
                }else{
                    $hadir = explode("-", $d->$tgl);
                    $totalhadir += 1;
                    if($hadir[0]>"07:00:00"){
                        $totalterlambat +=1;
                    }
                }
                ?>
                <td>
                    {{-- BARIS 1: Jam Masuk --}}
                    <span style="color: {{$hadir[0] > "07:30:00" ? "red": "black"}}">
                        {{ $hadir[0] }}
                    </span>
                    <br>
                    {{-- BARIS 2: Jam Keluar (KOREKSI PENTING: MENGGUNAKAN $hadir[1]) --}}
                    <span style="color: {{ $hadir[1] > "16:00:00" ? "red" : "black" }}">
                        {{ $hadir[1] }} {{-- <-- PERBAIKAN DARI $hadir[0] ke $hadir[1] --}}
                    </span>
                
                </td>
        <?php
            }
        ?>
        
      
        
        {{-- KOLOM TOTAL HADIR DAN TERLAMBAT (DI LUAR LOOP 31 HARI) --}}
        <td>{{ $totalhadir }}</td>
        <td>{{ $totalterlambat }}</td>
    </tr>
    @endforeach
    </table>


    <!-- TANDA TANGAN -->
    <table width="100%" style="margin-top:80px;">
        <tr>
            <td></td>
            <td  align="center">
                Karanganyar, {{ date('d-m-Y') }}
            </td>
        </tr>
        <tr>
            <td align="center" height="200">
                <b>HRD Manager</b>
                <br>
                <br>
                <br>
                <br>
                <u>Nama Lengkap HRD</u>
                
            </td>
            <td align="center">
                <b>Direktur</b>
                <br>
                <br>
                <br>
                <br>
                <u>Nama Lengkap Direktur</u>
                
            </td>
        </tr>
    </table>

</section>

</body>
</html>
