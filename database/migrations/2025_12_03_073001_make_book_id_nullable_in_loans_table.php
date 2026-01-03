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
        // Make book_id nullable for backward compatibility (we'll use loan_book_id for new loans)
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'book_id')) {
                $table->foreignId('book_id')->nullable()->change();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            // This won't work perfectly due to Laravel limitations, but for rollback:
            // Note: Manual rollback may be needed
        });
    }
};
