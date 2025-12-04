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
                                
                                @if (Session::get('success'))
                                    <div class="alert alert-success">
                                        {{ Session::get('success') }}
                                    </div>
                                @endif
                                @if (Session::get('warning'))
                                    <div class="alert alert-warning">
                                        {{ Session::get('warning') }}
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <button type="button" class="btn btn-primary" id="btnTambahkaryawan">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M18 13h-5v5c0 .55-.45 1-1 1s-1-.45-1-1v-5H6c-.55 0-1-.45-1-1s.45-1 1-1h5V6c0-.55.45-1 1-1s1 .45 1 1v5h5c.55 0 1 .45 1 1s-.45 1-1 1"/>
                                    </svg>
                                    Tambah Data
                                </button>

                            </div>
                        </div>
                        <div class="row mt-2">
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
                                    
                                    <td>
                                        <div class="d-flex align-items-center gap-2">

                                            <!-- Tombol Edit -->
                                            <a href="#" 
                                            class="edit btn btn-info btn-sm d-flex justify-content-center align-items-center" 
                                            style="width: 36px; height: 36px;"
                                            nik="{{ $d->nik }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                                    viewBox="0 0 24 24">
                                                    <g fill="none" stroke="currentColor" stroke-linecap="round" 
                                                    stroke-linejoin="round" stroke-width="2">
                                                        <path d="m16.475 5.408l2.117 2.117m-.756-3.982L12.109 9.27a2.1 
                                                        2.1 0 0 0-.58 1.082L11 13l2.648-.53c.41-.082.786-.283 
                                                        1.082-.579l5.727-5.727a1.853 1.853 0 1 0-2.621-2.621"/>
                                                        <path d="M19 15v3a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V7a2 2 0 0 
                                                        1 2-2h3"/>
                                                    </g>
                                                </svg>
                                            </a>

                                            <!-- Tombol Delete -->
                                            <form action="/karyawan/{{ $d->nik }}/delete" method="POST">
                                                @csrf
                                                
                                                <a 
                                                    class="btn btn-danger btn-sm d-flex justify-content-center align-items-center delete-confirm"
                                                    style="width: 36px; height: 36px;" >
                                                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" 
                                                        viewBox="0 0 16 16">
                                                        <path fill="currentColor" 
                                                            d="M11 1.75V3h2.25a.75.75 0 0 1 0 1.5H2.75a.75.75 0 0 
                                                            1 0-1.5H5V1.75C5 .784 5.784 0 6.75 0h2.5C10.216 0 11 
                                                            .784 11 1.75M4.496 6.675l.66 6.6a.25.25 0 0 0 .249.225h5.19a.25.25 
                                                            0 0 0 .249-.225l.65-6.6a.75.75 0 0 1 1.492.149l-.66 6.6A1.75 
                                                            1.75 0 0 1 10.595 15h-5.19a1.75 1.75 0 0 1-1.741-1.575l-.66-6.6a.75.75 
                                                            0 1 1 1.492-.15M6.5 1.75V3h3V1.75a.25.25 0 0 0-.25-.25h-2.5a.25.25 
                                                            0 0 0-.25.25"/>
                                                    </svg>
                                                </a>
                                            </form>

                                        </div>
                                    </td>

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
{{-- Modal Edit --}}
<div class="modal modal-blur fade" id="modal-editkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Edit Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="loadeditform">
                
            </div>
            
        </div>
    </div>

</div>

