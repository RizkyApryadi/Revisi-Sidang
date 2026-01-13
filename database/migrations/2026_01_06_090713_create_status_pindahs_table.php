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
        Schema::create('status_pindahs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jemaat_id')
                ->constrained('jemaats')
                ->onDelete('cascade');

            // status utama
            $table->enum('status', ['tetap', 'pindah_masuk']);

            // detail pindah masuk
            $table->string('nomor_surat')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('nama_gereja')->nullable();
            $table->string('kota')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_pindahs');
    }
};
