@extends('layouts.presensi')
@section('header')
<div class="appHeader bg-primary text-light">
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


