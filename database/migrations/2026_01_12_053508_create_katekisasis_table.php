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
        Schema::create('katekisasis', function (Blueprint $table) {
            $table->id();
            $table->string('periode_ajaran', 20);
            $table->date('tanggal_mulai');
            $table->date('tanggal_selesai');
            $table->date('tanggal_pendaftaran_tutup');
            $table->foreignId('pendeta_id')
                ->constrained('pendetas')   // relasi ke tabel pendeta
                ->onDelete('cascade');
            $table->text('deskripsi')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('katekisasis');
    }
};
