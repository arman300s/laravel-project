<?php

namespace Tests\Unit;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BookModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_book()
    {
        $book = Book::factory()->create([
            'title' => 'Test Book',
            'isbn' => '1234567890123',
            'status' => 'available',
            'available_copies' => 5,
            'total_copies' => 5,
        ]);

        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Test Book',
            'isbn' => '1234567890123',
            'status' => 'available',
            'available_copies' => 5,
            'total_copies' => 5,
        ]);
    }

    public function test_book_belongs_to_author()
    {
        $author = Author::factory()->create();
        $book = Book::factory()->create(['author_id' => $author->id]);

        $this->assertInstanceOf(Author::class, $book->author);
        $this->assertEquals($author->id, $book->author->id);
    }

    public function test_book_belongs_to_category()
    {
        $category = Category::factory()->create();
        $book = Book::factory()->create(['category_id' => $category->id]);

        $this->assertInstanceOf(Category::class, $book->category);
        $this->assertEquals($category->id, $book->category->id);
    }

    public function test_book_status_is_valid()
    {
        $book = Book::factory()->create(['status' => 'available']);

        $this->assertContains($book->status, ['available', 'reserved', 'unavailable', 'archived', 'lost']);
    }

    public function test_book_has_timestamps()
    {
        $book = Book::factory()->create();

        $this->assertNotNull($book->created_at);
        $this->assertNotNull($book->updated_at);
    }
}
