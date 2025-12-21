@php
use Illuminate\Support\Facades\Storage;

// Hanya deklarasikan fungsi jika belum ada
if (!function_exists('selisih')) {
    function selisih($jam_masuk, $jam_keluar)
    {
        list($h, $m, $s) = explode(":", $jam_masuk);
        $dtAwal = mktime($h, $m, $s, 0, 0, 0);

        list($h, $m, $s) = explode(":", $jam_keluar);
        $dtAkhir = mktime($h, $m, $s, 0, 0, 0);

        $dtSelisih = $dtAkhir - $dtAwal;
        $totalmenit = $dtSelisih / 60;

        $jam = floor($totalmenit / 60);
        $menit = $totalmenit % 60;

        return $jam . ":" . str_pad($menit, 2, '0', STR_PAD_LEFT);
    }
}
@endphp

@forelse ($presensi as $d)
@php
    $foto_in  = $d->foto_in  ? Storage::url('uploads/absensi/'.$d->foto_in)  : null;
    $foto_out = $d->foto_out ? Storage::url('uploads/absensi/'.$d->foto_out) : null;
@endphp

<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $d->nik }}</td>
    <td>{{ $d->nama_lengkap }}</td>
    <td>{{ $d->jam_in ?? '-' }}</td>

    {{-- FOTO MASUK --}}
    <td>
        @if ($foto_in)
            <img src="{{ url($foto_in) }}" class="avatar">
        @else
            <span class="badge bg-secondary">-</span>
        @endif
    </td>

    {{-- JAM PULANG --}}
    <td>
        {{ $d->jam_out ?? '-' }}
    </td>

    {{-- FOTO PULANG --}}
    <td>
        @if ($foto_out)
            <img src="{{ url($foto_out) }}" class="avatar">
        @else
            <span class="badge bg-secondary">-</span>
        @endif
    </td>

    {{-- KETERANGAN --}}
    <td>
        {{-- JIKA LUAR RADIUS --}}
        @if ($d->keterangan)

            @if ($d->keterangan == 'dldd')
                <span class="badge bg-blue">
                    DLDD - Dinas Luar Dalam Daerah
                </span>

            @elseif ($d->keterangan == 'dl')
                <span class="badge bg-orange">
                    DL - Dinas Luar
                </span>

            @elseif ($d->keterangan == 'tk')
                <span class="badge bg-purple">
                    TK - Tugas Khusus
                </span>

            @else
                <span class="badge bg-secondary">
                    Luar Radius
                </span>
            @endif

        {{-- JIKA DALAM RADIUS --}}
        @else
            @if ($d->jam_in)
                <span class="badge bg-success">
                    {{ $d->jam_in }}
                </span>
            @else
                <span class="badge bg-secondary">-</span>
            @endif
        @endif
    </td>


    {{-- AKSI --}}
    <td>
        @if($d->jam_in)
            <a href="#" class="btn btn-primary tampilkanpeta" data-id="{{ $d->id }}">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 576 512">
                    <path fill="currentColor" d="M408 120c0 54.6-73.1 151.9-105.2 192c-7.7 9.6-22 9.6-29.6 0C241.1 271.9 168 174.6 168 120C168 53.7 221.7 0 288 0s120 53.7 120 120z"/>
                </svg>
            </a>
        @else
            <span class="text-muted">-</span>
        @endif
    </td>
</tr>

@empty
<tr>
    <td colspan="9" class="text-center text-muted">Tidak ada data presensi</td>
</tr>
@endforelse

<script>
$(function(){
    $(".tampilkanpeta").click(function(e){
        e.preventDefault();
        let id = $(this).data("id");

        $.ajax({
            type:'POST',
            url:'/tampilkanpeta',
            data:{
                _token:"{{ csrf_token() }}",
                id:id
            },
            success:function(respond){
                $("#loadmap").html(respond);
                $("#modal-tampilkanpeta").modal("show");
            },
            error:function(xhr){
                console.log(xhr.responseText);
                alert('Gagal menampilkan peta');
            }
        });
    });
});
</script>
