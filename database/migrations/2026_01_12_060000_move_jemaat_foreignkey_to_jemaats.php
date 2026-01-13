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
        // Add pendaftaran_sidi_id to jemaats
        Schema::table('jemaats', function (Blueprint $table) {
            $table->foreignId('pendaftaran_sidi_id')->nullable()->constrained('pendaftaran_sidis')->nullOnDelete();
        });

        // Remove jemaat_id from pendaftaran_sidis if exists
        Schema::table('pendaftaran_sidis', function (Blueprint $table) {
            // drop foreign key first
            $table->dropForeign(['jemaat_id']);
            $table->dropColumn('jemaat_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Add jemaat_id back to pendaftaran_sidis
        Schema::table('pendaftaran_sidis', function (Blueprint $table) {
            $table->foreignId('jemaat_id')->nullable()->constrained('jemaats')->nullOnDelete();
        });

        // Drop pendaftaran_sidi_id from jemaats
        Schema::table('jemaats', function (Blueprint $table) {
            $table->dropForeign(['pendaftaran_sidi_id']);
            $table->dropColumn('pendaftaran_sidi_id');
        });
    }
};
