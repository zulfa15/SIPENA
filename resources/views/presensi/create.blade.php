@extends('layouts.presensi')
@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light">
        <div class="left">
            <a href="javascript:;" class="headerButton goBack">
                <ion-icon name="chevron-back-outline"></ion-icon>
            </a>
        </div>
        <div class="pageTitle">E-Presensi</div>
        <div class="right"></div>
    </div>
    <!-- * App Header -->

<style>
    /* Styling webcam */
    .webcam-capture,
    .webcam-capture video {
        display: inline-block;
        width: 100% !important;
        margin: auto;
        height: auto !important;
        border-radius: 15px;
    }
</style>

@endsection

@section('content')
<div class="row" style="margin-top: 70px">
    <div class="col">
        <input type="hidden" id="lokasi" class="form-control mb-3" placeholder="Koordinat lokasi">
        <div class="webcam-capture"></div>
    </div>    
</div>
<div class="row">
    <div class="col">
         <button id="takeabsen" class="btn btn-primary btn-block">
            <ion-icon name="camera-outline"></ion-icon>
            Absen Masuk</button>

    </div>
</div>
@endsection

@push('myscript')
<script>
    // Setting webcam
    Webcam.set({
        height: 480,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });

    Webcam.attach('.webcam-capture');

    // Ambil lokasi
    var lokasi = document.getElementById('lokasi');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        lokasi.value = "Geolocation tidak didukung browser ini";
    }

    function successCallback(position) {
        // ambil latitude & longitude
        lokasi.value = position.coords.latitude + "," + position.coords.longitude;
    }

    function errorCallback(error) {
        lokasi.value = "Tidak bisa mendapatkan lokasi: " + error.message;
    }
</script>
@endpush
