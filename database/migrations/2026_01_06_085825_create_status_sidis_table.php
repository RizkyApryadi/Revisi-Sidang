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
        Schema::create('status_sidis', function (Blueprint $table) {
            $table->id();

            $table->foreignId('jemaat_id')
                ->constrained('jemaats')
                ->onDelete('cascade');

            $table->enum('status', ['belum', 'sudah']);
            $table->enum('jenis', ['lokal', 'luar'])->nullable();

            $table->string('nomor_surat')->nullable();
            $table->date('tanggal')->nullable();
            $table->string('pendeta')->nullable();

            // khusus jika gereja luar
            $table->string('nama_gereja')->nullable();
            $table->string('alamat')->nullable();
            $table->string('kota')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_sidis');
    }
};
