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
        @page {
            size: A4;
            margin: 20mm;
        }

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

        .tabelpresensi {
            width: 100%;
            margin-top: 25px;
            border-collapse: collapse;
            font-size: 12px;
        }

        .tabelpresensi th {
            border: 1px solid #000;
            padding: 8px;
            background: #d4d4d4;
            text-align: center;
        }

        .tabelpresensi td {
            border: 1px solid #000;
            padding: 6px;
            line-height: 1.4;
            text-align: center;
        }

        .foto {
            width: 40px;
            height: 30px;
        }
    </style>
</head>

<body class="A4">



<section class="sheet">

    <!-- HEADER -->
    <table width="100%" style="border-bottom:2px solid #000; padding-bottom:10px;">
        <tr>
            <td width="100" align="center">
                <img src="{{ asset('assets/img/logo sementara.png') }}" width="90">
            </td>
            <td align="center">
                <h3>LAPORAN PRESENSI KARYAWAN</h3>
                <h3>PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}</h3>
                <h3>DINAS KOMUNIKASI DAN INFORMATIKA KAB. KARANGANYAR</h3>
                <div style="font-size:12px;color:#555">
                    <i>No. 385 B, Jl. Lawu, Karanganyar, Jawa Tengah 57715</i>
                </div>
            </td>
        </tr>
    </table>

    <!-- DATA KARYAWAN -->
    <table class="tabeldatakaryawan">
        <tr>
            <td rowspan="4" width="120">
                @php $path = Storage::url('uploads/karyawan/'.$karyawan->foto); @endphp
                <img src="{{ url($path) }}" width="110" height="130">
            </td>
            <td width="150">NIK</td>
            <td width="10">:</td>
            <td>{{ $karyawan->nik }}</td>
        </tr>
        <tr>
            <td>Nama Karyawan</td>
            <td>:</td>
            <td>{{ $karyawan->nama_lengkap }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $karyawan->jabatan }}</td>
        </tr>
        <tr>
            <td>No. HP</td>
            <td>:</td>
            <td>{{ $karyawan->no_hp }}</td>
        </tr>
    </table>

    <!-- TABEL PRESENSI -->
    <table class="tabelpresensi">
        <tr>
            <th>No</th>
            <th>Tanggal</th>
            <th>Jam Masuk</th>
            <th>Foto</th>
            <th>Jam Pulang</th>
            <th>Foto</th>
            <th>Keterangan</th>
            <th>Jml Jam</th>
        </tr>

        @foreach ($presensi as $d)
        @php
            $path_in  = Storage::url('uploads/absensi/'.$d->foto_in);
            $path_out = Storage::url('uploads/absensi/'.$d->foto_out);
            $jamterlambat = selisih('07:30:00', $d->jam_in);
            $jmljamkerja = selisih($d->jam_in, $d->jam_out);
        @endphp
        <tr>
            <td>{{ $loop->iteration }}</td>
            <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
            <td>{{ $d->jam_in }}</td>
            <td><img src="{{ url($path_in) }}" class="foto"></td>
            <td>{{ is_null($d->jam_out) ? 'Belum Absen' : $d->jam_out }}</td>
            <td>
                @if (!is_null($d->jam_out))
                    <img src="{{ url($path_out) }}" class="foto">
                @else
                    -
                @endif
            </td>
            <td>
                @if ($d->jam_in > '07:30:00')
                    Terlambat {{ $jamterlambat }}
                @else
                    Tepat Waktu
                @endif
            </td>
            <td>{{ $jmljamkerja }}</td>
        </tr>
        @endforeach
    </table>

    <!-- TANDA TANGAN -->
    <table width="100%" style="margin-top:80px;">
        <tr>
            <td colspan="2" align="right">
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
