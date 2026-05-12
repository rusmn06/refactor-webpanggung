<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fm_rumah_tangga', function (Blueprint $table) {
            $table->id();

            // Siapa yang mengisi & urutan pengajuan
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedInteger('user_sequence_number')->nullable();

            // Step 1: Lokasi & Info Form
            $table->string('provinsi', 20);
            $table->string('kabupaten', 20);
            $table->string('kecamatan', 20);
            $table->string('desa', 20);
            $table->unsignedTinyInteger('rt');
            $table->unsignedTinyInteger('rw');
            $table->date('tgl_pembuatan');
            $table->string('nama_pendata', 100);
            $table->string('nama_responden', 100);

            // Step 3: Rekapitulasi
            $table->unsignedTinyInteger('jart');       // jumlah anggota
            $table->unsignedTinyInteger('jart_ab');    // yang bekerja
            $table->unsignedTinyInteger('jart_tb');    // tidak/belum bekerja
            $table->unsignedTinyInteger('jart_ms');    // masih sekolah
            $table->enum('jpr2rtp', ['0', '1', '2', '3', '4']); // pendapatan RT

            // Step 4: Verifikasi oleh pendata
            $table->date('verif_tgl_pembuatan');
            $table->string('verif_nama_pendata', 100);
            $table->string('ttd_pendata')->nullable();

            // Validasi oleh admin
            $table->enum('status_validasi', ['pending', 'validated', 'rejected'])
                ->default('pending');
            $table->date('admin_tgl_validasi')->nullable();
            $table->string('admin_nama_kepaladusun', 100)->nullable();
            $table->string('admin_ttd_pendata')->nullable();
            $table->text('admin_catatan')->nullable();

            $table->timestamps();
        });
    }
};
