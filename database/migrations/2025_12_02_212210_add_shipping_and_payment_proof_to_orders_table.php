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
            $table->string('shipping_method')->default('jne')->after('bank_account');
            $table->decimal('shipping_cost', 10, 2)->default(0)->after('shipping_method');
            $table->string('proof_of_payment')->nullable()->after('shipping_cost');
            $table->enum('payment_status', ['pending', 'verified', 'failed'])->default('pending')->after('proof_of_payment');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_method', 'shipping_cost', 'proof_of_payment', 'payment_status']);
        });
    }
};
