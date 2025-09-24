@extends('layouts.presensi')
@section(('content'))
<!-- Header -->
<div class="header p-3 text-white d-flex justify-content-between align-items-center rounded-bottom" style="background:#1B7D7E;">
  <div>
    <h5 class="mb-1">Selamat Datang di SIPENA</h5>
    <small>Jangan lupa absen hari ini!</small>
  </div>
  <div>
    <ion-icon name="notifications-outline" size="large"></ion-icon>
  </div>
</div>

<!-- User Card -->
<div class="card mt-3 shadow-sm">
  <div class="card-body d-flex align-items-center" style="background:#FAC58F; border-radius:12px;">
    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="rounded-circle me-3" width="60" height="60">
    <div>
      <h6 class="mb-0 text-dark">Putri Ariani</h6>
      <small class="text-dark">Mahasiswa Magang</small>
    </div>
  </div>
</div>


<!-- Absensi Card -->
<div class="card mt-3 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <span class="text-muted">Rabu, 3 September 2025</span>
      <small class="text-muted">Jam Reguler : 07.30 - 16.00</small>
    </div>
    <div class="row text-center">
      <div class="col-6">
        <div class="p-3 border rounded shadow-sm">
          <ion-icon name="log-in-outline" size="large"></ion-icon>
          <h6 class="mt-2">Absensi Masuk</h6>
          <span>{{ $presensihariini->jam_in ?? '-- : -- : --' }}</span>

        </div>
      </div>
      <div class="col-6">
        <div class="p-3 border rounded shadow-sm">
          <ion-icon name="log-out-outline" size="large"></ion-icon>
          <h6 class="mt-2">Absensi Pulang</h6>
          <span>
              {{ $presensihariini && $presensihariini->jam_out
                  ? date('H:i:s', strtotime($presensihariini->jam_out))
                  : '-- : -- : --' }}
          </span>

        </div>
      </div>
    </div>
  </div>
</div>

<!-- Ringkasan Kehadiran -->
<div class="card mt-3 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between mb-2">
      <h6>Ringkasan Kehadiran Bulan Ini</h6>
      <a href="#" class="text-primary small">Lihat Selengkapnya</a>
    </div>
    <div class="row">
      <div class="col-6">
        <ul class="list-unstyled">
          <li><span class="badge me-2" style="background:#1B7D7E;">19</span> Hadir</li>
          <li><span class="badge me-2" style="background:#F7A129;">7</span> Terlambat</li>
          <li><span class="badge me-2" style="background:#B16700;">1</span> Cuti</li>
          <li><span class="badge me-2" style="background:#910101;">2</span> Tidak Hadir</li>
        </ul>
      </div>
      <div class="col-6 d-flex align-items-center justify-content-center">
        <canvas id="chartKehadiran" width="120" height="120"></canvas>
      </div>
    </div>
  </div>
</div>

<div class="tab-content mt-2" style="margin-bottom:100px;">
  <div class="tab-pane fade show active" id="home" role="tabpanel">
    <ul class="listview image-listview">
        @foreach ($historibulanini as $d )
        @php
          $path = Storage::url('/uploads/absensi/'.$d->foto_in);                
        @endphp
       <li>
            <div class="item">
                <div class="icon-box bg-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"><path d="M7.429 3.362c3.97-2.698 9.707-1.238 11.801 3.056m-8.373 15.506C15.584 22.582 20 18.895 20 14.21v-3.877M7.429 20.606C5.356 19.198 4 16.858 4 14.21V9.758c0-1.185.271-2.308.757-3.314"/><path d="M16 13.8c0 2.32-1.79 4.2-4 4.2s-4-1.88-4-4.2v-3.6c0-.644.138-1.254.385-1.8M12 6c2.21 0 4 1.88 4 4.2m-4 .3v3"/></g></svg>
            
                </div>
                <div class="in">
                    {{-- Tanggal presensi --}}
                    <div>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</div>
                    {{-- Badge jumlah (bisa diganti dinamis) --}}
                    <span class="badge badge-success">{{ $d->jam_in }}</span>
                    <span class="badge badge-danger">{{ $d->jam_out }}</span>
                </div>
            </div>
        </li>

        @endforeach
        
    </ul>
  </div>
</div>


@endsection



