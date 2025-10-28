@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="col">
            
            <h2 class="page-title">
                Data Karyawan
            </h2>
        </div>
        
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-12">
                                <form action="/karyawan" method="GET">
                                    <div class="row">
                                        <div class="col-6">
                                            <div class="form-group">
                                                <input type="text" name="nama_karyawan" class="form-control" placeholder="Nama Karyawan" value="{{ Request('nama_karyawan') }}">
                                            </div>
                                        </div>
                                        <div class="col-2">
                                            <div class="form-group">
                                                <button type="submit" class="btn btn-primary">
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M15.5 14h-.79l-.28-.27A6.47 6.47 0 0 0 16 9.5A6.5 6.5 0 1 0 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/></svg>
                                                    Cari
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-12">
                            <table class="table table-bordered">
                                <thead>
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama</th>
                                    <th>Jabatan</th>
                                    <th>No.Hp</th>
                                    <th>Foto</th>
                                    <th>Aksi</th>
                                </thead>
                                <tbody>
                                @foreach ($karyawan as $d)
                                    @php
                                    $path = Storage::url('uploads/karyawan/' . $d->foto);
                                    @endphp
                                    <tr>
                                    <td>{{ $loop->iteration + $karyawan->firstItem()-1 }}</td>
                                    <td>{{ $d->nik }}</td>
                                    <td>{{ $d->nama_lengkap }}</td>
                                    <td>{{ $d->jabatan }}</td>
                                    <td>{{ $d->no_hp }}</td>
                                    <td>
                                        @if (empty($d->foto))
                                        <img src="{{ asset('assets/img/nophoto.png') }}" class="avatar" alt="">
                                        @else
                                        <img src="{{ url($path) }}" class="avatar" alt="">
                                        @endif
                                    </td>
                                    
                                    <td></td>
                                    </tr>
                                @endforeach
                                </tbody>

                            </table>
                            {{ $karyawan->links('vendor.pagination.bootstrap-5') }}
                            </div>
                        </div>
                        
                    </div>
                </div>
                
            </div>
        </div>
    </div>
</div>

@endsection