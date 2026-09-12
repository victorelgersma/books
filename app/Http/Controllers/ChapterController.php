<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Chapter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChapterController extends Controller
{
    public function store(Request $request, Book $book): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chapter_number' => ['nullable', 'integer', 'min:1', 'max:9999'],
        ]);

        $chapter = $book->chapters()->create($validated);

        return redirect()->route('chapters.show', $chapter);
    }

    public function show(Chapter $chapter): View
    {
        return view('chapters.show', [
            'chapter' => $chapter,
            'book' => $chapter->book,
            'quotes' => $chapter->quotes()->with('notes')->latest()->get(),
            'unattachedNotes' => $chapter->notes()->whereDoesntHave('quotes')->latest()->get(),
        ]);
    }

    public function update(Request $request, Chapter $chapter): RedirectResponse
    {
        $validated = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'chapter_number' => ['nullable', 'integer', 'min:1', 'max:9999'],
        ]);

        $chapter->update($validated);

        return redirect()->route('chapters.show', $chapter);
    }

    public function destroy(Chapter $chapter): RedirectResponse
    {
        $book = $chapter->book;
        $chapter->delete();

        return redirect()->route('books.show', $book);
    }
}
