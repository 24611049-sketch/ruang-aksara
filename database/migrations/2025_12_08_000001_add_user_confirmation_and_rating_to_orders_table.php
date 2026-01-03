<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('delivered_at')->nullable()->after('status');
            $table->boolean('confirmed_by_user')->default(false)->after('delivered_at');
            $table->unsignedTinyInteger('user_rating')->nullable()->after('confirmed_by_user');
            $table->text('user_review')->nullable()->after('user_rating');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['delivered_at', 'confirmed_by_user', 'user_rating', 'user_review']);
        });
    }
};
