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
        $notes = Note::whereNull('book_id')->with('quotes')->latest()->get();

        return view('notes.index', ['notes' => $notes]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['nullable', 'exists:books,id'],
            'body' => ['required', 'string'],
            'link' => ['nullable', 'url', 'max:2048'],
            'quote_id' => ['nullable', 'exists:quotes,id'],
        ]);

        $note = Note::create([
            'book_id' => $validated['book_id'] ?? null,
            'body' => $validated['body'],
            'link' => $validated['link'] ?? null,
        ]);

        if (! empty($validated['quote_id'])) {
            $note->quotes()->attach($validated['quote_id']);
        }

        return redirect($note->book_id ? route('books.show', $note->book_id) : route('notes.index'));
    }

    public function update(Request $request, Note $note): RedirectResponse
    {
        $validated = $request->validate([
            'book_id' => ['nullable', 'exists:books,id'],
            'body' => ['required', 'string'],
            'link' => ['nullable', 'url', 'max:2048'],
        ]);

        $note->update($validated);

        return redirect($note->book_id ? route('books.show', $note->book_id) : route('notes.index'));
    }

    public function destroy(Note $note): RedirectResponse
    {
        $bookId = $note->book_id;
        $note->delete();

        return redirect($bookId ? route('books.show', $bookId) : route('notes.index'));
    }
}
