<?php
    function selisih($jam_masuk, $jam_keluar)
    {
        list($h, $m, $s) = explode(":", $jam_masuk);
        $dtAwal = mktime($h, $m, $s, "0", "0", "0");

        list($h, $m, $s) = explode(":", $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, "0", "0", "0");

        $dtSelisih = $dtAkhir - $dtAwal;

        $totalmenit = $dtSelisih / 60;

        $jam = explode(".", $totalmenit / 60);
        $sisamenit = ($totalmenit / 60) - $jam[0];

        $sisamenit2 = $sisamenit * 60;

        $jml_jam = $jam[0];

        return $jml_jam . ":" . round($sisamenit2);
    }
?>
@foreach ($presensi as $d)
@php
    $foto_in = Storage::url('uploads/absensi/'.$d->foto_in);
    $foto_out = Storage::url('uploads/absensi/'.$d->foto_out);
@endphp
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $d->nik }}</td>
    <td>{{ $d->nama_lengkap }}</td>
    <td>{{ $d->jam_in }}</td>
    <td>
        <img src="{{ url($foto_in) }}" class="avatar" alt="">
    </td>

    {{-- JAM PULANG --}}
    <td>
        @if ($d->jam_out == null)
            <span class="badge bg-danger">Belum absen</span>
        @else
            {{ $d->jam_out }}
        @endif
    </td>

    {{-- FOTO PULANG --}}
    <td>
        @if ($d->jam_out != null)
            <img src="{{ url($foto_out) }}" class="avatar" alt="">
        @else
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M6 4H4V2h16v2h-2v2c0 1.615-.816 2.915-1.844 3.977c-.703.726-1.558 1.395-2.425 2.023c.867.628 1.722 1.297 2.425 2.023C17.184 15.085 18 16.385 18 18v2h2v2H4v-2h2v-2c0-1.615.816-2.915 1.844-3.977c.703-.726 1.558-1.395 2.425-2.023c-.867-.628-1.722-1.297-2.425-2.023C6.816 8.915 6 7.615 6 6zm2 0v2c0 .685.26 1.335.771 2h6.458c.51-.665.771-1.315.771-2V4zm4 9.222c-1.045.738-1.992 1.441-2.719 2.192a7 7 0 0 0-.51.586h6.458a7 7 0 0 0-.51-.586c-.727-.751-1.674-1.454-2.719-2.192"/></svg>
        @endif
    </td>

    {{-- KETERANGAN --}}
    <td>
        @if ($d->jam_in > '07:30:00')
        @php
        $jamterlambat = selisih('07:30:00',$d->jam_in);
        @endphp
            <span class="badge bg-danger">Terlambat {{ $jamterlambat }}</span>
        @else
            <span class="badge bg-success">Tepat waktu</span>
        @endif
    </td>
    <td>
        <a href="#" class="btn btn-primary tampilkanpeta" id="{{ $d->id }}">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 576 512"><path fill="currentColor" d="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120m8 80.4c3.5-6.9 6.7-13.8 9.6-20.6c.5-1.2 1-2.5 1.5-3.7l116-46.4c15.8-6.3 32.9 5.3 32.9 22.3v270.8c0 9.8-6 18.6-15.1 22.3L416 503zm-278.4-62.1c2.4 14.1 7.2 28.3 12.8 41.5c2.9 6.8 6.1 13.7 9.6 20.6v251.4L32.9 502.7C17.1 509 0 497.4 0 480.4V209.6c0-9.8 6-18.6 15.1-22.3l122.6-49zM327.8 332c13.9-17.4 35.7-45.7 56.2-77v249.3l-192-54.9V255c20.5 31.3 42.3 59.6 56.2 77c20.5 25.6 59.1 25.6 79.6 0M288 152a40 40 0 1 0 0-80a40 40 0 1 0 0 80"/></svg>
        </a>
    </td>
</tr>
@endforeach

<script>
    $(function(){
        $(".tampilkanpeta").click(function(e){
            var id = $(this).attr("id");
            $.ajax({
                type:'POST',
                url:'/tampilkanpeta',
                data:{
                    _token:"{{ csrf_token() }}",
                    id:id
                },
                cache:false,
                success:function(respond){
                    $("#loadmap").html(respond);
                }
            });
            $("#modal-tampilkanpeta").modal("show");    
        });
    })
</script>