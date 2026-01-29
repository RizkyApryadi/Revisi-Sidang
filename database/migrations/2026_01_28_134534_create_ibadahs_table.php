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
        Schema::create('ibadahs', function (Blueprint $table) {
            $table->id();

            // RELASI
            $table->foreignId('warta_id')
                ->constrained('wartas')
                ->cascadeOnDelete();

            $table->foreignId('pendeta_id')
                ->constrained('pendetas')
                ->cascadeOnDelete();

            // DATA IBADAH
            $table->time('waktu');
            $table->string('tema');
            $table->string('ayat')->nullable();

            // FILE
            $table->string('tata_ibadah')->nullable(); // path PDF
            $table->string('foto')->nullable();         // path gambar

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibadahs');
    }
};
