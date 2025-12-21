@extends('layouts.admin.tabler')
@section('content')

{{-- ================= HEADER ================= --}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Pengajuan Dinas</h2>
            </div>
        </div>
    </div>
</div>

{{-- ================= BODY ================= --}}
<div class="page-body">
    <div class="container-xl">
        {{-- ================= FILTER ================= --}}
        <form action="/presensi/izinsakit" method="GET" autocomplete="off">
            <div class="row">

                {{-- Dari --}}
                <div class="col-md-6">
                    <div class="input-icon mb-3">
                        <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <path d="M8 2v4m8-4v4M3 10h18"/>
                                </g>
                            </svg>
                        </span>
                        <input type="text" value="{{ Request('dari') }}" name="dari" id="dari" class="form-control" placeholder="Dari">
                    </div>
                </div>

                {{-- Sampai --}}
                <div class="col-md-6">
                    <div class="input-icon mb-3">
                        <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                <g fill="none" stroke="currentColor" stroke-width="2">
                                    <rect x="3" y="4" width="18" height="18" rx="2"/>
                                    <path d="M8 2v4m8-4v4M3 10h18"/>
                                </g>
                            </svg>
                        </span>
                        <input type="text" value="{{ Request('sampai') }}" name="sampai" id="sampai" class="form-control" placeholder="Sampai">
                    </div>
                </div>

                {{-- NIK --}}
                <div class="col-3">
                    <input type="text" name="nik" class="form-control" placeholder="NIK">
                </div>

                {{-- Nama --}}
                <div class="col-3">
                    <input type="text" name="nama_karyawan" class="form-control" placeholder="Nama Karyawan">
                </div>

                {{-- Status --}}
                <div class="col-3">
                    <select name="status_approved" class="form-select">
                        <option value="">-- Status --</option>
                        <option value="0" {{ Request('status_approved') === '0' ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ Request('status_approved') == 1 ? 'selected' : '' }}>Disetujui</option>
                        <option value="2" {{ Request('status_approved') == 2 ? 'selected' : '' }}>Ditolak</option>
                    </select>
                </div>

                {{-- Cari --}}
                <div class="col-3">
                    <button class="btn btn-primary w-100" type="submit">Cari</button>
                </div>
            </div>
        </form>

        {{-- ================= TABEL ================= --}}
        <div class="row mt-4">
            <div class="col-12">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>NIK</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Jenis Dinas</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($data as $d)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ date('d-m-Y', strtotime($d->tgl_presensi)) }}</td>
                        <td>{{ $d->nik }}</td>
                        <td>{{ $d->nama_lengkap }}</td>
                        <td>{{ $d->jabatan }}</td>
                        <td>
                            @if ($d->jenis_dinas == 'DL')
                                <span class="badge bg-info">DL</span>
                            @elseif ($d->jenis_dinas == 'DLDD')
                                <span class="badge bg-primary">DLDD</span>
                            @else
                                <span class="badge bg-secondary">TK</span>
                            @endif
                        </td>
                        <td>{{ $d->keterangan }}</td>
                        <td>
                            @if ($d->status_approved == 1)
                                <span class="badge bg-success">Disetujui</span>
                            @elseif ($d->status_approved == 2)
                                <span class="badge bg-danger">Ditolak</span>
                            @else
                                <span class="badge bg-warning">Pending</span>
                            @endif
                        </td>
                        <td>
                            @if ($d->status_approved == 0)
                                <a href="#" class="btn btn-sm btn-primary approve"
                                data-id="{{ $d->id }}">
                                    Approve
                                </a>
                            @else
                                <span class="text-muted">Selesai</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center">Belum ada pengajuan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>

            </div>
        </div>

    </div>
</div>

{{-- ================= MODAL APPROVE ================= --}}
<div class="modal fade" id="modal-dinasluar" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="/presensi/dinasluar/approve" method="POST">
                @csrf
                <input type="hidden" name="id" id="id_dinasluar">

                <div class="modal-header">
                    <h5 class="modal-title">Persetujuan Dinas Luar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    <select name="status" class="form-select" required>
                        <option value="1">Disetujui</option>
                        <option value="2">Ditolak</option>
                    </select>
                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary w-100">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
$(function () {

    $('.approve').click(function(e){
        e.preventDefault();
        $('#id_dinasluar').val($(this).data('id'));
        $('#modal-dinasluar').modal('show');
    });

});
</script>
@endpush
