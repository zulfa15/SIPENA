@extends('layouts.admin.tabler')
@section('content')


<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="col">
            
            <h2 class="page-title">
                Konfigurasi Lokasi
            </h2>
        </div>
        
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6">

                <div class="card">
                    

                    <div class="card-body">
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
                        <form action="/konfigurasi/updatelokasikantor"  method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" d="M7 18c-1.829.412-3 1.044-3 1.754C4 20.994 7.582 22 12 22s8-1.006 8-2.246c0-.71-1.171-1.342-3-1.754"/><path d="M14.5 9a2.5 2.5 0 1 1-5 0a2.5 2.5 0 0 1 5 0Z"/><path d="M13.257 17.494a1.813 1.813 0 0 1-2.514 0c-3.089-2.993-7.228-6.336-5.21-11.19C6.626 3.679 9.246 2 12 2s5.375 1.68 6.467 4.304c2.016 4.847-2.113 8.207-5.21 11.19Z"/></g></svg>
                                    </span>
                                    <input type="text"
                                                    value="{{ $konfigurasi->lokasi_kantor }}"
                                                    id="lokasi_kantor"
                                                    class="form-control"
                                                    name="lokasi_kantor"
                                                    placeholder="Lokasi Kantor (lat,lng)"
                                                    required>                                    
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M21.45 11.227h-1.39a8.18 8.18 0 0 0-7.36-7.36v-1.39a.75.75 0 0 0-1.5 0v1.39a8.17 8.17 0 0 0-7.31 7.36H2.5a.75.75 0 1 0 0 1.5h1.39a8.18 8.18 0 0 0 7.36 7.36v1.39a.75.75 0 0 0 1.5 0v-1.39a8.19 8.19 0 0 0 7.36-7.36h1.39a.75.75 0 1 0 0-1.5zm-9.5 7.39a6.64 6.64 0 1 1 6.64-6.64a6.65 6.65 0 0 1-6.64 6.65z"/><path fill="currentColor" d="M16.48 11.987a4.54 4.54 0 1 1-4.53-4.54a4.53 4.53 0 0 1 4.53 4.54"/></svg>
                                    </span>
                                    <input type="number"
                                                    value="{{ $konfigurasi->radius }}"
                                                    id="radius"
                                                    class="form-control"
                                                    name="radius"
                                                    placeholder="Radius (meter)"
                                                    required>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-12">
                                    <button class="btn btn-primary w-100">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.651 7.65a7.131 7.131 0 0 0-12.68 3.15M18.001 4v4h-4m-7.652 8.35a7.13 7.13 0 0 0 12.68-3.15M6 20v-4h4"/></svg>    
                                    Update</button>
                                </div>                                
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

@endsection