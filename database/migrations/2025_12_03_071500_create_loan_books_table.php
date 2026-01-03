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
        if (!Schema::hasTable('loan_books')) {
            Schema::create('loan_books', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('original_book_id')->nullable()->index();
                $table->string('judul');
                $table->string('penulis')->nullable();
                $table->string('kategori')->nullable();
                $table->string('penerbit')->nullable();
                $table->string('isbn')->nullable()->index();
                $table->integer('loan_stok')->default(0);
                $table->integer('halaman')->nullable();
                $table->text('deskripsi')->nullable();
                $table->string('status')->default('available');
                $table->timestamps();
                $table->softDeletes();
            });
        } else {
            Schema::table('loan_books', function (Blueprint $table) {
                if (!Schema::hasColumn('loan_books', 'original_book_id')) {
                    $table->unsignedBigInteger('original_book_id')->nullable()->index();
                }
                if (!Schema::hasColumn('loan_books', 'judul')) {
                    $table->string('judul')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'penulis')) {
                    $table->string('penulis')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'kategori')) {
                    $table->string('kategori')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'penerbit')) {
                    $table->string('penerbit')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'isbn')) {
                    $table->string('isbn')->nullable()->index();
                }
                if (!Schema::hasColumn('loan_books', 'loan_stok')) {
                    $table->integer('loan_stok')->default(0);
                }
                if (!Schema::hasColumn('loan_books', 'halaman')) {
                    $table->integer('halaman')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'deskripsi')) {
                    $table->text('deskripsi')->nullable();
                }
                if (!Schema::hasColumn('loan_books', 'status')) {
                    $table->string('status')->default('available');
                }
                if (!Schema::hasColumn('loan_books', 'deleted_at')) {
                    $table->softDeletes();
                }
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
