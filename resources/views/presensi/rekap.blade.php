@extends('layouts.admin.tabler')
@section('content')


<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="col">
            
            <h2 class="page-title">
                Rekap Presensi
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
                        <form action="/presensi/cetakrekap" target="_blank" method="POST">
                            
                            @csrf

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="bulan">Bulan</label>
                                        <select name="bulan" id="bulan" class="form-select">
                                            <option value="">Pilih Bulan</option>
                                            @for ($i=1; $i<=12; $i++)
                                            <option value="{{ $i }}"{{ date('m') == $i ? 'selected' : ''}}>{{ $namabulan[$i] }}</option>                        
                                            @endfor
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-2">
                                <div class="col-12">
                                    <div class="form-group">
                                        <label for="tahun">Tahun</label>
                                        <select name="tahun" id="tahun" class="form-select">
                                            <option value="">Pilih Tahun</option>
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

                        

                            <div class="row mt-4">
                                <div class="col-6">
                                    <button type="submit" name="cetak" class="btn btn-primary w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><g fill="none"><path fill="currentColor" d="M16 16a1 1 0 0 1 .993.883L17 17v4a1 1 0 0 1-.883.993L16 22H8a1 1 0 0 1-.993-.883L7 21v-4a1 1 0 0 1 .883-.993L8 16zm3-9a3 3 0 0 1 3 3v7a2 2 0 0 1-2 2h-1v-3a2 2 0 0 0-2-2H7a2 2 0 0 0-2 2v3H4a2 2 0 0 1-2-2v-7a3 3 0 0 1 3-3zm-2 2h-2a1 1 0 0 0-.117 1.993L15 11h2a1 1 0 0 0 .117-1.993zm0-7a1 1 0 0 1 1 1v2H6V3a1 1 0 0 1 1-1z"/></g></svg>
                                        Cetak
                                    </button>
                                </div>

                                <div class="col-6">
                                    <button type="submit" name="exportexcel" class="btn btn-success w-100">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M12 15.575q-.2 0-.375-.062T11.3 15.3l-3.6-3.6q-.3-.3-.288-.7t.288-.7q.3-.3.713-.312t.712.287L11 12.15V5q0-.425.288-.712T12 4t.713.288T13 5v7.15l1.875-1.875q.3-.3.713-.288t.712.313q.275.3.288.7t-.288.7l-3.6 3.6q-.15 .15-.325.213t-.375.062M6 20q-.825 0-1.412-.587T4 18v-2q0-.425.288-.712T5 15t.713.288T6 16v2h12v-2q0-.425.288-.712T19 15t.713.288T20 16v2q0 .825-.587 1.413T18 20z"/></svg>
                                        Export to Excel
                                    </button>
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