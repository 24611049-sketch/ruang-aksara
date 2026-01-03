<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('books', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->string('penulis');
            $table->text('deskripsi');
            $table->decimal('harga', 10, 2);
            $table->string('kategori')->nullable(); // ✅ TAMBAHKAN
            $table->string('penerbit')->nullable(); // ✅ TAMBAHKAN
            $table->string('isbn')->nullable(); // ✅ TAMBAHKAN
            $table->integer('halaman');
            $table->integer('stok')->default(0);
            $table->string('status')->default('available'); // ✅ TAMBAHKAN (ganti is_published)
            $table->integer('terjual')->default(0); // ✅ TAMBAHKAN
            $table->string('image')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('books');
    }
};

