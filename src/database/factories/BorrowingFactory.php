<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Borrowing>
 */
class BorrowingFactory extends Factory
{
    protected $model = Borrowing::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'book_id' => Book::factory(),
            'borrowed_at' => now(),
            'returned_at' => null,
            'due_at' => now()->addDays(14),
            'description' => $this->faker->sentence(),
            'status' => Borrowing::STATUS_ACTIVE,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
