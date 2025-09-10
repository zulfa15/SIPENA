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
          <span class="text-primary fw-bold">07:27:07</span>
        </div>
      </div>
      <div class="col-6">
        <div class="p-3 border rounded shadow-sm">
          <ion-icon name="log-out-outline" size="large"></ion-icon>
          <h6 class="mt-2">Absensi Pulang</h6>
          <span class="text-danger fw-bold">16:00:09</span>
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


@endsection



