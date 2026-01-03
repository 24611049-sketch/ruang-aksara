<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Alter columns to TEXT to accommodate long OAuth tokens
        try {
            if (Schema::hasTable('users')) {
                DB::statement("ALTER TABLE `users` MODIFY `google_token` TEXT NULL");
                DB::statement("ALTER TABLE `users` MODIFY `google_refresh_token` TEXT NULL");
            }
        } catch (\Exception $e) {
            \Log::error('Failed to alter google token columns: ' . $e->getMessage());
        }
    }

    public function down()
    {
        try {
            if (Schema::hasTable('users')) {
                // revert back to varchar(255) if needed
                DB::statement("ALTER TABLE `users` MODIFY `google_token` VARCHAR(255) NULL");
                DB::statement("ALTER TABLE `users` MODIFY `google_refresh_token` VARCHAR(255) NULL");
            }
        } catch (\Exception $e) {
            \Log::error('Failed to revert google token columns: ' . $e->getMessage());
        }
    }
};
