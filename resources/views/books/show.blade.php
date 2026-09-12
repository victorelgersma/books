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
                        @if ($book->want_to_read)
                            · <span class="bk-badge">{{ __('Want to read') }}</span>
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
                    <label class="flex items-center gap-2 text-sm" style="color: var(--ink-soft);">
                        <input type="checkbox" name="want_to_read" value="1" class="rounded" {{ $book->want_to_read ? 'checked' : '' }}>
                        {{ __('Want to read') }}
                    </label>

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

        <div class="bk-card" x-data="{ open: false }">
            <div class="flex items-center justify-between mb-1">
                <h2 class="text-xs font-semibold uppercase tracking-wide" style="color: var(--ink-soft);">{{ __('Chapters') }}</h2>
                <button type="button" @click="open = !open" class="text-xs underline" style="color: var(--ink-soft);" x-text="open ? '{{ __('Cancel') }}' : '+ {{ __('Add') }}'"></button>
            </div>

            <template x-if="open">
                <form method="POST" action="{{ route('chapters.store', $book) }}" class="space-y-2 mb-3">
                    @csrf
                    <input type="text" name="title" required maxlength="255" placeholder="{{ __('Chapter title') }}" class="bk-input">
                    <input type="number" name="chapter_number" min="1" max="9999" placeholder="{{ __('Chapter number (optional)') }}" class="bk-input">
                    <div class="flex justify-end">
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add chapter') }}</button>
                    </div>
                </form>
            </template>

            @forelse ($chapters as $chapter)
                <a href="{{ route('chapters.show', $chapter) }}" class="bk-sidebar-link" style="padding-left: 0; padding-right: 0;">
                    <span>
                        @if ($chapter->chapter_number) {{ $chapter->chapter_number }}. @endif
                        {{ $chapter->title }}
                    </span>
                </a>
            @empty
                <p class="text-sm" style="color: var(--ink-soft);">{{ __('No chapters yet.') }}</p>
            @endforelse
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

        @if ($quotes->isNotEmpty())
            <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Quotes') }}</h2>
            @foreach ($quotes as $quote)
                <x-quote-card :quote="$quote" :book="$book" />
                        @endforeach
        @endif

            <form method="POST" action="{{ route('terms.store', $book) }}" class="bk-card" x-data="{ open: false }">
        @csrf
        <template x-if="!open">
            <button type="button" @click="open = true" class="bk-btn bk-btn-ghost">+ {{ __('Add a term') }}</button>
        </template>
        <template x-if="open">
            <div class="space-y-2">
                <input type="text" name="term" required maxlength="255" placeholder="{{ __('Term') }}" class="bk-input">
                <textarea name="definition" required rows="3" placeholder="{{ __('How this author uses the term') }}" class="bk-input" style="resize: vertical;"></textarea>
                <div class="flex justify-end gap-2">
                    <button type="button" @click="open = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                    <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add term') }}</button>
                </div>
            </div>
        </template>
    </form>

    @if ($terms->isNotEmpty())
        <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Terms') }}</h2>
        @foreach ($terms as $term)
            <x-term-card :term="$term" :book="$book" />
        @endforeach
    @endif

        @if ($unattachedNotes->isNotEmpty())
            <h2 class="text-xs font-semibold uppercase tracking-wide mt-8 mb-2" style="color: var(--ink-soft);">{{ __('Notes') }}</h2>
            @foreach ($unattachedNotes as $note)
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

        @if ($quotes->isEmpty() && $unattachedNotes->isEmpty())
            <p class="text-sm py-6" style="color: var(--ink-soft);">{{ __('Nothing added yet — start with a quote or a note above.') }}</p>
        @endif
    </div>
</x-app-layout>
