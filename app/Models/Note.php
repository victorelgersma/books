<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Note extends Model
{
    use HasFactory;

    protected $fillable = ['book_id', 'body', 'link'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function quotes(): BelongsToMany
    {
        return $this->belongsToMany(Quote::class);
    }
}

