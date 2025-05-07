<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BorrowingModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_borrowing()
    {
        $borrowing = Borrowing::factory()->create([
            'status' => 'active',
            'description' => 'Test borrowing',
        ]);

        $this->assertDatabaseHas('borrowings', [
            'id' => $borrowing->id,
            'user_id' => $borrowing->user_id,
            'book_id' => $borrowing->book_id,
            'status' => 'active',
            'description' => 'Test borrowing',
        ]);
    }

    public function test_borrowing_belongs_to_user()
    {
        $user = User::factory()->create();
        $borrowing = Borrowing::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $borrowing->user);
        $this->assertEquals($user->id, $borrowing->user->id);
    }

    public function test_borrowing_belongs_to_book()
    {
        $book = Book::factory()->create();
        $borrowing = Borrowing::factory()->create(['book_id' => $book->id]);

        $this->assertInstanceOf(Book::class, $borrowing->book);
        $this->assertEquals($book->id, $borrowing->book->id);
    }

    public function test_borrowing_status_is_valid()
    {
        $borrowing = Borrowing::factory()->create(['status' => 'active']);

        $this->assertContains($borrowing->status, ['pending', 'active', 'overdue', 'returned']);
    }

    public function test_borrowing_has_timestamps()
    {
        $borrowing = Borrowing::factory()->create();

        $this->assertNotNull($borrowing->borrowed_at);
        $this->assertNotNull($borrowing->due_at);
        $this->assertNotNull($borrowing->created_at);
        $this->assertNotNull($borrowing->updated_at);
    }
}
