<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Drop and recreate foreign key while making column nullable. Using raw SQL to avoid requiring doctrine/dbal.
        // Note: adjust constraint names if your DB uses different naming.
        DB::statement('ALTER TABLE `penatuas` DROP FOREIGN KEY `penatuas_user_id_foreign`');
        DB::statement('ALTER TABLE `penatuas` MODIFY `user_id` BIGINT UNSIGNED NULL');
        DB::statement('ALTER TABLE `penatuas` ADD CONSTRAINT `penatuas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement('ALTER TABLE `penatuas` DROP FOREIGN KEY `penatuas_user_id_foreign`');
        DB::statement('ALTER TABLE `penatuas` MODIFY `user_id` BIGINT UNSIGNED NOT NULL');
        DB::statement('ALTER TABLE `penatuas` ADD CONSTRAINT `penatuas_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE');
    }
};
