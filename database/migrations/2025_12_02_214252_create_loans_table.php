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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('book_id')->constrained()->onDelete('cascade');
            $table->dateTime('borrowed_date'); // Tanggal pinjam
            $table->dateTime('return_date'); // Tanggal harus dikembalikan
            $table->dateTime('returned_at')->nullable(); // Tanggal benar-benar dikembalikan
            $table->enum('status', ['active', 'returned', 'overdue', 'cancelled'])->default('active');
            $table->text('notes')->nullable();
            $table->string('location')->default('offline-store'); // Lokasi peminjaman
            $table->timestamps();
            
            // Index untuk query yang sering digunakan
            $table->index(['user_id', 'status']);
            $table->index(['book_id', 'status']);
            $table->index('return_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
