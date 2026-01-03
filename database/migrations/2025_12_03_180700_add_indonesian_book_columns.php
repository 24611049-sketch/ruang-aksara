<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::table('books', function (Blueprint $table) {
            if (!Schema::hasColumn('books', 'judul')) {
                $table->string('judul')->nullable()->after('title');
            }
            if (!Schema::hasColumn('books', 'penulis')) {
                $table->string('penulis')->nullable()->after('judul');
            }
            if (!Schema::hasColumn('books', 'deskripsi')) {
                $table->text('deskripsi')->nullable()->after('penulis');
            }
            if (!Schema::hasColumn('books', 'harga')) {
                $table->decimal('harga', 10, 2)->nullable()->after('deskripsi');
            }
            if (!Schema::hasColumn('books', 'kategori')) {
                $table->string('kategori')->nullable()->after('harga');
            }
            if (!Schema::hasColumn('books', 'penerbit')) {
                $table->string('penerbit')->nullable()->after('kategori');
            }
            if (!Schema::hasColumn('books', 'halaman')) {
                $table->integer('halaman')->nullable()->after('penerbit');
            }
            if (!Schema::hasColumn('books', 'stok')) {
                $table->integer('stok')->nullable()->after('halaman');
            }
            if (!Schema::hasColumn('books', 'terjual')) {
                $table->integer('terjual')->nullable()->after('stok');
            }
        });

        // Copy data from English columns to Indonesian columns for existing rows
        DB::table('books')->get()->each(function ($row) {
            DB::table('books')->where('id', $row->id)->update([
                'judul' => $row->title ?? null,
                'penulis' => $row->author ?? null,
                'deskripsi' => $row->description ?? null,
                'harga' => $row->price ?? null,
                'kategori' => $row->category ?? null,
                'penerbit' => $row->publisher ?? null,
                'halaman' => $row->pages ?? null,
                'stok' => $row->stock ?? null,
                'terjual' => $row->sold_count ?? null,
            ]);
        });
    }

    public function down()
    {
        Schema::table('books', function (Blueprint $table) {
            if (Schema::hasColumn('books', 'terjual')) {
                $table->dropColumn('terjual');
            }
            if (Schema::hasColumn('books', 'stok')) {
                $table->dropColumn('stok');
            }
            if (Schema::hasColumn('books', 'halaman')) {
                $table->dropColumn('halaman');
            }
            if (Schema::hasColumn('books', 'penerbit')) {
                $table->dropColumn('penerbit');
            }
            if (Schema::hasColumn('books', 'kategori')) {
                $table->dropColumn('kategori');
            }
            if (Schema::hasColumn('books', 'harga')) {
                $table->dropColumn('harga');
            }
            if (Schema::hasColumn('books', 'deskripsi')) {
                $table->dropColumn('deskripsi');
            }
            if (Schema::hasColumn('books', 'penulis')) {
                $table->dropColumn('penulis');
            }
            if (Schema::hasColumn('books', 'judul')) {
                $table->dropColumn('judul');
            }
        });
    }
};
