<?php

namespace Database\Factories;

use App\Models\Book;
use App\Models\Term;
use Illuminate\Database\Eloquent\Factories\Factory;

class TermFactory extends Factory
{
    protected $model = Term::class;

    public function definition(): array
    {
        return [
            'book_id' => Book::factory(),
            'term' => fake()->word(),
            'definition' => fake()->sentence(15),
        ];
    }
}
