<?php

require __DIR__ . '/../vendor/autoload.php';

$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Book;
use App\Models\LoanBook;

echo "Starting loan stock migration script...\n";

$books = Book::whereNotNull('loan_stok')->get();
if ($books->isEmpty()) {
    echo "No books with loan_stok found.\n";
    exit(0);
}

foreach ($books as $book) {
    $data = [
        'original_book_id' => $book->id,
        'judul' => $book->judul,
        'penulis' => $book->penulis,
        'kategori' => $book->kategori,
        'penerbit' => $book->penerbit,
        'isbn' => $book->isbn,
        'loan_stok' => $book->loan_stok ?? 0,
        'halaman' => $book->halaman ?? null,
        'deskripsi' => $book->deskripsi ?? null,
        'status' => $book->status ?? 'available',
    ];

    LoanBook::updateOrCreate(['original_book_id' => $book->id], $data);

    // Wipe original field (set to 0 to respect NOT NULL constraint)
    $book->loan_stok = 0;
    $book->save();

    echo "Migrated book id={$book->id}\n";
}

echo "Loan stock migration completed.\n";
