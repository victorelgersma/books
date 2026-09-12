<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NoteController extends Controller
{
    public function index(): View
    {
        $notes = Note::whereNull('book_id')->whereNull('chapter_id')->with('quotes')->latest()->get();

        return view('notes.index', ['notes' => $notes]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['nullable', 'exists:books,id'],
            'chapter_id' => ['nullable', 'exists:chapters,id'],
            'body' => ['required', 'string'],
            'link' => ['nullable', 'url', 'max:2048'],
            'quote_id' => ['nullable', 'exists:quotes,id'],
        ]);

        $note = Note::create([
            'book_id' => $validated['book_id'] ?? null,
            'chapter_id' => $validated['chapter_id'] ?? null,
            'body' => $validated['body'],
            'link' => $validated['link'] ?? null,
        ]);

        if (! empty($validated['quote_id'])) {
            $note->quotes()->attach($validated['quote_id']);
        }

        return $this->redirectFor($note);
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['nullable', 'exists:books,id'],
            'chapter_id' => ['nullable', 'exists:chapters,id'],
            'body' => ['required', 'string'],
            'link' => ['nullable', 'url', 'max:2048'],
        ]);

        $note->update($validated);

        return $this->redirectFor($note);
    }

    public function destroy(Note $note): RedirectResponse
    {
        $bookId = $note->book_id;
        $chapterId = $note->chapter_id;
        $note->delete();

        if ($chapterId) {
            return redirect()->route('chapters.show', $chapterId);
        }

        return redirect($bookId ? route('books.show', $bookId) : route('notes.index'));
    }

    protected function redirectFor(Note $note): RedirectResponse
    {
        if ($note->chapter_id) {
            return redirect()->route('chapters.show', $note->chapter_id);
        }

        return redirect($note->book_id ? route('books.show', $note->book_id) : route('notes.index'));
    }
}
