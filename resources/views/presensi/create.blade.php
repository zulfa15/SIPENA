@extends('layouts.presensi')

@section('header')
<div class="appHeader bg-primary text-light" style="background-color:#1B7D7E!important">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">E-Presensi</div>
</div>

<style>
.webcam-capture, .webcam-capture video {
    width:100%!important;
    border-radius:15px;
}
#map { height:200px; }

.overlay {
    display:none;
    position:fixed;
    inset:0;
    background:rgba(0,0,0,.6);
    z-index:9999;
}
.overlay-box {
    background:#fff;
    padding:20px;
    margin:30% auto;
    width:90%;
    max-width:380px;
    border-radius:16px;
}
</style>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <input type="hidden" id="lokasi">
        <div class="webcam-capture"></div>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <button id="takeabsen" class="btn btn-primary btn-block">
            <ion-icon name="camera-outline"></ion-icon> Absen
        </button>
    </div>
</div>

<div class="row mt-2">
    <div class="col">
        <div id="map"></div>
    </div>
</div>

<!-- 🔴 OVERLAY DINAS -->
<div id="overlayDinas" class="overlay">
    <div class="overlay-box">
        <h4>Absen di Luar Radius</h4>

        <select id="jenis_dinas" class="form-control mb-2">
            <option value="">-- Pilih Jenis Dinas --</option>
            <option value="DLDD">DLDD - Dinas Luar Dalam Daerah</option>
            <option value="DL">DL - Dinas Luar</option>
            <option value="TK">TK - Tugas Khusus</option>
        </select>

        <textarea id="keterangan" class="form-control mb-3"
            placeholder="Keterangan tugas"></textarea>

        <div class="text-end">
            <button class="btn btn-secondary" onclick="tutupOverlay()">Batal</button>
            <button class="btn btn-primary" onclick="kirimDinas()">Kirim</button>
        </div>
    </div>
</div>

<!-- 🟢 OVERLAY BERHASIL -->
<div id="overlayBerhasil" class="overlay">
    <div class="overlay-box text-center">
        <ion-icon name="checkmark-circle"
            style="font-size:64px;color:#4CAF50"></ion-icon>
        <h4 class="mt-2">Absen Diajukan</h4>
        <p class="text-muted">
            Pengajuan dinas luar berhasil dikirim
        </p>
        <button class="btn btn-primary btn-block" onclick="keDashboard()">OK</button>
    </div>
</div>
@endsection

@push('myscript')
<script>
const lokasiInput = document.getElementById('lokasi');
var lokasi_kantor = "{{ $lokasi_kantor }}";
var radiusKantor  = "{{ $radius }}";

let imageBase64 = null;

/* ================= WEBCAM ================= */
Webcam.set({
    width:640,
    height:480,
    image_format:'jpeg',
    jpeg_quality:80
});
Webcam.attach('.webcam-capture');

/* ================= GEOLOCATION ================= */
navigator.geolocation.getCurrentPosition(pos => {
    let lat = pos.coords.latitude;
    let lng = pos.coords.longitude;
    lokasiInput.value = lat+','+lng;

    let map = L.map('map').setView([lat,lng],18);
    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(map);
    L.marker([lat,lng]).addTo(map).bindPopup('Posisi Anda').openPopup();

    let kantor = lokasi_kantor.split(',');
    L.circle([kantor[0],kantor[1]], {
        radius: radiusKantor,
        color:'red'
    }).addTo(map);
});

/* ================= STATUS ABSEN ================= */
let sudahAbsenMasuk = {{ $cek > 0 ? 'true' : 'false' }};

/* UBAH TOMBOL JIKA ABSEN PULANG */
if (sudahAbsenMasuk) {
    $('#takeabsen')
        .removeClass('btn-primary')
        .addClass('btn-danger')
        .html('<ion-icon name="log-out-outline"></ion-icon> Absen Pulang');
}

/* ================= ABSEN ================= */
$('#takeabsen').click(function(){
    Webcam.snap(uri => {

        imageBase64 = uri;

        $.post('/presensi/store',{
            _token:'{{ csrf_token() }}',
            image: uri,
            lokasi: $('#lokasi').val()
        }, res => {

            /* MASUK + LUAR RADIUS */
            if(res.status === 'outside'){
                $('#overlayDinas').fadeIn();
            }

            /* BERHASIL (MASUK / PULANG) */
            else if(res.status === 'success'){
                Swal.fire('Berhasil', res.message, 'success');
                setTimeout(()=>location.href='/dashboard',2000);
            }

            /* ERROR */
            else{
                Swal.fire('Gagal', res.message, 'error');
            }

        });
    });
});

/* ================= KIRIM DINAS (KHUSUS MASUK) ================= */
function kirimDinas() {
    let jenis  = $('#jenis_dinas').val();
    let ket    = $('#keterangan').val();
    let lokasi = $('#lokasi').val();

    if (!jenis || !ket || !imageBase64) {
        Swal.fire('Peringatan', 'Lengkapi data dan ambil foto', 'warning');
        return;
    }

    let formData = new FormData();
    formData.append('_token', "{{ csrf_token() }}");
    formData.append('jenis_dinas', jenis);
    formData.append('keterangan', ket);
    formData.append('lokasi', lokasi);
    formData.append('foto', imageBase64);

    $.ajax({
        type: 'POST',
        url: '/presensi/luar-radius',
        data: formData,
        processData: false,
        contentType: false,
        success: function (res) {
            if (res.status === 'success') {
                $('#overlayDinas').hide();
                $('#overlayBerhasil').fadeIn();
            } else {
                Swal.fire('Gagal', res.message, 'error');
            }
        },
        error: function (xhr) {
            $('#overlayDinas').hide();
            Swal.fire(
                'Gagal',
                xhr.responseJSON?.message ?? 'Server error',
                'error'
            );
        }
    });
}

/* ================= NAVIGASI ================= */
function keDashboard(){
    location.href='/dashboard';
}
</script>
@endpush
