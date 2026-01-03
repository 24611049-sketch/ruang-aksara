<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

class DebugController extends Controller
{
    public function detectCovers()
    {
        $books = Book::all();
        $results = [];
        
        foreach ($books as $book) {
            $path = "book-covers/{$book->image}";
            $exists = false;
            
            if ($book->image) {
                $exists = Storage::disk('public')->exists($path);
            }
            
            $results[] = [
                'id' => $book->id,
                'judul' => $book->judul,
                'image_field' => $book->image ?? 'NULL',
                'exists' => $exists,
                'kategori' => $book->kategori,
                'penulis' => $book->penulis,
            ];
        }

        return view('debug-covers', compact('results'));
    }

    public function generatePlaceholder($bookId)
    {
        $book = Book::find($bookId);
        
        if (!$book) {
            return response()->json(['error' => 'Book not found'], 404);
        }

        try {
            // Warna berdasarkan kategori
            $colors = [
                'Fiksi' => 'FF6B6B',
                'Non-Fiksi' => '4ECDC4',
                'Sains' => '45B7D1',
                'Teknologi' => '96CEB4',
                'Sejarah' => 'FFEAA7',
                'Biografi' => 'DDA0DD',
            ];

            $color = $colors[$book->kategori] ?? '95E1D3';

            // Generate SVG
            $bgColor = '#' . $color;
            $judul = substr($book->judul, 0, 30);
            $penulis = substr($book->penulis, 0, 25);

            $svg = <<<SVG
<svg width="200" height="280" xmlns="http://www.w3.org/2000/svg">
  <defs>
    <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
      <stop offset="0%" style="stop-color:{$bgColor};stop-opacity:1" />
      <stop offset="100%" style="stop-color:{$bgColor}dd;stop-opacity:1" />
    </linearGradient>
  </defs>
  <rect width="200" height="280" fill="url(#grad)"/>
  <circle cx="100" cy="60" r="35" fill="rgba(255,255,255,0.2)"/>
  <text x="100" y="50" font-size="12" font-weight="bold" text-anchor="middle" fill="white">{$book->kategori}</text>
  <text x="10" y="120" font-size="14" font-weight="bold" fill="white" font-family="Arial" text-anchor="start" xml:space="preserve">
    <tspan x="10" dy="0">{$judul}</tspan>
  </text>
  <text x="10" y="200" font-size="11" fill="rgba(255,255,255,0.9)" font-family="Arial" text-anchor="start">
    <tspan x="10" dy="0">by {$penulis}</tspan>
  </text>
  <text x="100" y="260" font-size="10" fill="rgba(255,255,255,0.7)" text-anchor="middle">ID: {$book->id}</text>
</svg>
SVG;

            // Simpan SVG ke storage
            $filename = 'placeholder_' . $bookId . '_' . time() . '.svg';
            Storage::disk('public')->put("book-covers/{$filename}", $svg);

            // Update database
            $book->update(['image' => $filename]);

            return response()->json([
                'success' => true,
                'message' => 'Placeholder generated successfully',
                'image' => $filename,
                'url' => asset('storage/book-covers/' . $filename)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function generateAllPlaceholders()
    {
        $books = Book::all();
        $generated = 0;
        $failed = 0;
        $messages = [];

        foreach ($books as $book) {
            try {
                // Skip jika sudah ada file
                if ($book->image && Storage::disk('public')->exists("book-covers/{$book->image}")) {
                    continue;
                }

                // Generate placeholder
                $response = json_decode($this->generatePlaceholder($book->id)->getContent());
                
                if ($response->success) {
                    $generated++;
                    $messages[] = "✅ {$book->judul}";
                } else {
                    $failed++;
                    $messages[] = "❌ {$book->judul}: {$response->error}";
                }
            } catch (\Exception $e) {
                $failed++;
                $messages[] = "❌ {$book->judul}: {$e->getMessage()}";
            }
        }

        return response()->json([
            'generated' => $generated,
            'failed' => $failed,
            'total' => $books->count(),
            'messages' => $messages
        ]);
    }
}
