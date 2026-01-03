<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Book;

class AddDariBalikPenjaraSeeder extends Seeder
{
    public function run()
    {
        $title = 'Dari Balik Penjara dan Pengasingan';

        // Avoid creating duplicates
        if (Book::where('judul', $title)->exists()) {
            $this->command->info("Seeder: Book '$title' already exists. Skipping.");
            return;
        }

        // Try to copy attributes from an existing book (ID 1 or first book) to keep consistency with 'buku hayo'
        $source = Book::find(1) ?: Book::first();

        if ($source) {
            $data = $source->replicate()->toArray();

            // Remove attributes we don't want copied
            unset($data['id']);
            unset($data['created_at']);
            unset($data['updated_at']);

            // Set new values specific to this book
            $data['judul'] = $title;
            $data['penulis'] = 'Badruddin';
            $data['deskripsi'] = 'Menelusuri biografi dan jejak sang revolusioner sejati Tan Malaka — kisah dari balik penjara dan pengasingan.';
            $data['harga'] = $source->harga ?? 90000;
            $data['kategori'] = $source->kategori ?? 'Biografi';
            $data['penerbit'] = $source->penerbit ?? 'Unknown';
            $data['isbn'] = $source->isbn ?? null;
            $data['halaman'] = $source->halaman ?? 240;
            $data['stok'] = $source->stok ?? 50;
            $data['status'] = $source->status ?? 'available';
            $data['terjual'] = 0;
            // Image stored in storage/app/public/book-covers/
            $data['image'] = 'book-covers/dari-balik-penjara.jpg';

            Book::create($data);
            $this->command->info("Seeder: Book '$title' created (copied from source).");
            return;
        }

        // Fallback - create with reasonable defaults
        Book::create([
            'judul' => $title,
            'penulis' => 'Badruddin',
            'deskripsi' => 'Menelusuri biografi dan jejak sang revolusioner sejati Tan Malaka — kisah dari balik penjara dan pengasingan.',
            'harga' => 90000,
            'kategori' => 'Biografi',
            'penerbit' => 'Unknown',
            'isbn' => null,
            'halaman' => 240,
            'stok' => 50,
            'status' => 'available',
            'terjual' => 0,
            'image' => 'book-covers/dari-balik-penjara.jpg',
        ]);

        $this->command->info("Seeder: Book '$title' created (fallback data).");
    }
}
