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

        #map {
            height: 200px;
        }
    </style>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
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
                <button id="takeabsen" class="btn btn-danger btn-block">
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
@endsection

@push('myscript')
<script>
    // --- Setting webcam ---
    Webcam.set({
        height: 480,
        width: 640,
        image_format: 'jpeg',
        jpeg_quality: 80
    });
    Webcam.attach('.webcam-capture');

    // --- Ambil lokasi ---
    const lokasiInput = document.getElementById('lokasi');

    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(successCallback, errorCallback);
    } else {
        lokasiInput.value = "Geolocation tidak didukung browser ini";
    }

    function successCallback(position) {
        // simpan latitude & longitude
        lokasiInput.value = position.coords.latitude + "," + position.coords.longitude;

        // tampilkan peta
        const map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 15);

        L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
        }).addTo(map);

        // marker
        L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);

        // lingkaran radius
        L.circle([position.coords.latitude, position.coords.longitude], {
            color: 'red',
            fillColor: '#f03',
            fillOpacity: 0.5,
            radius: 100
        }).addTo(map);
    }

    function errorCallback(error) {
        lokasiInput.value = "Tidak bisa mendapatkan lokasi: " + error.message;
    }

    // --- Tombol absen ---
    $("#takeabsen").click(function (e) {
        Webcam.snap(function (uri) {
            let image = uri;
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
                    // ambil message lalu split pakai |
                    let parts = respond.message.split('|');
                    // parts[0] = status
                    // parts[1] = pesan
                    // parts[2] = In/Out

                    if (respond.status === 'success') {
                        Swal.fire({
                            title: 'Berhasil!',
                            text: parts[1] + " (" + parts[2] + ")",
                            icon: 'success',
                            confirmButtonText: 'OK'
                        });

                        setTimeout(function () {
                            window.location.href = '/dashboard';
                        }, 3000);
                    } else {
                        Swal.fire({
                            title: 'Error!',
                            text: parts[1],
                            icon: 'error',
                            confirmButtonText: 'OK'
                        });
                    }
                },

                error: function (xhr) {
                    console.log(xhr.responseText);
                    Swal.fire({
                        title: 'Error!',
                        text: 'Terjadi kesalahan sistem!',
                        icon: 'error'
                    });
                }
            });
        });
    });
</script>
@endpush
