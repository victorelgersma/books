<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Quote;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuoteController extends Controller
{
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string'],
            'quote_author' => ['nullable', 'string', 'max:255'],
        ]);

        $book->quotes()->create($validated);

        return redirect()->route('books.show', $book);
    }

    public function storeForChapter(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string'],
            'quote_author' => ['nullable', 'string', 'max:255'],
        ]);

        $chapter->quotes()->create($validated + ['book_id' => $chapter->book_id]);

        return redirect()->route('chapters.show', $chapter);
    }

    public function update(Request $request, Quote $quote): RedirectResponse
    {
        $validated = $request->validate([
            'text' => ['required', 'string'],
            'quote_author' => ['nullable', 'string', 'max:255'],
        ]);

        $quote->update($validated);

        return redirect($quote->chapter_id
            ? route('chapters.show', $quote->chapter_id)
            : route('books.show', $quote->book));
    }

    public function destroy(Quote $quote): RedirectResponse
    {
        $book = $quote->book;
        $chapterId = $quote->chapter_id;
        $quote->delete();

        return redirect($chapterId ? route('chapters.show', $chapterId) : route('books.show', $book));
    }
}
