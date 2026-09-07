<?php

namespace Database\Factories;

use App\Models\Book;
use Illuminate\Database\Eloquent\Factories\Factory;

class BookFactory extends Factory
{
    protected $model = Book::class;

    public function definition(): array
    {
        return [
            'title' => fake()->sentence(3),
            'author' => fake()->name(),
            'year_published' => fake()->numberBetween(1950, 2025),
            'year_read' => fake()->numberBetween(2020, 2026),
            'month_read' => fake()->numberBetween(1, 12),
        ];
    }
}

