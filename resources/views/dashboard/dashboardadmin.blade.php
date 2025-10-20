@extends('layouts.admin.tabler')
@section('content')
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="col">
            <div class="page-pretitle">
                Overview
            </div>
            <h2 class="page-title">
                Dashboard
            </h2>
        </div>
        
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6 col-xl-2">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-primary text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="576" height="512" viewBox="0 0 576 512"><path fill="currentColor" d="M64 128a112 112 0 1 1 224 0a112 112 0 1 1-224 0M0 464c0-97.2 78.8-176 176-176s176 78.8 176 176v6c0 23.2-18.8 42-42 42H42c-23.2 0-42-18.8-42-42zM432 64a96 96 0 1 1 0 192a96 96 0 1 1 0-192m0 240c79.5 0 144 64.5 144 144v22.4c0 23-18.6 41.6-41.6 41.6H389.6c6.6-12.5 10.4-26.8 10.4-42v-6c0-51.5-17.4-98.9-46.5-136.7c22.6-14.7 49.6-23.3 78.5-23.3"/></svg>                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                            {{ $totalkaryawan }}
                        </div>
                        <div class="text-secondary">Total</div>
                        <div class="text-secondary">Karyawan</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-6 col-xl-2">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-success text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M8 3c-.988 0-1.908.286-2.682.78a.75.75 0 0 1-.806-1.266A6.5 6.5 0 0 1 14.5 8c0 1.665-.333 3.254-.936 4.704a.75.75 0 0 1-1.385-.577C12.708 10.857 13 9.464 13 8a5 5 0 0 0-5-5M3.55 4.282a.75.75 0 0 1 .23 1.036A4.97 4.97 0 0 0 3 8a.75.75 0 0 1-1.5 0c0-1.282.372-2.48 1.014-3.488a.75.75 0 0 1 1.036-.23M8 5.875A2.125 2.125 0 0 0 5.875 8a3.625 3.625 0 0 1-3.625 3.625h-.037a.75.75 0 1 1 .008-1.5h.03A2.125 2.125 0 0 0 4.376 8a3.625 3.625 0 1 1 7.25 0q0 .117-.003.233a.75.75 0 1 1-1.5-.036q.003-.098.003-.197A2.125 2.125 0 0 0 8 5.875M7.995 7.25a.75.75 0 0 1 .75.75a6.5 6.5 0 0 1-4.343 6.134a.75.75 0 1 1-.498-1.416A5 5 0 0 0 7.245 8a.75.75 0 0 1 .75-.75m2.651 2.87a.75.75 0 0 1 .463.955a9.4 9.4 0 0 1-3.008 4.25a.75.75 0 0 1-.936-1.171a7.9 7.9 0 0 0 2.527-3.57a.75.75 0 0 1 .954-.463" clip-rule="evenodd"/></svg>
                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                            {{ $rekappresensi->jmlhadir == null ? $rekapcuti->jmlcuti : 0 }}
                        </div>
                        <div class="text-secondary">Karyawan</div>
                        <div class="text-secondary">Hadir</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-6 col-xl-2">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-info text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M20.68 7.014a3.85 3.85 0 0 0-.92-1.22l-3-2.72a4.15 4.15 0 0 0-2.39-1.07H8.21A5 5 0 0 0 3 6.864v10.3a5 5 0 0 0 3.31 4.53a4.7 4.7 0 0 0 1.92.3h7.56a5 5 0 0 0 5.21-4.86v-8.57a3.75 3.75 0 0 0-.32-1.55m-13-.4h3.26a1 1 0 0 1 0 2H7.68a1 1 0 1 1 0-2m8.7 10.71h-8.7a1 1 0 1 1 0-2h8.7a1 1 0 0 1 0 1.98zm0-4.35h-8.7a1 1 0 1 1 0-2h8.7a1 1 0 1 1 0 2m-.32-5.57a1.08 1.08 0 0 1-1.09-1.08v-2.65c.66.16 3.23 2.8 3.79 3.24a2 2 0 0 1 .42.49z"/></svg>                    </div>
                      <div class="col">
                        <div class="font-weight-medium">
                            {{ $rekapcuti->jmlcuti = null ? $rekapcuti->jmlcuti : 0}}
                        </div>
                        <div class="text-secondary">Karyawan</div>
                        <div class="text-secondary">Izin</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            <div class="col-md-6 col-xl-2">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-warning text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M17 3.34a10 10 0 1 1-14.995 8.984L2 12l.005-.324A10 10 0 0 1 17 3.34M12 6a1 1 0 0 0-.993.883L11 7v5l.009.131a1 1 0 0 0 .197.477l.087.1l3 3l.094.082a1 1 0 0 0 1.226 0l.094-.083l.083-.094a1 1 0 0 0 0-1.226l-.083-.094L13 11.585V7l-.007-.117A1 1 0 0 0 12 6"/></svg>                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                            {{ $rekappresensi->jmlterlambat == null ? $rekapcuti->jmlcuti : 0}}
                        </div>
                        <div class="text-secondary">Karyawan</div>
                        <div class="text-secondary">Terlambat</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-md-6 col-xl-2">
                <div class="card card-sm">
                  <div class="card-body">
                    <div class="row align-items-center">
                      <div class="col-auto">
                        <span class="bg-danger text-white avatar"><!-- Download SVG icon from http://tabler.io/icons/icon/currency-dollar -->
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M16.5 15.75a2.75 2.75 0 0 0-2.383 4.123l3.756-3.756a2.74 2.74 0 0 0-1.373-.367m2.42 1.442l-3.728 3.728a2.75 2.75 0 0 0 3.728-3.728M12.25 18.5a4.25 4.25 0 1 1 8.5 0a4.25 4.25 0 0 1-8.5 0" clip-rule="evenodd"/><path fill="currentColor" d="M16 6a4 4 0 1 1-8 0a4 4 0 0 1 8 0m-1.705 7.188A5.752 5.752 0 0 0 11.938 22C4 21.99 4 19.979 4 17.5c0-2.485 3.582-4.5 8-4.5c.798 0 1.568.066 2.295.188"/></svg>                        </span>
                      </div>
                      <div class="col">
                        <div class="font-weight-medium">
                            {{ $alpa }}
                        </div>
                        <div class="text-secondary">Karyawan</div>
                        <div class="text-secondary">Alpa</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
        </div>
    </div>
</div>
@endsection