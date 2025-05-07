<?php

namespace Tests\Feature;

use App\Models\Book;
use App\Models\Reservation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReservationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_view_their_reservations()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('user.reservations.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.reservations.index');
        $response->assertViewHas('reservations', function ($reservations) use ($reservation) {
            return $reservations->contains($reservation);
        });
    }

    public function test_user_can_view_single_reservation()
    {
        $user = User::factory()->create();
        $reservation = Reservation::factory()->create([
            'user_id' => $user->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->get(route('user.reservations.show', $reservation));

        $response->assertStatus(200);
        $response->assertViewIs('user.reservations.show');
        $response->assertViewHas('reservation', $reservation);
    }

    public function test_user_can_view_create_reservation_form()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);

        $response = $this->actingAs($user)->get(route('user.reservations.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.reservations.create');
        $response->assertViewHas('books', function ($books) use ($book) {
            return $books->contains($book);
        });
    }

    public function test_user_can_create_reservation_for_available_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);

        $response = $this->actingAs($user)->post(route('user.reservations.store'), [
            'book_id' => $book->id,
            'expires_at' => now()->addDays(7)->toDateTimeString(),
            'description' => 'Test reservation',
        ]);

        $response->assertRedirect(route('user.reservations.index'));
        $response->assertSessionHas('success', 'Book successfully reserved.');
        $this->assertDatabaseHas('reservations', [
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'active',
        ]);
        $this->assertEquals(0, $book->fresh()->available_copies);
    }

    public function test_user_cannot_reserve_unavailable_book()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'unavailable',
            'available_copies' => 0,
        ]);

        $response = $this->actingAs($user)->post(route('user.reservations.store'), [
            'book_id' => $book->id,
            'expires_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('book_id', 'This book cannot be reserved.');
        $this->assertDatabaseMissing('reservations', [
            'user_id' => $user->id,
            'book_id' => $book->id,
        ]);
    }

    public function test_user_cannot_reserve_same_book_twice()
    {
        $user = User::factory()->create();
        $book = Book::factory()->create([
            'status' => 'available',
            'available_copies' => 1,
        ]);
        Reservation::factory()->create([
            'user_id' => $user->id,
            'book_id' => $book->id,
            'status' => 'active',
        ]);

        $response = $this->actingAs($user)->post(route('user.reservations.store'), [
            'book_id' => $book->id,
            'expires_at' => now()->addDays(7)->toDateTimeString(),
        ]);

        $response->assertSessionHasErrors('book_id', 'You already have a reservation for this book.');
        $this->assertCount(1, Reservation::where('user_id', $user->id)->where('book_id', $book->id)->get());
    }
}
