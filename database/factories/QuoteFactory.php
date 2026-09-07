<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    protected $model = Quote::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'text' => fake()->sentence(12),
            'quote_author' => null,
        ];
    }
}

