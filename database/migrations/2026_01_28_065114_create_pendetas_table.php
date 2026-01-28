<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pendetas', function (Blueprint $table) {
            $table->id();

            // Pendeta PASTI login
            $table->foreignId('user_id')
                ->nullable()
                ->unique()
                ->constrained()
                ->nullOnDelete();


            // Pendeta PASTI jemaat
            $table->foreignId('jemaat_id')
                ->unique()
                ->constrained('jemaats')
                ->cascadeOnDelete();

            // Tahbisan (sekali seumur hidup)
            $table->date('tanggal_tahbis');

            // Status pelayanan
            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])
                ->default('aktif');

            $table->string('no_sk_tahbis')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pendetas');
    }
};
