<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use App\Models\Term;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class TermController extends Controller
{
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'term' => ['required', 'string', 'max:255'],
            'definition' => ['required', 'string'],
        ]);

        $book->terms()->create($validated);

        return redirect()->route('books.show', $book);
    }

    public function storeForChapter(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'term' => ['required', 'string', 'max:255'],
            'definition' => ['required', 'string'],
        ]);

        $chapter->terms()->create($validated + ['book_id' => $chapter->book_id]);

        return redirect()->route('chapters.show', $chapter);
    }

    public function update(Request $request, Term $term): RedirectResponse
    {
        $validated = $request->validate([
            'term' => ['required', 'string', 'max:255'],
            'definition' => ['required', 'string'],
        ]);

        $term->update($validated);

        return redirect($term->chapter_id
            ? route('chapters.show', $term->chapter_id)
            : route('books.show', $term->book));
    }

    public function destroy(Term $term): RedirectResponse
    {
        $book = $term->book;
        $chapterId = $term->chapter_id;
        $term->delete();

        return redirect($chapterId ? route('chapters.show', $chapterId) : route('books.show', $book));
    }
}
