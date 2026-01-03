<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\User;
use App\Models\Book;

class PopularBooksTest extends TestCase
{
    use RefreshDatabase;

    public function test_popular_books_sorted_by_books_terjual()
    {
        // Create a user to access /home
        $user = User::factory()->create();

        // Create books with different 'terjual' values
        $b1 = Book::create([
            'judul' => 'A',
            'penulis' => 'P1',
            'harga' => 10000,
            'stok' => 10,
            'status' => 'available',
            'terjual' => 5,
        ]);
        $b2 = Book::create([
            'judul' => 'B',
            'penulis' => 'P2',
            'harga' => 10000,
            'stok' => 10,
            'status' => 'available',
            'terjual' => 12,
        ]);
        $b3 = Book::create([
            'judul' => 'C',
            'penulis' => 'P3',
            'harga' => 10000,
            'stok' => 10,
            'status' => 'available',
            'terjual' => 0,
        ]);

        $this->actingAs($user);

        $response = $this->get('/home');
        $response->assertStatus(200);
        $response->assertViewHas('popularBooks');

        // Get data from the view
        $popular = $response->viewData('popularBooks');
        $this->assertGreaterThan(0, $popular->count());

        // Ensure first item has the highest terjual
        $first = $popular->first();
        $this->assertEquals($b2->id, $first->id);
        $this->assertEquals($b2->terjual, (int) ($first->purchase_count ?? 0));
    }
}
