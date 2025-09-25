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
<div class="card mt-3 mb-4 shadow-sm">
  <div class="card-body d-flex align-items-center" style="background:#FAC58F; border-radius:12px;">
    <img src="assets/img/sample/avatar/avatar1.jpg" alt="avatar" class="rounded-circle me-3" width="60" height="60">
    <div>
      <h6 class="mb-0 text-dark">{{ Auth::guard('karyawan')->user()->nama_lengkap }}</h6>
      <small class="text-dark">{{ Auth::guard('karyawan')->user()->jabatan }}</small>
    </div>
  </div>
</div>


<!-- Absensi Card -->
<h3 style="font-size: 0.9rem; font-weight:500">Presensi Hari Ini</h3>
<div class="card mt-1 mb-4 shadow-sm">
  <div class="card-body">
    <div class="d-flex justify-content-between">
      <span class="text-muted">Rabu, 3 September 2025</span>
      <small class="text-muted">Jam Reguler : 07.30 - 16.00</small>
    </div>
    <div class="row text-center">
      <div class="col-6">
        <div class="p-1 border rounded shadow-sm">
          <ion-icon name="log-in-outline" size="large"></ion-icon>
          <h6 class="mt-1">Absensi Masuk</h6>
          <span>{{ $presensihariini->jam_in ?? '-- : -- : --' }}</span>

        </div>
      </div>
      <div class="col-6">
        <div class="p-1 border rounded shadow-sm">
          <ion-icon name="log-out-outline" size="large"></ion-icon>
          <h6 class="mt-1">Absensi Pulang</h6>
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


<div id="rekappresensi mt-3 mb-4">
  <h3 style="font-size: 0.9rem; font-weight:500">Rekap Presensi Bulan {{ $namabulan [$bulanini]}} Tahun {{ $tahunini }}</h3>
  <div class="row">
    <div class="col-3">
      <div class="card">
        <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
            <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" class="text-primary" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><circle cx="18" cy="16" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="m16.667 16l.833 1l1.833-1.889"/><path d="M15 13.327A13.6 13.6 0 0 0 12 13c-4.418 0-8 2.015-8 4.5S4 22 12 22c5.687 0 7.331-1.018 7.807-2.5"/></g></svg>
            <br>
            <span class="badge fw-bold" style="font-size: 0.8rem; font-weight:900">{{ $rekappresensi->jmlhadir }} Hari</span>
            <br>
            <span style="font-size: 0.7rem; font-weight:500" >Hadir</span>
          </div>
      </div>
    </div>
    <div class="col-3">
      <div class="card">
        <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
          <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" class="text-success" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-width="1.5" d="M3 14v-4c0-3.771 0-5.657 1.172-6.828S7.229 2 11 2h2c3.771 0 5.657 0 6.828 1.172c.654.653.943 1.528 1.07 2.828M21 10v4c0 3.771 0 5.657-1.172 6.828S16.771 22 13 22h-2c-3.771 0-5.657 0-6.828-1.172c-.654-.653-.943-1.528-1.07-2.828M8 14h5m-5-4h1m7 0h-4"/></svg>        
          <br>
            <span class="badge fw-bold" style="font-size: 0.8rem; font-weight:900">0 Hari</span>
          <br>
            <span style="font-size: 0.7rem; font-weight:500" >Cuti</span>
        </div>
      </div>
    </div>
    <div class="col-3">
      <div class="card">
        <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
          <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" class="text-warning" viewBox="0 0 24 24"><path fill="currentColor" d="M15.098 12.634L13 11.423V7a1 1 0 0 0-2 0v5a1 1 0 0 0 .5.866l2.598 1.5a1 1 0 1 0 1-1.732M12 2a10 10 0 1 0 10 10A10.01 10.01 0 0 0 12 2m0 18a8 8 0 1 1 8-8a8.01 8.01 0 0 1-8 8"/></svg>       
          <br>
            <span class="badge fw-bold" style="font-size: 0.8rem; font-weight:900">{{ $rekappresensi->jmlterlambat }} Hari</span>
          <br>
            <span style="font-size: 0.7rem; font-weight:500" >Terlambat</span>
        </div>
      </div>
    </div>
    <div class="col-3">
      <div class="card">
        <div class="card-body text-center" style="padding: 12px 12px !important; line-height: 0.8rem">
          <svg xmlns="http://www.w3.org/2000/svg" width="31" height="31" class="text-danger" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="6" r="4"/><path d="M15 13.327A13.6 13.6 0 0 0 12 13c-4.418 0-8 2.015-8 4.5S4 22 12 22c5.687 0 7.331-1.018 7.807-2.5"/><circle cx="18" cy="16" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="m16.667 14.667l2.666 2.666m0-2.666l-2.666 2.666"/></g></svg>       
          <br>
            <span class="badge fw-bold" style="font-size: 0.8rem; font-weight:900">0 Hari</span>
          <br>
            <span style="font-size: 0.7rem; font-weight:500" >Alpa</span>
        </div>
      </div>
    </div>
  </div>
</div>

<div class="presentcetab mt-2">
  <div class="tab-pane fade show active" id="pilled" role="tabpanel">
    <ul class="nav nav-tabs style1" role="tablist">
      <li class="nav-item">
        <a class="nav-link active" data-toggle="tab" href="#home" role="tab">
          Bulan Ini
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#profile" role="tab">
          Leaderboard
        </a>
      </li>
    </ul>

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
                      <span class="badge badge-danger">{{ $presensihariini && $d->jam_out ? $d->jam_out : '-- : -- : --' }}</span>

                  </div>
              </div>
          </li>

          @endforeach
          
      </ul>
    </div>
    <div class="tab-pane fade" id="profile" role="tabpanel">
      <ul class="listview image-listview">
          @foreach ($leaderboard as $d)
          <li>
            <div class="item">
              <img src="assets/img/sample/avatar/avatar1.jpg" alt="img" class="image">
              <div class="in">
                
                <div>
                  <b>{{  $d->nama_lengkap }}</b>
                  <br>
                  <small class="text-muted">{{ $d->jabatan }}</small>
                </div>
                <span class="badge {{ $d->jam_in < "07:30" ? "bg-success" : "bg-danger" }}">
                {{ $d->jam_in }}
              </span>
              </div>
              
            </div>
          </li>
          @endforeach
      </ul>
    </div>
  </div>
</div>




@endsection



