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
        Schema::create('status_kedukaans', function (Blueprint $table) {
            $table->id();

            $table->enum('status', ['hidup', 'wafat'])->nullable();
            $table->foreignId('jemaat_id')
                ->constrained('jemaats')
                ->onDelete('cascade');

            $table->date('tanggal')->nullable();
            $table->string('nomor_surat')->nullable();
            $table->text('keterangan')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('status_kedukaans');
    }
};
