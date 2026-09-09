<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BookController extends Controller
{
    public function index(): View
    {
        $books = Book::orderByDesc('year_read')
            ->orderByDesc('month_read')
            ->orderBy('title')
            ->get();

        return view('books.index', ['books' => $books]);
    }


public function show(Book $book): View
{
    return view('books.show', [
        'book' => $book,
        'quotes' => $book->quotes()->with('notes')->latest()->get(),
        'unattachedNotes' => $book->notes()->whereDoesntHave('quotes')->latest()->get(),
    ]);
}

        public function store(Request $request): RedirectResponse
                {
                            $book = Book::create($this->validated($request));

                                    return redirect()->route('books.show', $book);
                                }

        public function update(Request $request, Book $book): RedirectResponse
                {
                            $book->update($this->validated($request));

                                    return redirect()->route('books.show', $book);
                                }

        public function destroy(Book $book): RedirectResponse
                {
                            $book->delete();

                                    return redirect()->route('books.index');
                                }



protected function validated(Request $request): array
{
    $request->merge(['want_to_read' => $request->boolean('want_to_read')]);

    return $request->validate([
        'title' => ['required', 'string', 'max:255'],
        'author' => ['required', 'string', 'max:255'],
        'url' => ['nullable', 'url', 'max:2048'],
        'want_to_read' => ['required', 'boolean'],
        'year_published' => ['nullable', 'integer', 'min:1000', 'max:2100'],
        'year_read' => ['nullable', 'integer', 'min:1000', 'max:2100'],
        'month_read' => ['nullable', 'integer', 'min:1', 'max:12'],
    ]);
}
    }

