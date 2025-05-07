<?php

namespace Tests\Feature;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BookControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_books_index()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get(route('user.books.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.books.index');
        $response->assertViewHas('books', function ($books) use ($book) {
            return $books->contains($book);
        });
    }

    public function test_user_can_view_single_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create();

        $response = $this->actingAs($user)->get(route('user.books.show', $book));

        $response->assertStatus(200);
        $response->assertViewIs('user.books.show');
        $response->assertViewHas('book', $book);
    }

    public function test_admin_can_view_create_book_form()
    {
        $user = User::factory()->create(['role' => 'admin']);
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->get(route('admin.books.create'));

        $response->assertStatus(200);
        $response->assertViewIs('admin.books.create');
        $response->assertViewHas('authors', function ($authors) use ($author) {
            return $authors->contains($author);
        });
        $response->assertViewHas('categories', function ($categories) use ($category) {
            return $categories->contains($category);
        });
    }

    public function test_admin_can_store_new_book()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'admin']);
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->post(route('admin.books.store'), [
            'title' => 'Test Book',
            'isbn' => '1234567890123',
            'published_year' => 2020,
            'author_id' => $author->id,
            'category_id' => $category->id,
            'available_copies' => 5,
            'total_copies' => 5,
            'file_pdf' => UploadedFile::fake()->create('book.pdf', 100, 'application/pdf'),
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success', 'Book created successfully.');
        $this->assertDatabaseHas('books', [
            'title' => 'Test Book',
            'isbn' => '1234567890123',
        ]);
        $book = Book::where('title', 'Test Book')->first();
        Storage::disk('public')->assertExists($book->file_pdf);
    }

    public function test_admin_can_update_book()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create();
        $author = Author::factory()->create();
        $category = Category::factory()->create();

        $response = $this->actingAs($user)->put(route('admin.books.update', $book), [
            'title' => 'Updated Book',
            'isbn' => '9876543210987',
            'published_year' => 2021,
            'author_id' => $author->id,
            'category_id' => $category->id,
            'available_copies' => 3,
            'total_copies' => 3,
        ]);

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success', 'Book updated successfully.');
        $this->assertDatabaseHas('books', [
            'id' => $book->id,
            'title' => 'Updated Book',
            'isbn' => '9876543210987',
        ]);
    }

    public function test_admin_can_delete_book()
    {
        Storage::fake('public');
        $user = User::factory()->create(['role' => 'admin']);
        $book = Book::factory()->create([
            'file_pdf' => 'books/pdf/test.pdf',
        ]);
        Storage::disk('public')->put('books/pdf/test.pdf', 'test');

        $response = $this->actingAs($user)->delete(route('admin.books.destroy', $book));

        $response->assertRedirect(route('admin.books.index'));
        $response->assertSessionHas('success', 'Book deleted successfully.');
        $this->assertDatabaseMissing('books', ['id' => $book->id]);
        Storage::disk('public')->assertMissing('books/pdf/test.pdf');
    }
}
