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
        Schema::create('beritas', function (Blueprint $table) {
            $table->id();

            // RELASI KE USER (ADMIN / PENULIS)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // DATA BERITA
            $table->date('tanggal');
            $table->string('judul');
            $table->text('ringkasan');

            // FILE (OPSIONAL)
            $table->string('file')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('beritas');
    }
};
