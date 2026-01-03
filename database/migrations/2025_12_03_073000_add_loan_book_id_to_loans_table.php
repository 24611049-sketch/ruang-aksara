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
        Schema::table('loans', function (Blueprint $table) {
            // Add loan_book_id column (nullable for backward compatibility)
            if (!Schema::hasColumn('loans', 'loan_book_id')) {
                $table->foreignId('loan_book_id')->nullable()->constrained('loan_books')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('loans', function (Blueprint $table) {
            if (Schema::hasColumn('loans', 'loan_book_id')) {
                $table->dropForeignIdFor('LoanBook', 'loan_book_id');
                $table->dropColumn('loan_book_id');
            }
        });
    }
};
