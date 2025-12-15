<?php

if (!function_exists('selisih')) {
    function selisih($jam_masuk, $jam_keluar)
    {
        if (is_null($jam_keluar)) return '0:00';

        list($h, $m, $s) = explode(":", $jam_masuk);
        $awal = mktime($h, $m, $s, 0, 0, 0);

        list($h, $m, $s) = explode(":", $jam_keluar);
        $akhir = mktime($h, $m, $s, 0, 0, 0);

        $selisih = $akhir - $awal;
        $menit = $selisih / 60;

        $jam = floor($menit / 60);
        $sisaMenit = round($menit % 60);

        return $jam . ':' . str_pad($sisaMenit, 2, '0', STR_PAD_LEFT);
    }
}


