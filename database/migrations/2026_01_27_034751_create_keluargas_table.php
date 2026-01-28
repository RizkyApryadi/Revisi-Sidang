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
        Schema::create('keluargas', function (Blueprint $table) {
            $table->id();

            $table->string('nomor_registrasi')->unique();
            $table->date('tanggal_registrasi');

            $table->text('alamat');
            $table->foreignId('wijk_id')->constrained('wijks')->onDelete('cascade');

            $table->date('tanggal_pernikahan')->nullable();
            $table->string('gereja_pemberkatan')->nullable();
            $table->string('pendeta_pemberkatan')->nullable();
            $table->string('akte_pernikahan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keluargas');
    }
};
