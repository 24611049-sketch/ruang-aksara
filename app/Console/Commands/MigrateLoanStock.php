<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Book;
use App\Models\LoanBook;

class MigrateLoanStock extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ra:migrate-loan-stock {--wipe : Clear loan_stok on books after migrating}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing loan_stok values from books table into loan_books table';

    public function handle()
    {
        $this->info('Starting loan stock migration...');

        $books = Book::whereNotNull('loan_stok')->get();

        if ($books->isEmpty()) {
            $this->info('No books with loan_stok found. Nothing to migrate.');
            return 0;
        }

        $bar = $this->output->createProgressBar($books->count());
        $bar->start();

        foreach ($books as $book) {
            $existing = LoanBook::where('original_book_id', $book->id)->first();

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

            if ($existing) {
                $existing->update($data);
            } else {
                LoanBook::create($data);
            }

            if ($this->option('wipe')) {
                $book->loan_stok = null;
                $book->save();
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);
        $this->info('Loan stock migration completed.');

        return 0;
    }
}
