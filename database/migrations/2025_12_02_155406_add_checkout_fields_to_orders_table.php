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
        Schema::table('orders', function (Blueprint $table) {
            $table->string('alamat')->nullable()->after('status');
            $table->string('telepon')->nullable()->after('alamat');
            $table->string('payment_method')->default('pending')->after('telepon');
            $table->text('bank_account')->nullable()->after('payment_method');
            $table->string('tracking_number')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['alamat', 'telepon', 'payment_method', 'bank_account']);
        });
    }
};
