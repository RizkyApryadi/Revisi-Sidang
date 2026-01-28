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
        Schema::create('penatuas', function (Blueprint $table) {
            $table->id();

            // One-to-one relation to users
            $table->foreignId('user_id')->unique()->constrained()->onDelete('cascade');
            // enforce one-to-one: each penatua belongs to exactly one jemaat
            $table->foreignId('jemaat_id')
                ->unique()
                ->constrained('jemaats')
                ->cascadeOnDelete('cascade');
            $table->date('tanggal_tahbis');

            $table->enum('status', ['aktif', 'nonaktif', 'selesai'])
                ->default('aktif');

            $table->text('keterangan')->nullable();
            // Many-to-one relation to wijks
            $table->foreignId('wijk_id')->constrained('wijks')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penatuas');
    }
};
