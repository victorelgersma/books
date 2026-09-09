<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'author', 'url', 'want_to_read', 'year_published', 'year_read', 'month_read',
    ];

	protected function casts(): array
	{
		return [
			'want_to_read' => 'boolean',
		];
	}

    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
	

    /**
     * Linked note/quote pairs bundled together; unlinked ones flat.
     */
    public function groupedNotesAndQuotes(): array
    {
        $notes = $this->notes()->with('quotes')->get();
        $quotes = $this->quotes()->with('notes')->get();

        $linkedNoteIds = $notes->filter(fn ($note) => $note->quotes->isNotEmpty())->pluck('id');
        $linkedQuoteIds = $quotes->filter(fn ($quote) => $quote->notes->isNotEmpty())->pluck('id');

        return [
            'linkedNotes' => $notes->whereIn('id', $linkedNoteIds)->values(),
            'linkedQuotes' => $quotes->whereIn('id', $linkedQuoteIds)->values(),
            'otherNotes' => $notes->whereNotIn('id', $linkedNoteIds)->values(),
            'otherQuotes' => $quotes->whereNotIn('id', $linkedQuoteIds)->values(),
        ];
    }
}
