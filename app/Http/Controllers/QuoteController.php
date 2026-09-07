<?php

namespace App\Http\Controllers;

use App\Models\Book;
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

        public function update(Request $request, Quote $quote): RedirectResponse
                {
                            $validated = $request->validate([
                                            'text' => ['required', 'string'],
                                                        'quote_author' => ['nullable', 'string', 'max:255'],
                                                                ]);

                                    $quote->update($validated);

                                    return redirect()->route('books.show', $quote->book);
                                        }

        public function destroy(Quote $quote): RedirectResponse
                {
                            $book = $quote->book;
                                    $quote->delete();

                                    return redirect()->route('books.show', $book);
                                        }
    }