<div class="modal modal-blur fade" id="modal-inputkaryawan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Data Karyawan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="/karyawan/store" method="POST" id="frmKaryawan" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="9" cy="9" r="2"/><path d="M13 15c0 1.105 0 2-4 2s-4-.895-4-2s1.79-2 4-2s4 .895 4 2Z"/><path d="M2 12c0-3.771 0-5.657 1.172-6.828S6.229 4 10 4h4c3.771 0 5.657 0 6.828 1.172S22 8.229 22 12s0 5.657-1.172 6.828S17.771 20 14 20h-4c-3.771 0-5.657 0-6.828-1.172S2 15.771 2 12Z"/><path stroke-linecap="round" d="M19 12h-4m4-3h-5m5 6h-3"/></g></svg>                            
                            </span>
                            <input type="text" value="" id="nik" class="form-control" name="nik" placeholder="NIK">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 4a4 4 0 0 1 4 4a4 4 0 0 1-4 4a4 4 0 0 1-4-4a4 4 0 0 1 4-4m0 2a2 2 0 0 0-2 2a2 2 0 0 0 2 2a2 2 0 0 0 2-2a2 2 0 0 0-2-2m0 7c2.67 0 8 1.33 8 4v3H4v-3c0-2.67 5.33-4 8-4m0 1.9c-2.97 0-6.1 1.46-6.1 2.1v1.1h12.2V17c0-.64-3.13-2.1-6.1-2.1"/></svg>
                            </span>
                            <input type="text" value="" id="nama_lengkap" class="form-control" name="nama_lengkap" placeholder="Nama Lengkap">
                            </div>
                        </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19 6h-3V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v1H5a3 3 0 0 0-3 3v9a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V9a3 3 0 0 0-3-3m-9-1h4v1h-4Zm10 13a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-5.61L8.68 14A1.2 1.2 0 0 0 9 14h6a1.2 1.2 0 0 0 .32-.05L20 12.39Zm0-7.72L14.84 12H9.16L4 10.28V9a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1Z"/></svg>                            
                            </span>
                            <input type="text" value="" id="jabatan" class="form-control" name="jabatan" placeholder="Jabatan">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <div class="input-icon mb-3">
                            <span class="input-icon-addon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M19.95 21q-3.125 0-6.175-1.362t-5.55-3.863t-3.862-5.55T3 4.05q0-.45.3-.75t.75-.3H8.1q.35 0 .625.238t.325.562l.65 3.5q.05.4-.025.675T9.4 8.45L6.975 10.9q.5.925 1.187 1.787t1.513 1.663q.775.775 1.625 1.438T13.1 17l2.35-2.35q.225-.225.588-.337t.712-.063l3.45.7q.35.1.575.363T21 15.9v4.05q0 .45-.3.75t-.75.3M6.025 9l1.65-1.65L7.25 5H5.025q.125 1.025.35 2.025T6.025 9m8.95 8.95q.975.425 1.988.675T19 18.95v-2.2l-2.35-.475zm0 0"/></svg>                            </span>
                            <input type="text" value="" id="no_hp" class="form-control" name="no_hp" placeholder="No. HP">
                            </div>
                        </div>
                    </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-12">
                            <div class="form-label">Masukkan Foto profil</div>
                            <input type="file" name="foto" class="form-control">
                        </div>
                    </div>
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="form-group">
                                <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14L21 3m0 0l-6.5 18a.55.55 0 0 1-1 0L10 14l-7-3.5a.55.55 0 0 1 0-1z"/></svg>
                                    Simpan
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            
        </div>
    </div>

</div>
@endsection

@push('myscript')
<script>
    $(function(){
        $("#btnTambahkaryawan").click(function(){
            $("#modal-inputkaryawan").modal("show");
        });

        $(".edit").click(function(){
            var nik = $(this).attr('nik');
            $.ajax({
                type:'POST',
                url:'/karyawan/edit',
                cache:false,
                data:{
                    _token: "{{ csrf_token() }}"
                    , nik: nik
                },
                success:function(respond){
                    $("#loadeditform").html(respond);
                }
            });
            $("#modal-editkaryawan").modal("show");
        });

        $(".delete-confirm").click(function(e){
            var form = $(this).closest('form');
            e.preventDefault();
            Swal.fire({
            title: "Apakah Anda Yakin Data Ini Mau di Hapus?",
            text: "Jika Ya Maka Data Akan Terhapus Permanent",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Hapus"
            }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                title: "Deleted!",
                text: "Data Berhasil Di Hapus",
                icon: "success"
                });
            }
            });
        });

        $("#frmKaryawan").submit(function(){
            var nik = $("#nik").val();
            var nama_lengkap = $("#nama_lengkap").val();
            var jabatan = $("#jabatan").val();
            var no_hp = $("#no_hp").val();
            if(nik==""){
                //alert('NIK harus diisi');
                Swal.fire({
                    title: 'Warning',
                    text: 'NIK harus diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nik").focus();
                });
                
                return false;
            }else if (nama_lengkap==""){
                Swal.fire({
                    title: 'Warning',
                    text: 'Nama harus diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#nama_lengkap").focus();
                });
                
                return false;

            }else if (jabatan==""){
                Swal.fire({
                    title: 'Warning',
                    text: 'jabatan harus diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#jabatan").focus();
                });
                
                return false;
            }else if (no_hp==""){
                Swal.fire({
                    title: 'Warning',
                    text: 'No. Hp harus diisi',
                    icon: 'warning',
                    confirmButtonText: 'Ok'
                }).then((result)=>{
                    $("#no_hp").focus();
                });
                
                return false;
            }

        });
    });
</script>
@endpush