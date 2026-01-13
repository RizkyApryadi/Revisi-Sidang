<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jemaats', function (Blueprint $table) {
            if (!Schema::hasColumn('jemaats', 'status')) {
                // Make status nullable and set default to 'pending' so inserts without explicit status succeed
                $table->string('status')->nullable()->default('pending')->after('nomor_jemaat');
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jemaats', function (Blueprint $table) {
            // dropColumn will be executed during rollback; check for existence to be safe
            if (Schema::hasColumn('jemaats', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
