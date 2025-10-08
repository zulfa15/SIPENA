@extends('layouts.presensi')
@section('header')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0-beta/css/materialize.min.css">
<style>
    .datepicker-modal{
        max-height: 430px !important;
    }

    .datepicker-date-display {
        background-color: #1B7D7E !important;
    }
</style>
<div class="appHeader bg-primary text-light" style="background-color: #1B7D7E !important;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Form Cuti</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <form method="POST" action="{{ url('presensi/storecuti') }}" id="frmCuti">
            @csrf
            <div class="form-group">
                <input type="text" id="tgl_cuti" name="tgl_cuti" class="form-control datepicker" placeholder="Tanggal">
            </div>
            <div class="form-group">
                <select name="status" id="status" class="form-control">
                    <option value="">Izin/Sakit</option>    
                    <option value="i">Izin</option>
                    <option value="s">Sakit</option>
                </select>
            </div>
            <div class="form-group">
                <textarea name="keterangan" id="keterangan" cols="30" rows="5" class="form-control" placeholder="keterangan"></textarea>
            </div>
            <div class="form-group">
                <button class="btn w-100" style="background-color:#F7A129; border-color:#F7A129;">Kirim</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('myscript')
<script>
    var currYear = (new Date()).getFullYear();

        $(document).ready(function() {
        $(".datepicker").datepicker({
            format: "yyyy-mm-dd"    
        });

        $("#frmCuti").submit(function(){
            var tgl_cuti   = $("#tgl_cuti").val();
            var status     = $("#status").val();
            var keterangan = $("#keterangan").val();

            if(tgl_cuti == ""){
                Swal.fire({
                            title: 'Oops',
                            text: 'Tanggal Harus Diisi',
                            icon: 'warning',
                        });
                return false;
            } else if(status == ""){
                Swal.fire({
                            title: 'Oops',
                            text: 'Status Harus Diisi',
                            icon: 'warning',
                        });
                return false;
            } else if(keterangan == ""){
                Swal.fire({
                            title: 'Oops',
                            text: 'Keterangan Harus Diisi',
                            icon: 'warning',
                        });
                return false;
            }
        });

    });

</script>
@endpush