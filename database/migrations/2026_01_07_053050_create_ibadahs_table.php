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

            // Relasi ke warta
            $table->foreignId('warta_id')
                ->constrained('wartas')
                ->onDelete('cascade');

            // Relasi ke pendeta
            $table->foreignId('pendeta_id')
                ->constrained('pendetas')
                ->onDelete('cascade');

            $table->time('waktu');
            $table->string('tema');
            $table->string('ayat');
            $table->string('file')->nullable();

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
