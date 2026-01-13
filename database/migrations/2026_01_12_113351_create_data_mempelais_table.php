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
        Schema::create('data_mempelais', function (Blueprint $table) {
            $table->id();

            // Relasi ke pendaftaran_pernikahans
            $table->foreignId('pendaftaran_pernikahans_id')
                ->constrained('pendaftaran_pernikahans')
                ->onDelete('cascade');

            // Data mempelai
            $table->string('nama');
            $table->string('no_hp')->nullable();
            $table->string('email')->nullable();
            $table->string('tempat_lahir')->nullable();
            $table->date('tanggal_lahir')->nullable();
            $table->date('tanggal_baptis')->nullable();
            $table->date('tanggal_sidi')->nullable();
            $table->string('nama_ayah')->nullable();
            $table->string('nama_ibu')->nullable();
            $table->string('asal_gereja')->nullable();
            $table->string('wijk')->nullable();
            $table->text('alamat')->nullable();

            $table->timestamps(); // create_at & update_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_mempelais');
    }
};
