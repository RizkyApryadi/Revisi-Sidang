<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pendaftaran_pernikahans', function (Blueprint $table) {
            $table->id();

            // Acara
            $table->date('tanggal_perjanjian')->nullable();
            $table->time('jam_perjanjian')->nullable();
            $table->text('keterangan_perjanjian')->nullable();

            $table->date('tanggal_pemberkatan')->nullable();
            $table->time('jam_pemberkatan')->nullable();
            $table->text('keterangan_pemberkatan')->nullable();

            // Administrasi (path file)
            $table->string('surat_keterangan_gereja_asal')->nullable();
            $table->string('surat_baptis_pria')->nullable();
            $table->string('surat_baptis_wanita')->nullable();
            $table->string('surat_sidi_pria')->nullable();
            $table->string('surat_sidi_wanita')->nullable();
            $table->string('foto')->nullable();
            $table->string('surat_pengantar')->nullable();

            $table->timestamps(); 
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pendaftaran_pernikahans');
    }
};
