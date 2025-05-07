<?php

namespace Tests\Unit;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationModelTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_reservation()
    {
        $reservation = Reservation::factory()->create([
            'status' => 'active',
            'description' => 'Test reservation',
        ]);

        $this->assertDatabaseHas('reservations', [
            'id' => $reservation->id,
            'user_id' => $reservation->user_id,
            'book_id' => $reservation->book_id,
            'status' => 'active',
            'description' => 'Test reservation',
        ]);
    }

    public function test_reservation_belongs_to_user()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $reservation->user);
        $this->assertEquals($user->id, $reservation->user->id);
    }

    public function test_reservation_belongs_to_book()
    {
        $book = Book::factory()->create();
        $reservation = Reservation::factory()->create(['book_id' => $book->id]);

        $this->assertInstanceOf(Book::class, $reservation->book);
        $this->assertEquals($book->id, $reservation->book->id);
    }

    public function test_reservation_status_is_valid()
    {
        $reservation = Reservation::factory()->create(['status' => 'pending']);

        $this->assertContains($reservation->status, ['pending', 'completed', 'canceled', 'active']);
    }

    public function test_reservation_has_timestamps()
    {
        $reservation = Reservation::factory()->create();

        $this->assertNotNull($reservation->reserved_at);
        $this->assertNotNull($reservation->expires_at);
        $this->assertNotNull($reservation->created_at);
        $this->assertNotNull($reservation->updated_at);
    }
}
