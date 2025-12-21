@extends('layouts.admin')

@section('content')
<div class="container">
    <h4 class="mb-3">Data Dinas Luar</h4>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead class="text-center">
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Tanggal</th>
                <th>Jenis</th>
                <th>Keterangan</th>
                <th>Lokasi</th>
                <th>Status</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
        @foreach ($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->nama_lengkap }}</td>
                <td>{{ $item->tanggal }}</td>
                <td>{{ $item->jenis_dinas }}</td>
                <td>{{ $item->keterangan }}</td>
                <td>{{ $item->lokasi }}</td>
                <td class="text-center">
                    @if($item->status_approved == 0)
                        <span class="badge bg-warning">Pending</span>
                    @elseif($item->status_approved == 1)
                        <span class="badge bg-success">Disetujui</span>
                    @else
                        <span class="badge bg-danger">Ditolak</span>
                    @endif
                </td>
                <td class="text-center">

                    @if($item->status_approved == 0)
                        <form action="/admin/dinas-luar/approve" method="POST" style="display:inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="status" value="1">
                            <button class="btn btn-sm btn-success">Setujui</button>
                        </form>

                        <form action="/admin/dinas-luar/approve" method="POST" style="display:inline">
                            @csrf
                            <input type="hidden" name="id" value="{{ $item->id }}">
                            <input type="hidden" name="status" value="2">
                            <button class="btn btn-sm btn-danger">Tolak</button>
                        </form>
                    @else
                        <em>-</em>
                    @endif

                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
</div>
@endsection
