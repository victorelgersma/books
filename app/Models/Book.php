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

    public function chapters(): HasMany
    {
        return $this->hasMany(Chapter::class);
    }

    /**
     * All quotes on this book, including ones scoped to a chapter.
     * Callers that want only the book's top-level quotes must add
     * ->whereNull('chapter_id') themselves.
     */
    public function quotes(): HasMany
    {
        return $this->hasMany(Quote::class);
    }

    /**
     * All terms defined on this book, including ones scoped to a chapter.
     * Callers that want only the book's top-level terms must add
     * ->whereNull('chapter_id') themselves.
     */
    public function terms(): HasMany
    {
        return $this->hasMany(Term::class);
    }

    /**
     * All notes on this book, including ones scoped to a chapter.
     * Callers that want only the book's top-level notes must add
     * ->whereNull('chapter_id') themselves.
     */
    public function notes(): HasMany
    {
        return $this->hasMany(Note::class);
    }
}
