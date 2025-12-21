<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pengajuanizin extends Model
{
    protected $table = 'pengajuan_cuti';

    protected $fillable = [
        'nik',
        'tanggal',
        'keterangan',
        'status_approved',
        // kolom lain kalau ada
    ];

    public $timestamps = false; // ⬅️ TAMBAHKAN DI SINI
}
