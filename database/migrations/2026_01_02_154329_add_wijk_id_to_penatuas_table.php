<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penatuas', function (Blueprint $table) {
            $table->foreignId('wijk_id')
                  ->nullable()
                  ->after('user_id')
                  ->constrained('wijks')
                  ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('penatuas', function (Blueprint $table) {
            $table->dropForeign(['wijk_id']);
            $table->dropColumn('wijk_id');
        });
    }
};
