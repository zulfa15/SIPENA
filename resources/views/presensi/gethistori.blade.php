@if ($histori->isEmpty())
<div class="alert alert-warning d-flex align-items-center" role="alert" style="border-radius: 12px; background-color:#fff8e1; border:1px solid #ffe082; color:#795548;">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="currentColor" class="me-2 bi bi-info-circle">
        <path d="M8 15A7 7 0 1 0 8 1a7 7 0 0 0 0 14zm0 1A8 8 0 1 1 8 0a8 8 0 0 1 0 16z"/>
        <path d="m8.93 6.588-2.29.287-.082.38.45.083c.294.07.352.176.288.469L7.35 11h1.1l.347-1.632c.066-.293.176-.352.469-.288l.45.083.082-.38-2.29-.287z"/>
        <circle cx="8" cy="4.5" r="1"/>
    </svg>
    <div>
        Data presensi bulan ini belum tersedia ✨
    </div>
</div>
@endif

<ul class="listview">
    @foreach ($histori as $d)
    <li style="padding: 12px 15px; border-bottom: 1px solid #eee;">
        <div style="display: flex; justify-content: space-between; align-items: center;">
            
            {{-- Bagian kiri: Tanggal + Jam --}}
            <div>
                <b>{{ \Carbon\Carbon::parse($d->tgl_presensi)->translatedFormat('l, d F Y') }}</b>
                <br>
                Jam Masuk : {{ $d->jam_in ?? '-' }} <br>
                Jam Pulang : {{ $d->jam_out ?? '-' }}
            </div>

            {{-- Bagian kanan: Status --}}
<div>
    @php
        // DEFAULT
        $status = '';
        $badgeClass = '';

        // JIKA LUAR RADIUS
        if (!empty($d->keterangan)) {
            if ($d->keterangan == 'dldd') {
                $status = 'DLDD';
                $badgeClass = 'background:#6a1b9a;';
            } elseif ($d->keterangan == 'dl') {
                $status = 'DL';
                $badgeClass = 'background:#1565c0;';
            } elseif ($d->keterangan == 'tk') {
                $status = 'TK';
                $badgeClass = 'background:#2e7d32;';
            } else {
                $status = 'Luar Radius';
                $badgeClass = 'background:#616161;';
            }

        // JIKA DALAM RADIUS
        } else {
            if ($d->jam_in > '07:30:00') {
                $status = 'Terlambat';
                $badgeClass = 'background:#fb8c00;';
            } else {
                $status = 'Hadir';
                $badgeClass = 'background:#00796b;';
            }
        }
    @endphp

    <span style="padding:6px 15px;border-radius:20px;color:#fff;font-size:14px;{{ $badgeClass }}">
        {{ $status }}
    </span>
</div>

        </div>
    </li>
    @endforeach
</ul>
