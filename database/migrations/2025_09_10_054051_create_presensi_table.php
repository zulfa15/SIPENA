<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('presensi', function (Blueprint $table) {
            $table->id(); // kolom id auto increment
            $table->char('nik', 5); // foreign key ke tabel karyawan
            $table->date('tgl_presensi');
            $table->time('jam_in')->nullable();
            $table->time('jam_out')->nullable();
            $table->string('foto_in', 255)->nullable();
            $table->string('foto_out', 255)->nullable();
            $table->text('location')->nullable();
            $table->timestamps();

            // Relasi ke tabel karyawan
            $table->foreign('nik')->references('nik')->on('karyawan')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('presensi');
    }
};
