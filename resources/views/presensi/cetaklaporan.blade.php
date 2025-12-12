<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>A4</title>

    <!-- Normalize or reset CSS with your favorite library -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/7.0.0/normalize.min.css">

    <!-- Load paper.css for happy printing -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/paper-css/0.4.1/paper.css">

    <!-- Set page size here: A5, A4 or A3 -->
    <!-- Set also "landscape" if you need -->
    <style>
    @page { 
        size: A4 
        }
        h3{
            font-family:Arial, Helvetica, sans-serif;
            font-size: 18px;
            font-weight: bold;
        }
        .tabeldatakaryawan{
            margin-top: 40px;

        }

        .tabledatakaryawan td {
            padding: 5px;
        }

        .tabelpresensi{
            width: 100%;
            margin-top: 20px;
            font-family:Arial, Helvetica, sans-serif;
            border-collapse: collapse;
        }

        .tabelpresensi tr th{
            border: 1px solid #000;
            padding: 8px;
            background: #d4d4d4ff;
        }
         .tabelpresensi tr td{
            border: 1px solid #000;
            padding: 8px;
            font-size: 12px;
        }

        .foto{
            width: 40px ;
            height: 30px;
        }
    </style>
</head>

<!-- Set "A5", "A4" or "A3" for class name -->
<!-- Set also "landscape" if you need -->
<body class="A4">

    <!-- Each sheet element should have the class "sheet" -->
    <!-- "padding" is optional: you can set 10, 15, 20 or 25 -->
    <section class="sheet padding-10">

        <!-- Write HTML just like a web page -->
        <table style="width: 100%; border-bottom: 2px solid #000; padding-bottom: 10px; font-family: Arial, Helvetica, sans-serif;">
            <tr>
                <td style="width: 100px; text-align: center; vertical-align: top;">
                    <img src="{{ asset('assets/img/logo sementara.png') }}" 
                        alt="Logo" 
                        width="90"
                        style="display: block; margin: 0 auto;">
                </td>

                <td style="text-align: center;">
                    <h3 style="margin: 5px 0 0 0; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">
                        LAPORAN PRESENSI KARYAWAN
                    </h3>

                    <h3 style="margin: 5px 0 0 0; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">
                        PERIODE {{ strtoupper($namabulan[$bulan]) }} {{ $tahun }}
                    </h3>

                    <h3 style="margin: 5px 0 10px 0; font-size: 18px; font-family: Arial, Helvetica, sans-serif;">
                        DINAS KOMUNIKASI DAN INFORMATIKA KAB. KARANGANYAR
                    </h3>

                    <div style="font-size: 12px; color: #555; font-family: Arial, Helvetica, sans-serif;">
                        <i>No. 385 B, Jl. Lawu, Badran Asri, Cangakan, Karanganyar, Jawa Tengah 57715</i>
                    </div>
                </td>
            </tr>
        </table>
        <table class="tabeldatakaryawan" >
            <tr>
                <td rowspan="5">
                    @php
                        $path = Storage::url('uploads/karyawan/'.$karyawan->foto);
                    @endphp
                    <img src="{{ url($path) }}" alt="" width="100px" height="120px">
                </td>
            </tr>
            <tr>
                <td>
                    <td>NIK</td>
                    <td>:</td>
                    <td>{{ $karyawan->nik }}</td>
                </td>
            </tr>
            <tr>    
                <td>
                    <td>Nama Karyawan</td>
                    <td>:</td>
                    <td>{{ $karyawan->nama_lengkap }}</td>
                </td>
            </tr>
            <tr>
                <td>
                    <td>Jabatan</td>
                    <td>:</td>
                    <td>{{ $karyawan->jabatan }}</td>
                </td>
            </tr>
            <tr>
                <td>
                    <td>No. HP</td>
                    <td>:</td>
                    <td>{{ $karyawan->no_hp }}</td>
                </td>
            </tr>
        </table>
        <table class="tabelpresensi">
            <tr>
                <th>No.</th>
                <th>Tanggal</th>
                <th>Jam Masuk</th>
                <th>Foto</th>
                <th>Jam Pulang</th>
                <th>Foto</th>
                <th>Keterangan</th>
            </tr>
            <tr>
                @foreach ($presensi as $d)
                @php
                    $path_in = Storage::url('uploads/absensi/'.$d->foto_in);
                    $path_out = Storage::url('uploads/absensi/'.$d->foto_out);

                @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date("d-m-Y",strtotime($d->tgl_presensi)) }}</td>
                        <td>{{ $d->jam_in }}</td>
                        <td><img src="{{ url($path_in) }}" alt="" class="foto"></td>
                        <td>{{ $d->jam_out = null ? $d->jam_out : 'Belum Absen' }}</td>
                        <td><img src="{{ url($path_out) }}" alt="" class="foto"></td>
                        <td>
                            @if ($d->jam_in > '07:30:00')
                                Terlambat
                                @else
                                Tepat Waktu
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tr>
        </table>



    </section>

</body>
</html>
