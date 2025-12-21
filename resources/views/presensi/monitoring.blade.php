@extends('layouts.admin.tabler')

@section('content')

{{-- ================= HEADER ================= --}}
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <h2 class="page-title">Monitoring Presensi</h2>
            </div>
        </div>
    </div>
</div>

{{-- ================= BODY ================= --}}
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">

                        {{-- Filter Tanggal --}}
                        <div class="row">
                            <div class="col-12">
                                <div class="input-icon mb-3">
                                    <span class="input-icon-addon">
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                             width="24" height="24" viewBox="0 0 24 24">
                                            <path fill="currentColor"
                                                  d="M19 4h-2V3a1 1 0 0 0-2 0v1H9V3a1 1 0 0 0-2 0v1H5a3 3 0 0 0-3 3v12a3 3 0 0 0 3 3h14a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3m1 15a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1v-7h16Zm0-9H4V7a1 1 0 0 1 1-1h2v1a1 1 0 0 0 2 0V6h6v1a1 1 0 0 0 2 0V6h2a1 1 0 0 1 1 1Z"/>
                                        </svg>
                                    </span>
                                    <input
                                        type="text"
                                        id="tanggal"
                                        name="tanggal"
                                        value="{{ date('Y-m-d') }}"
                                        class="form-control"
                                        placeholder="Tanggal Presensi"
                                        autocomplete="off">
                                </div>
                            </div>
                        </div>

                        {{-- Tabel Presensi --}}
                        <div class="row">
                            <div class="col-12">
                                <table class="table table-striped table-hover">
                                    <thead>
                                        <tr>
                                            <th>No.</th>
                                            <th>NIK</th>
                                            <th>Nama Karyawan</th>
                                            <th>Jam Masuk</th>
                                            <th>Foto</th>
                                            <th>Jam Pulang</th>
                                            <th>Foto</th>
                                            <th>Keterangan</th>
                                            <th>Lokasi</th>
                                        </tr>
                                    </thead>
                                    <tbody id="loadpresensi"></tbody>
                                </table>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ================= MODAL MAP ================= --}}
<div class="modal modal-blur fade" id="modal-tampilkanpeta" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Lokasi Presensi User</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="loadmap"></div>

        </div>
    </div>
</div>

@endsection

@push('myscript')
<script>
$(function () {

    // Datepicker
    $("#tanggal").datepicker({
        autoclose: true,
        todayHighlight: true,
        format: 'yyyy-mm-dd'
    });

    function loadpresensi() {
        let tanggal = $("#tanggal").val();

        $.ajax({
            type: 'POST',
            url: '/getpresensi',
            data: {
                _token: "{{ csrf_token() }}",
                tanggal: tanggal
            },
            cache: false,
            success: function (respond) {
                $("#loadpresensi").html(respond);
            }
        });
    }

    $("#tanggal").change(function () {
        loadpresensi();
    });

    loadpresensi();

});
</script>
@endpush
