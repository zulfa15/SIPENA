@php
    // lokasi_in format: "lat,lng"
    $lokasi = explode(',', $presensi->lokasi_in);
    $lat = $lokasi[0] ?? 0;
    $lng = $lokasi[1] ?? 0;
@endphp

<div id="mapid" style="height:400px;"></div>

<script>
    var lat = {{ $lat }};
    var lng = {{ $lng }};

    var map = L.map('mapid').setView([lat, lng], 16);

    L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
    }).addTo(map);

    L.marker([lat, lng])
        .addTo(map)
        .bindPopup("{{ $presensi->nama_lengkap }}")
        .openPopup();
</script>
