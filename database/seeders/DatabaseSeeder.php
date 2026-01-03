<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Book;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Create admin user
        User::create([
            'name' => 'Admin Ruang Aksara',
            'email' => 'admin@ruangaksara.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // Create owner user
        User::create([
            'name' => 'Owner Ruang Aksara',
            'email' => 'owner@ruangaksara.com',
            'password' => Hash::make('password'),
            'role' => 'owner',
        ]);

        // Create sample user
        User::create([
            'name' => 'Kipli Santoso',
            'email' => 'kipli@example.com',
            'password' => Hash::make('password'),
            'role' => 'user',
            'points' => 150,
        ]);

        // ===== CREATE SAMPLE BOOKS =====
        // Sesuai dengan struktur kolom yang ada
        
        Book::create([
            'judul' => 'Laskar Pelangi',
            'penulis' => 'Andrea Hirata',
            'deskripsi' => 'Novel inspiratif tentang perjuangan sekelompok anak di Belitung',
            'harga' => 85000,
            'kategori' => 'Novel',
            'penerbit' => 'Bentang Pustaka',
            'isbn' => '9789791227787',
            'halaman' => 529,
            'stok' => 50,
            'status' => 'available',
            'terjual' => 120,
            'image' => 'laskar-pelangi.jpg',
        ]);

        Book::create([
            'judul' => 'Bumi Manusia',
            'penulis' => 'Pramoedya Ananta Toer',
            'deskripsi' => 'Novel sejarah yang mengisahkan perjuangan di masa kolonial',
            'harga' => 95000,
            'kategori' => 'Novel Sejarah',
            'penerbit' => 'Lentera Dipantara',
            'isbn' => '9789799731236',
            'halaman' => 535,
            'stok' => 30,
            'status' => 'available',
            'terjual' => 85,
            'image' => 'bumi-manusia.jpg',
        ]);

        Book::create([
            'judul' => 'Filosofi Teras',
            'penulis' => 'Henry Manampiring',
            'deskripsi' => 'Buku tentang filosofi stoisisme untuk kehidupan modern',
            'harga' => 79000,
            'kategori' => 'Filsafat',
            'penerbit' => 'Kompas',
            'isbn' => '9786233463461',
            'halaman' => 346,
            'stok' => 25,
            'status' => 'available',
            'terjual' => 200,
            'image' => 'filosofi-teras.jpg',
        ]);

        Book::create([
            'judul' => 'Negeri 5 Menara',
            'penulis' => 'Ahmad Fuadi',
            'deskripsi' => 'Kisah inspiratif tentang pesantren dan impian',
            'harga' => 75000,
            'kategori' => 'Novel',
            'penerbit' => 'Gramedia Pustaka Utama',
            'isbn' => '9789792296477',
            'halaman' => 423,
            'stok' => 40,
            'status' => 'available',
            'terjual' => 150,
            'image' => 'negeri-5-menara.jpg',
        ]);

        // Tambahkan buku dengan gambar yang sudah ada
        Book::create([
            'judul' => 'Buku Special',
            'penulis' => 'Penulis Terkenal',
            'deskripsi' => 'Deskripsi buku yang menarik',
            'harga' => 99000,
            'kategori' => 'Best Seller',
            'penerbit' => 'Penerbit Unggulan',
            'isbn' => '1234567890',
            'halaman' => 300,
            'stok' => 100,
            'status' => 'available',
            'terjual' => 50,
            'image' => 'tPMI597azwiju7dzel4JaLKsDH7jMgGeFM1dbV0W.jpg', // FILE YANG SUDAH ADA
        ]);

        // Tambahkan seeder untuk "Dari Balik Penjara dan Pengasingan"
        $this->call(\Database\Seeders\AddDariBalikPenjaraSeeder::class);
    }
}