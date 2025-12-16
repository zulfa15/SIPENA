@extends('layouts.presensi')

@section('header')
    <!-- App Header -->
    <div class="appHeader bg-primary text-light" style="background-color: #1B7D7E !important;">
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
        .webcam-capture,
        .webcam-capture video {
            display: inline-block;
            width: 100% !important;
            margin: auto;
            height: auto !important;
            border-radius: 15px;
        }

        #map {
            height: 200px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
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
            @if ($cek > 0)
                <button id="takeabsen" class="btn btn-danger btn-block" >
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Pulang
                </button>
            @else
                <button id="takeabsen" class="btn btn-primary btn-block">
                    <ion-icon name="camera-outline"></ion-icon>
                    Absen Masuk
                </button>
            @endif
        </div>
    </div>

    <div class="row mt-2">
        <div class="col">
            <div id="map"></div>
        </div>
    </div>

    <audio id="notifikasi_in">
        <source src="{{ asset('assets/sound/notifikasi_in.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="notifikasi_out">
        <source src="{{ asset('assets/sound/notifikasi_out.mp3') }}" type="audio/mpeg">
    </audio>
    <audio id="radius_sound">
        <source src="{{ asset('assets/sound/radius.mp3') }}" type="audio/mpeg">
    </audio>
@endsection

@push('myscript')
<script>
    var notifikasi_in  = document.getElementById('notifikasi_in');
    var notifikasi_out = document.getElementById('notifikasi_out');
    var radius_sound   = document.getElementById('radius_sound');
    const lokasiInput  = document.getElementById('lokasi');

    // ==== KONFIGURASI LOKASI DARI DATABASE ====
    var lokasi_kantor = "{{ $lokasi_kantor }}"; // "-7.xxx,110.xxx"
    var radiusKantor  = "{{ $radius }}";          // meter

    var lok = lokasi_kantor.split(",");
    var latKantor = parseFloat(lok[0]);
    var lngKantor = parseFloat(lok[1]);

    // ==== WEBCAM ====
    Webcam.set({
        height: 480,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });
    Webcam.attach('.webcam-capture');

    // ==== GEOLOCATION ====
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        lokasiInput.value = "Geolocation tidak didukung browser";
    }

    function successCallback(position) {
        const latUser = position.coords.latitude;
        const lngUser = position.coords.longitude;

        lokasiInput.value = latUser + "," + lngUser;

        // ==== MAP ====
        var map = L.map('map').setView([latUser, lngUser], 18);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap'
        }).addTo(map);

        // Marker USER
        L.marker([latUser, lngUser])
            .addTo(map)
            .bindPopup("Posisi Anda")
            .openPopup();

        // Circle KANTOR (dari database)
        L.circle([latKantor, lngKantor], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: radiusKantor
        }).addTo(map).bindPopup("Radius Kantor");
    }

    function errorCallback(error) {
        lokasiInput.value = "Gagal mendapatkan lokasi: " + error.message;
    }

    // ==== ABSEN ====
    $("#takeabsen").click(function () {
        Webcam.snap(function (uri) {
            let image  = uri;
            let lokasi = $('#lokasi').val();

            $.ajax({
                type: 'POST',
                url: '/presensi/store',
                data: {
                    _token: "{{ csrf_token() }}",
                    image: image,
                    lokasi: lokasi
                },
                cache: false,
                success: function (respond) {
                    let parts = respond.message.split('|');

                    if (respond.status === 'success') {

                        if (parts[2] && parts[2].toLowerCase() === "in") {
                            notifikasi_in.play();
                        } else {
                            notifikasi_out.play();
                        }

                        Swal.fire({
                            title: 'Berhasil!',
                            text: parts[1],
                            icon: 'success'
                        });

                        setTimeout(() => {
                            window.location.href = '/dashboard';
                        }, 3000);

                    } else {
                        radius_sound.play();

                        Swal.fire({
                            title: 'Gagal!',
                            text: parts[1],
                            icon: 'error'
                        });
                    }
                },
                error: function () {
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem',
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
@endpush

