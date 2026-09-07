<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\NoteController;
use App\Http\Controllers\NoteQuoteLinkController;
use App\Http\Controllers\QuoteController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect(auth()->check() ? route('books.index') : route('login'));
});

Route::middleware('auth')->group(function () {
    Route::get('/books', [BookController::class, 'index'])->name('books.index');
    Route::post('/books', [BookController::class, 'store'])->name('books.store');
    Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');
    Route::patch('/books/{book}', [BookController::class, 'update'])->name('books.update');
    Route::delete('/books/{book}', [BookController::class, 'destroy'])->name('books.destroy');

    Route::post('/books/{book}/quotes', [QuoteController::class, 'store'])->name('quotes.store');
    Route::patch('/quotes/{quote}', [QuoteController::class, 'update'])->name('quotes.update');
    Route::delete('/quotes/{quote}', [QuoteController::class, 'destroy'])->name('quotes.destroy');

    Route::get('/notes', [NoteController::class, 'index'])->name('notes.index');
    Route::post('/notes', [NoteController::class, 'store'])->name('notes.store');
    Route::patch('/notes/{note}', [NoteController::class, 'update'])->name('notes.update');
    Route::delete('/notes/{note}', [NoteController::class, 'destroy'])->name('notes.destroy');

    Route::post('/note-quote-links', [NoteQuoteLinkController::class, 'store'])->name('note-quote-links.store');
    Route::delete('/notes/{note}/quotes/{quote}', [NoteQuoteLinkController::class, 'destroy'])->name('note-quote-links.destroy');
});

require __DIR__.'/auth.php';

