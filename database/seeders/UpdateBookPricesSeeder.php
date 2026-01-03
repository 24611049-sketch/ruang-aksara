<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateBookPricesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Margin keuntungan berdasarkan kategori buku
        $margins = [
            'Novel' => 35,
            'Fiksi' => 35,
            'Non-Fiksi' => 30,
            'Referensi' => 25,
            'Teknik' => 30,
            'Anak-anak' => 35,
            'Biografi' => 30,
            'Sejarah' => 30,
            'Agama' => 30,
            'Seni' => 35,
        ];

        // Update semua buku
        $books = Book::all();

        foreach ($books as $book) {
            // Tentukan margin berdasarkan kategori, default 35%
            $margin = $margins[$book->kategori] ?? 35;

            // Set profit margin
            $book->profit_margin_percent = $margin;

            // Set purchase price = 65% dari harga jual (untuk margin 35%)
            // Atau: purchase_price = harga_jual × (1 - margin%)
            $book->purchase_price = $book->harga * (1 - ($margin / 100));

            $book->save();

            echo "Updated: {$book->judul} | Harga Jual: Rp {$book->harga} | Harga Beli: Rp " . round($book->purchase_price) . " | Margin: {$margin}%\n";
        }

        echo "\n✅ Semua buku berhasil diupdate dengan harga beli dan margin keuntungan!\n";
    }
}
