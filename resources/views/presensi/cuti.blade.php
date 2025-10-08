@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light" style="background-color: #1B7D7E !important;">
    <div class="left">
        <a href="javascript:;" class="headerButton goBack">
            <ion-icon name="chevron-back-outline"></ion-icon>
        </a>
    </div>
    <div class="pageTitle">Data Cuti/Sakit</div>
    <div class="right"></div>
</div>
@endsection


@section('content')
    <div class="row" style="margin-top:70px">
        <div class="col">
            @php
            $messagesuccess = Session::get('success');
            $messageerror = Session::get('error');
            @endphp
            @if (Session::get('success'))
            <div class="alert alert-success">
                {{ $messagesuccess }}
            </div>
            @endif
            @if (Session::get('error'))
            <div class="alert alert-error">
                {{ $messageerror }}
            </div>
            @endif

        </div>
    </div>

<div class="row justify-content-center">
    <div class="col-11">
        @foreach ($datacuti as $d)
            <div class="card-cuti mb-2 shadow-sm p-1 rounded-3">
                <div class="d-flex justify-content-between align-items-start">
                    <div>
                        <h6 class="fw-bold mb-1" style="font-weight: 800; font-size: 0.95rem;">{{ $d->status == 's' ? 'Sakit' : 'Izin' }}</h6>
                        <div class="d-flex align-items-center mb-1">
                            <img src="data:image/svg+xml;base64,PHN2ZyB4bWxucz0iaHR0cDovL3d3dy53My5vcmcvMjAwMC9zdmciIHdpZHRoPSIyNCIgaGVpZ2h0PSIyNCIgdmlld0JveD0iMCAwIDI0IDI0Ij48ZyBmaWxsPSJub25lIj48cGF0aCBkPSJtMTIuNTkzIDIzLjI1OGwtLjAxMS4wMDJsLS4wNzEuMDM1bC0uMDIuMDA0bC0uMDE0LS4wMDRsLS4wNzEtLjAzNXEtLjAxNi0uMDA1LS4wMjQuMDA1bC0uMDA0LjAxbC0uMDE3LjQyOGwuMDA1LjAybC4wMS4wMTNsLjEwNC4wNzRsLjAxNS4wMDRsLjAxMi0uMDA0bC4xMDQtLjA3NGwuMDEyLS4wMTZsLjAwNC0uMDE3bC0uMDE3LS40MjdxLS4wMDQtLjAxNi0uMDE3LS4wMThtLjI2NS0uMTEzbC0uMDEzLjAwMmwtLjE4NS4wOTNsLS4wMS4wMWwtLjAwMy4wMTFsLjAxOC40M2wuMDA1LjAxMmwuMDA4LjAwN2wuMjAxLjA5M3EuMDE5LjAwNS4wMjktLjAwOGwuMDA0LS4wMTRsLS4wMzQtLjYxNHEtLjAwNS0uMDE4LS4wMi0uMDIybS0uNzE1LjAwMmEuMDIuMDIgMCAwIDAtLjAyNy4wMDZsLS4wMDYuMDE0bC0uMDM0LjYxNHEuMDAxLjAxOC4wMTcuMDI0bC4wMTUtLjAwMmwuMjAxLS4wOTNsLjAxLS4wMDhsLjAwNC0uMDExbC4wMTctLjQzbC0uMDAzLS4wMTJsLS4wMS0uMDF6Ii8+PHBhdGggZmlsbD0iY3VycmVudENvbG9yIiBkPSJNMjEgMTJ2N2EyIDIgMCAwIDEtMiAySDVhMiAyIDAgMCAxLTItMnYtN3ptLTUtOWExIDEgMCAwIDEgMSAxdjFoMmEyIDIgMCAwIDEgMiAydjNIM1Y3YTIgMiAwIDAgMSAyLTJoMlY0YTEgMSAwIDAgMSAyIDB2MWg2VjRhMSAxIDAgMCAxIDEtMSIvPjwvZz48L3N2Zz4=" 
                                 alt="calendar" width="18" height="18" class="me-2">
                            <span style="font-weight: 500; font-size: 0.80rem;">{{ date('d F Y', strtotime($d->tgl_cuti)) }}</span>
                        </div>
                        <p class="text-muted mb-0" >{{ $d->keterangan }}</p>

                    </div>

                    @if ($d->status_approved == 0)
                        <span class="badge-status waiting">Menunggu</span>
                    @elseif ($d->status_approved == 1)
                        <span class="badge-status approved">Disetujui</span>
                    @elseif ($d->status_approved == 2)
                        <span class="badge-status declined">Ditolak</span>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
</div>

<style>
    .card-cuti {
        background-color: #fff;
        border-radius: 12px;
        border: 1px solid #e5e5e5;
        transition: 0.2s;
    }

    .card-cuti:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
    }

    .badge-status {
        font-size: 0.8rem;
        font-weight: 600;
        padding: 4px 10px;
        border-radius: 12px;
        color: #fff;
        display: inline-block;
        min-width: 80px;
        text-align: center;
    }
    .badge-status.waiting {
        background-color: #f1c40f;
        color: #fff; /* ubah jadi putih */
    }

    .badge-status.approved {
        background-color: #27ae60;
    }

    .badge-status.declined {
        background-color: #c0392b;
    }
    .card-cuti h6 {
        border-bottom: 1px solid #ddd; /* warna abu lembut */
        padding-bottom: 4px; /* biar teks gak nempel ke garis */
        margin-bottom: 1px; /* jarak antara garis dan tanggal */
    }

</style>


    <div class="fab-bottom-right" style="margin-bottom:70px">
        <a href="/presensi/buatcuti" class="fab">
            <svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 32 32"><path fill="currentColor" d="M19.05 5.06c0-1.68-1.37-3.06-3.06-3.06s-3.07 1.38-3.06 3.06v7.87H5.06C3.38 12.93 2 14.3 2 15.99c0 1.68 1.38 3.06 3.06 3.06h7.87v7.86c0 1.68 1.37 3.06 3.06 3.06c1.68 0 3.06-1.37 3.06-3.06v-7.86h7.86c1.68 0 3.06-1.37 3.06-3.06c0-1.68-1.37-3.06-3.06-3.06h-7.86z"/></svg>
        </a>
    </div>

    <style>
        .fab-bottom-right {
            position: fixed;
            bottom: 20px;
            right: 20px;
            z-index: 999; /* biar di atas elemen lain */
        }

        .fab {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background-color: #f59e0b; /* oranye */
            color: white;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.2);
            font-size: 24px;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .fab:hover {
            background-color: #d97706; /* oranye lebih gelap */
        }
    </style>
@endsection


