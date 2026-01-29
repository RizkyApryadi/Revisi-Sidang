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
        Schema::create('wartas', function (Blueprint $table) {
            $table->id();

            // RELASI ke users (many wartas -> one user)
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // ATRIBUT WARTA
            $table->date('tanggal');
            $table->string('nama_minggu');
            $table->text('pengumuman');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wartas');
    }
};
