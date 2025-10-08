<!-- App Bottom Menu -->
<style>
    .appBottomMenu {
        display: flex;
        justify-content: space-around;
        align-items: center;
        background: #fff;
        border-top: 1px solid #ddd;
        padding: 8px 0;
        position: fixed;
        bottom: 0;
        left: 0;
        width: 100%;
        z-index: 1000;
    }

    .appBottomMenu .item {
        flex: 1;
        text-align: center;
        font-size: 12px;
        color: #000;
        text-decoration: none;
    }

    .appBottomMenu .item.active svg,
.appBottomMenu .item.active strong {
    color: #f59e0b !important;
    fill: #f59e0b !important;
}

.appBottomMenu .item svg,
.appBottomMenu .item strong {
    transition: color 0.3s ease, fill 0.3s ease;
}



    .appBottomMenu .item img {
        width: 26px;
        height: 26px;
        display: block;
        margin: 0 auto 3px;
    }

    /* Kamera khusus */
   .camera-button {
        background-color: #f59e0b; /* warna orange */
        width: 60px;
        height: 60px;
        transform: rotate(45deg); /* diamond shape */
        display: flex;
        align-items: center;
        justify-content: center;
        margin: auto;
        margin-top: -50px; /* naikkan tombol */
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
        border-radius: 12px; /* sudut agak halus */
    }



    .camera-button svg {
        transform: rotate(-45deg); /* balikin ikon biar tegak */
        width: 40px;
        height: 40px;
        color: white
        ;
    }
</style>

<div class="appBottomMenu">
    <!-- Home -->
    <a href="/dashboard" class="item {{ request()->is('dashboard') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="currentColor">
                <path d="M4 19v-9q0-.475.213-.9t.587-.7l6-4.5q.525-.4 1.2-.4t1.2.4l6 4.5q.375.275.588.7T20 10v9q0 .825-.588 1.413T18 21h-3q-.425 0-.712-.288T14 20v-5q0-.425-.288-.712T13 14h-2q-.425 0-.712.288T10 15v5q0 .425-.288.713T9 21H6q-.825 0-1.412-.587T4 19"/>
            </svg>
            <strong>Beranda</strong>
        </div>
    </a>

    <!-- Rekap -->
    <a href="/presensi/histori" class="item {{ request()->is('presensi/histori') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M4.172 3.172C3 4.343 3 6.229 3 10v4c0 3.771 0 5.657 1.172 6.828S7.229 22 11 22h2c3.771 0 5.657 0 6.828-1.172S21 17.771 21 14v-4c0-3.771 0-5.657-1.172-6.828S16.771 2 13 2h-2C7.229 2 5.343 2 4.172 3.172M7.25 8A.75.75 0 0 1 8 7.25h8a.75.75 0 0 1 0 1.5H8A.75.75 0 0 1 7.25 8m0 4a.75.75 0 0 1 .75-.75h8a.75.75 0 0 1 0 1.5H8a.75.75 0 0 1-.75-.75M8 15.25a.75.75 0 0 0 0 1.5h5a.75.75 0 0 0 0-1.5z" clip-rule="evenodd"/></svg>
            <strong>Rekap</strong>
        </div>
    </a>

    <!-- Kamera -->
    <a href="/presensi/create" class="item">
    <div class="col">
        <div class="camera-button">
            <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 24 24"><path fill="currentColor" d="M12.02 9.077q-.237 0-.368-.212q-.13-.211 0-.423l2.637-4.594q.142-.229.365-.291q.223-.063.457.016q1.668.602 3.022 1.874t2.098 2.945q.125.287-.009.486q-.133.199-.445.199zm-3.287 1.571l-2.654-4.68q-.142-.23-.081-.478q.062-.248.246-.39q1.2-1.004 2.682-1.552T12 3q.24 0 .537.018q.296.019.557.05q.293.03.405.241q.113.212-.043.493l-3.983 6.846q-.13.212-.37.212t-.37-.212m-4.94 3.468q-.218 0-.404-.16t-.237-.375q-.088-.429-.12-.812T3 12q0-1.402.445-2.753q.446-1.35 1.309-2.578q.198-.253.455-.253t.418.297l3.854 6.768q.13.211-.003.423q-.134.212-.37.212zm5.192 6.35q-1.746-.628-3.15-1.935Q4.43 17.223 3.73 15.55q-.125-.287.021-.486t.458-.199h7.9q.236 0 .357.212q.121.211-.01.423l-2.643 4.679q-.143.229-.366.3t-.463-.013M12 21q-.235 0-.499-.018t-.493-.05q-.292-.05-.408-.26q-.115-.213.04-.474l3.952-6.72q.131-.21.385-.21t.385.21l2.584 4.53q.137.217.084.465t-.25.408q-1.188 1.004-2.687 1.561T12 21m6.39-3.702l-3.865-6.817q-.13-.212-.007-.423t.36-.212h5.33q.217 0 .404.159q.186.159.236.376q.07.41.11.811q.042.402.042.808q0 1.46-.436 2.788q-.435 1.327-1.318 2.548q-.18.237-.44.24t-.416-.278"/></svg>
        </div>
    </div>
</a>




    <!-- Cuti -->
    <a href="/presensi/cuti" class="item {{ request()->is('presensi/cuti') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" d="M2 19c0 1.7 1.3 3 3 3h14c1.7 0 3-1.3 3-3v-8H2zM19 4h-2V3c0-.6-.4-1-1-1s-1 .4-1 1v1H9V3c0-.6-.4-1-1-1s-1 .4-1 1v1H5C3.3 4 2 5.3 2 7v2h20V7c0-1.7-1.3-3-3-3"/></svg>
            <strong>Cuti</strong>
        </div>
    </a>

    <!-- Akun -->
    <a href="/editprofile" class="item {{ request()->is('editprofile') ? 'active' : '' }}">
        <div class="col">
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"><path fill="currentColor" fill-rule="evenodd" d="M8 7a4 4 0 1 1 8 0a4 4 0 0 1-8 0m0 6a5 5 0 0 0-5 5a3 3 0 0 0 3 3h12a3 3 0 0 0 3-3a5 5 0 0 0-5-5z" clip-rule="evenodd"/></svg>
            <strong>Akun</strong>
        </div>
    </a>
</div>
<!-- * App Bottom Menu -->




