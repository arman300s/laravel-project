<?php

namespace Database\Factories;

use App\Models\Author;
use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Book>
 */
class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(3),
            'isbn' => $this->faker->unique()->isbn13(),
            'description' => $this->faker->paragraph(),
            'published_year' => $this->faker->year(),
            'author_id' => Author::factory(),
            'category_id' => Category::factory(),
            'available_copies' => $this->faker->numberBetween(0, 5),
            'total_copies' => $this->faker->numberBetween(1, 5),
            'file_pdf' => null,
            'file_docx' => null,
            'file_epub' => null,
            'status' => Book::STATUS_AVAILABLE,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
