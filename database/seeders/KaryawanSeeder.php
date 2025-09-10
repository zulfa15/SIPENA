<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaryawanSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('karyawan')->insert([
            'nik' => '12345',
            'nama_lengkap' => 'Istiqomah ZN',
            'jabatan' => 'Intern',
            'no_hp' => '081234567890',
            'password' => Hash::make('psswd'), // sudah di-hash sekali
        ]);
    }
}
