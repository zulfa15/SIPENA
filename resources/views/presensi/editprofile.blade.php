@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light" style="background-color: #1B7D7E !important;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Edit Profile</div>
    <div class="right"></div>
</div>

@endsection


@section('content')

<div class="container" style="margin-top:4rem; max-width:450px;">

  {{-- Card Pembungkus --}}
  <div class="card" 
       style="background-color:#fff; border-radius:20px; box-shadow:0 4px 12px rgba(0,0,0,0.08); padding:25px;">

    {{-- Foto Profil --}}
    <div class="text-center mb-4">
      <div style="position: relative; display:inline-block;">
        @if (!empty($karyawan->foto))
          @php
            $path = Storage::url('uploads/karyawan/'.$karyawan->foto);
          @endphp
          <img src="{{ url($path) }}" alt="Profile" 
               style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:4px solid #fff; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
        @else
          <img src="{{ asset('assets/img/sample/avatar/avatar1.jpg') }}" alt="Profile"
               style="width:100px; height:100px; border-radius:50%; object-fit:cover; border:4px solid #fff; box-shadow:0 3px 10px rgba(0,0,0,0.1);">
        @endif
       <label for="fileuploadInput"
            style="position:absolute; bottom:0; right:0; background:#F7A129; color:white; 
                    border-radius:50%; width:35px; height:35px; display:flex; 
                    align-items:center; justify-content:center; cursor:pointer; 
                    box-shadow:0 2px 6px rgba(0,0,0,0.15);">
        <ion-icon name="camera-outline" size="small"></ion-icon>
        </label>

      </div>
      <h6 class="mt-1 mb-0" style="font-weight:600; font-size: 1rem; color:#333;">{{ $karyawan->nama_lengkap }}</h6>
      <small style="font-weight:600; font-size: 0.8rem; color:#393939;">{{ $karyawan->jabatan ?? 'Intern' }}</small>
    </div>

    {{-- Notifikasi --}}
    @php
      $messagesuccess = Session::get('success');
      $messageerror = Session::get('error');
    @endphp
    @if(Session::get('success'))
      <div class="alert alert-success text-center">{{ $messagesuccess }}</div>
    @endif
    @if(Session::get('error'))
      <div class="alert alert-danger text-center">{{ $messageerror }}</div>
    @endif

    {{-- Form Edit --}}
    <form action="/presensi/{{ $karyawan->nik }}/updateprofile" method="POST" enctype="multipart/form-data">
      @csrf
      <input type="file" name="foto" id="fileuploadInput" accept=".png, .jpg, .jpeg" style="display:none;">

     <div class="form-group boxed">
        <label style="font-weight:600; font-size:0.7rem; color:#ACACAC; margin-bottom:0px;">
            Nama Lengkap
        </label>
        <input type="text" class="form-control" name="nama_lengkap"
                value="{{ $karyawan->nama_lengkap }}" placeholder="Masukkan nama lengkap"
                autocomplete="off"
                style="border-radius:10px; border:1px solid #ddd; padding:10px; margin-top:0;">
        </div>


      <div class="form-group boxed ">
        <label style="font-weight:600; font-size: 0.7rem; color:#ACACAC; margin-bottom:0px;">Nomor HP</label>
        <input type="text" class="form-control" name="no_hp" value="{{ $karyawan->no_hp }}" 
               placeholder="Masukkan nomor HP" autocomplete="off"
               style="border-radius:10px; border:1px solid #ddd; padding:10px;">
      </div>

      <div class="form-group boxed">
        <label style="font-weight:600; font-size: 0.7rem; color:#ACACAC; margin-bottom:0px;">Password</label>
        <input type="password" class="form-control" name="password" 
               placeholder="Masukkan password baru" autocomplete="off"
               style="border-radius:10px; border:1px solid #ddd; padding:10px;">
        </div>



      <div class="form-group text-center">
        <button type="submit" class="btn btn-block" 
                style="background-color:#F7A129; border:none; color:white; padding:10px 0; border-radius:12px; font-weight:600;">
          <ion-icon name="refresh-outline" style="margin-right:6px;"></ion-icon> Update Profile
        </button>
      </div>
    </form>
  </div>
</div>

@endsection
