<x-app-layout :book="$book">
    <div class="max-w-2xl mx-auto px-6 sm:px-10 py-10">
        <div class="flex items-center justify-between gap-4 mb-1">
            <h1 class="text-2xl font-semibold truncate" style="color: var(--ink);">{{ $book->title }}</h1>
            <form method="POST" action="{{ route('books.destroy', $book) }}"
                onsubmit="return confirm('{{ __('Delete this book and everything under it?') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs" style="color: var(--ink-soft);"
                    onmouseover="this.style.color='var(--error-red)'" onmouseout="this.style.color='var(--ink-soft)'">
                    {{ __('Delete book') }}
                </button>
            </form>
        </div>

        <div class="bk-card" x-data="{ editing: false }">
            <template x-if="!editing">
                <div class="flex items-center justify-between gap-2">

                    <p class="text-sm" style="color: var(--ink-soft);">
                        {{ $book->author }}
                        @if ($book->year_published) · {{ __('published') }} {{ $book->year_published }} @endif
                        @if ($book->year_read)
                            · {{ __('read') }} {{ $book->month_read ? \Carbon\Carbon::create()->month($book->month_read)->format('F') : '' }} {{ $book->year_read }}
                        @endif
                        @if ($book->url)
                            · <a href="{{ $book->url }}" target="_blank" rel="noopener" class="underline">{{ __('Link') }}</a>
                        @endif
                    </p>
                    <button type="button" @click="editing = true" class="text-xs underline shrink-0" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                </div>
            </template>
            <template x-if="editing">
                <form method="POST" action="{{ route('books.update', $book) }}" class="space-y-2">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="title" value="{{ $book->title }}" required maxlength="255" placeholder="{{ __('Title') }}" class="bk-input">
                    <input type="text" name="author" value="{{ $book->author }}" required maxlength="255" placeholder="{{ __('Author') }}" class="bk-input">


                    <input type="url" name="url" value="{{ $book->url }}" maxlength="2048" placeholder="{{ __('Link (optional) — Goodreads, publisher page, etc.') }}" class="bk-input">
                    <div class="flex gap-2">
                        <input type="number" name="year_published" value="{{ $book->year_published }}" placeholder="{{ __('Year published') }}" class="bk-input">
                        <input type="number" name="year_read" value="{{ $book->year_read }}" placeholder="{{ __('Year read') }}" class="bk-input">
                        <select name="month_read" class="bk-input">
                            <option value="">{{ __('Month read') }}</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}" {{ $book->month_read == $m ? 'selected' : '' }}>{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                    </div>
                </form>
            </template>
        </div>


        <form method="POST" action="{{ route('quotes.store', $book) }}" class="bk-card" x-data="{ open: false }">
            @csrf
            <template x-if="!open">
                <button type="button" @click="open = true" class="bk-btn bk-btn-ghost">+ {{ __('Add a quote') }}</button>
            </template>
            <template x-if="open">
                <div class="space-y-2">
                    <textarea name="text" required rows="2" placeholder="{{ __('Quote text') }}" class="bk-input" style="resize: vertical;"></textarea>
                    <input type="text" name="quote_author" maxlength="255" placeholder="{{ __('Quote author (if different from book author)') }}" class="bk-input">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add quote') }}</button>
                    </div>
                </div>
            </template>
        </form>

        <form method="POST" action="{{ route('notes.store') }}" class="bk-card" x-data="{ open: false }">
            @csrf
            <input type="hidden" name="book_id" value="{{ $book->id }}">
            <template x-if="!open">
                <button type="button" @click="open = true" class="bk-btn bk-btn-ghost">+ {{ __('Add a note') }}</button>
            </template>
            <template x-if="open">
                <div class="space-y-2">
                    <textarea name="body" required rows="2" placeholder="{{ __('Note') }}" class="bk-input" style="resize: vertical;"></textarea>
                    <input type="url" name="link" maxlength="2048" placeholder="{{ __('Link (optional)') }}" class="bk-input">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add note') }}</button>
                    </div>
                </div>
            </template>
        </form>

        @if ($groups['linkedNotes']->isNotEmpty())
            <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Notes & their quotes') }}</h2>
            @foreach ($groups['linkedNotes'] as $note)
                <div class="bk-card" x-data="{ editing: false }">
                    <template x-if="!editing">
                        <div>
                            <p class="bk-note-body">{{ $note->body }}</p>
                            @if ($note->link)
                                <a href="{{ $note->link }}" target="_blank" rel="noopener" class="text-xs underline" style="color: var(--ink-soft);">{{ $note->link }}</a>
                            @endif

                            <div class="mt-3 space-y-2">
                                @foreach ($note->quotes as $quote)
                                    {{-- ...existing quote-editing block, unchanged... --}}
                                @endforeach
                            </div>

                            <div class="flex justify-end gap-3 mt-2">
                                <button type="button" @click="editing = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit note') }}</button>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete note') }}</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing">
                        <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="book_id" value="{{ $note->book_id }}">
                            <textarea name="body" required rows="2" class="bk-input" style="resize: vertical;">{{ $note->body }}</textarea>
                            <input type="url" name="link" value="{{ $note->link }}" maxlength="2048" placeholder="{{ __('Link (optional)') }}" class="bk-input">
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                                <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </template>
                </div>
            @endforeach
        @endif

        @if ($groups['otherQuotes']->isNotEmpty())
            <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Other quotes') }}</h2>

            @foreach ($groups['otherQuotes'] as $quote)
                <div class="bk-card" x-data="{ editing: false }">
                    <template x-if="!editing">
                        <div>
                            <div class="bk-quote">
                                “{{ $quote->text }}”
                                <div class="bk-quote-author">— {{ $quote->quote_author ?: $book->author }}</div>
                            </div>
                            <div class="flex justify-end gap-3 mt-2">
                                <button type="button" @click="editing = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                                <form method="POST" action="{{ route('quotes.destroy', $quote) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete quote') }}</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing">
                        <form method="POST" action="{{ route('quotes.update', $quote) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <textarea name="text" required rows="2" class="bk-input" style="resize: vertical;">{{ $quote->text }}</textarea>
                            <input type="text" name="quote_author" value="{{ $quote->quote_author }}" maxlength="255" placeholder="{{ __('Quote author (if different from book author)') }}" class="bk-input">
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                                <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </template>
                </div>
            @endforeach
        @endif

        @if ($groups['otherNotes']->isNotEmpty())
            <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Other notes') }}</h2>
            @foreach ($groups['otherNotes'] as $note)
                <div class="bk-card" x-data="{ editing: false }">
                    <template x-if="!editing">
                        <div>
                            <p class="bk-note-body">{{ $note->body }}</p>
                            @if ($note->link)
                                <a href="{{ $note->link }}" target="_blank" rel="noopener" class="text-xs underline" style="color: var(--ink-soft);">{{ $note->link }}</a>
                            @endif
                            <div class="flex justify-end gap-3 mt-2">
                                <button type="button" @click="editing = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit note') }}</button>
                                <form method="POST" action="{{ route('notes.destroy', $note) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete note') }}</button>
                                </form>
                            </div>
                        </div>
                    </template>
                    <template x-if="editing">
                        <form method="POST" action="{{ route('notes.update', $note) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="book_id" value="{{ $note->book_id }}">
                            <textarea name="body" required rows="2" class="bk-input" style="resize: vertical;">{{ $note->body }}</textarea>
                            <input type="url" name="link" value="{{ $note->link }}" maxlength="2048" placeholder="{{ __('Link (optional)') }}" class="bk-input">
                            <div class="flex justify-end gap-2">
                                <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                                <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                            </div>
                        </form>
                    </template>
                </div>
            @endforeach

        @endif

        @if ($groups['linkedNotes']->isEmpty() && $groups['otherQuotes']->isEmpty() && $groups['otherNotes']->isEmpty())
            <p class="text-sm py-6" style="color: var(--ink-soft);">{{ __('Nothing added yet — start with a quote or a note above.') }}</p>
        @endif
    </div>
</x-app-layout>
