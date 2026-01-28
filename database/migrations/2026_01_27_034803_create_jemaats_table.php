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
        Schema::create('jemaats', function (Blueprint $table) {
            $table->id();

            $table->foreignId('keluarga_id')
                ->constrained('keluargas')
                ->cascadeOnDelete();

            $table->string('nama');
            $table->enum('jenis_kelamin', ['L', 'P']);

            $table->string('tempat_lahir');
            $table->date('tanggal_lahir');

            $table->string('no_telp')->nullable();

            $table->enum('hubungan_keluarga', [
                'Kepala Keluarga',
                'Suami',
                'Istri',
                'Anak',
                'Tanggungan'
            ]);

            $table->string('foto')->nullable();

            // Administrasi Gereja
            $table->date('tanggal_sidi')->nullable();
            $table->string('file_sidi')->nullable();

            $table->date('tanggal_baptis')->nullable();
            $table->string('file_baptis')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jemaats');
    }
};
