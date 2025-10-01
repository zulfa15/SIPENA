@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Rekap Presensi</div>
    <div class="right"></div>
</div>
@endsection

@section('content')
<div class="row" style="margin-top:70px">
    <div class="col">
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="bulan" id="bulan" class="form-control">
                        <option value="">Bulan</option>
                        @for ($i=1; $i<=12; $i++)
                        <option value="{{ $i }}"{{ date('m') == $i ? 'selected' : ''}}>{{ $namabulan[$i] }}</option>                        
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <select name="tahun" id="tahun" class="form-control">
                        <option value="">Tahun</option>
                        @php
                        $tahunmulai = 2022;
                        $tahunskrg = date("Y");
                        @endphp
                        @for ($tahun=$tahunmulai; $tahun<= $tahunskrg; $tahun++)
                            <option value="{{ $tahun }}"{{ date('Y') == $tahun ? 'selected' : ''}}>{{ $tahun }}</option>
                        @endfor
                    </select>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="form-group">
                    <button type="button" class="btn btn-primary btn-block" id="getdata">
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24">
                            <path fill="currentColor" d="M15.5 14h-.79l-.28-.27a6.5 6.5 0 0 0 1.48-5.34c-.47-2.78-2.79-5-5.59-5.34a6.505 6.505 0 0 0-7.27 7.27c.34 2.8 2.56 5.12 5.34 5.59a6.5 6.5 0 0 0 5.34-1.48l.27.28v.79l4.25 4.25c.41.41 1.08.41 1.49 0s.41-1.08 0-1.49zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5S14 7.01 14 9.5S11.99 14 9.5 14"/>
                        </svg> Cari
                    </button>
                </div>
            </div>
</div>

    </div>
</div>
<div class="row">
    <div class="col" id="showhistori"></div>
</div>
@endsection

@push('myscript')
<script>
    $(function(){
        $("#getdata").click(function(e) {
            var bulan = $("#bulan").val();
            var tahun = $("#tahun").val();
            $.ajax({
                type: 'POST',
                url:'/gethistori',
                data:{
                    _token: "{{ csrf_token() }}",
                    bulan:bulan,
                    tahun:tahun,
                 }
                 ,cache: false,
                 success: function(respond){
                    $("#showhistori").html(respond);

                 }


            });

        });
    });
</script>
@endpush