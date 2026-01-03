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
        Schema::table('users', function (Blueprint $table) {
            // Add columns without positional `after` to avoid failures if referenced columns missing
            if (!Schema::hasColumn('users', 'role')) {
                $table->enum('role', ['user', 'admin', 'owner'])->default('user');
            }
            if (!Schema::hasColumn('users', 'points')) {
                $table->decimal('points', 12, 2)->default(0);
            }
            if (!Schema::hasColumn('users', 'alamat')) {
                $table->string('alamat')->nullable();
            }
            if (!Schema::hasColumn('users', 'telepon')) {
                $table->string('telepon')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'points', 'alamat', 'telepon']);
        });
    }
};
