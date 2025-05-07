<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_borrowings()
    {
        $user = User::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('user.borrowings.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.borrowings.index');
        $response->assertViewHas('borrowings', function ($borrowings) use ($borrowing) {
            return $borrowings->contains($borrowing);
        });
    }

    public function test_user_can_view_single_borrowing()
    {
        $user = User::factory()->create();
        $borrowing = Borrowing::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('user.borrowings.show', $borrowing));

        $response->assertStatus(200);
        $response->assertViewIs('user.borrowings.show');
        $response->assertViewHas('borrowing', $borrowing);
    }

    public function test_user_can_view_create_borrowing_form()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('user.borrowings.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.borrowings.create');
        $response->assertViewHas('books', function ($books) use ($book) {
            return $books->contains($book);
        });
    }

    public function test_user_can_borrow_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('user.borrowings.store'), [
            'book_id' => $book->id,
            'due_at' => now()->addDays(7)->toDateTimeString(),
            'description' => 'Test borrowing',
        ]);

        $response->assertRedirect(route('user.borrowings.index'));
        $response->assertSessionHas('success', 'Book borrowed successfully.');
        $this->assertDatabaseHas('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'active',
        ]);
        $this->assertEquals(0, $book->fresh()->available_copies);
    }

    public function test_user_cannot_borrow_unavailable_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('user.borrowings.store'), [
            'book_id' => $book->id,
            'due_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('book_id', 'No available copies of this book.');
        $this->assertDatabaseMissing('borrowings', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_user_cannot_borrow_same_book_twice()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);
        Borrowing::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('user.borrowings.store'), [
            'book_id' => $book->id,
            'due_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('book_id', 'You already have this book borrowed.');
        $this->assertCount(1, Borrowing::where('user_id', $user->id)->where('book_id', $book->id)->get());
    }
}
