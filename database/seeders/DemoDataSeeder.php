<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\User;
use Illuminate\Database\Seeder;

class DemoDataSeeder extends Seeder
{
    protected string $meEmail = 'victorelgersma@fastmail.com';

    public function run(): void
    {
        User::where('email', $this->meEmail)->first()
            ?? User::factory()->create(['email' => $this->meEmail, 'name' => 'Victor']);

        $book = Book::factory()->create([
            'title' => 'Thinking, Fast and Slow',
            'author' => 'Daniel Kahneman',
            'year_published' => 2011,
            'year_read' => 2026,
            'month_read' => 3,
        ]);

        $quote1 = $book->quotes()->create(['text' => 'Nothing in life is as important as you think it is, while you are thinking about it.']);
        $quote2 = $book->quotes()->create(['text' => 'A reliable way to make people believe in falsehoods is frequent repetition.']);

        $note = $book->notes()->create(['body' => 'This ties into the focusing illusion — worth re-reading the chapter on affective forecasting.']);
        $note->quotes()->attach([$quote1->id, $quote2->id]);

        $book->notes()->create(['body' => 'Recommended by a colleague after a talk on decision fatigue.']);

        $this->command->info("Demo data seeded for {$this->meEmail}.");
    }
}

