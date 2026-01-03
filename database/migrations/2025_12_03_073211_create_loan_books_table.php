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
        // This migration intentionally left blank because a more
        // complete migration exists (2025_12_03_071500_create_loan_books_table.php).
        // Keeping this file to preserve migration order but prevent duplicate creation.
        if (!Schema::hasTable('loan_books')) {
            // fallback: create minimal table to avoid errors in older environments
            Schema::create('loan_books', function (Blueprint $table) {
                $table->id();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_books');
    }
};
