<x-app-layout :book="$book">
    <div class="max-w-2xl mx-auto px-6 sm:px-10 py-10">
        <div class="flex items-center justify-between gap-4 mb-1">
            <div>
                <a href="{{ route('books.show', $book) }}" class="text-xs underline" style="color: var(--ink-soft);">{{ $book->title }}</a>
                <h1 class="text-2xl font-semibold truncate" style="color: var(--ink);">
                    @if ($chapter->chapter_number) {{ $chapter->chapter_number }}. @endif
                    {{ $chapter->title }}
                </h1>
            </div>
            <form method="POST" action="{{ route('chapters.destroy', $chapter) }}"
                onsubmit="return confirm('{{ __('Delete this chapter and everything under it?') }}')">
                @csrf @method('DELETE')
                <button type="submit" class="text-xs" style="color: var(--ink-soft);"
                    onmouseover="this.style.color='var(--error-red)'" onmouseout="this.style.color='var(--ink-soft)'">
                    {{ __('Delete chapter') }}
                </button>
            </form>
        </div>

        <div class="bk-card" x-data="{ editing: false }">
            <template x-if="!editing">
                <div class="flex items-center justify-end">
                    <button type="button" @click="editing = true" class="text-xs underline shrink-0" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                </div>
            </template>
            <template x-if="editing">
                <form method="POST" action="{{ route('chapters.update', $chapter) }}" class="space-y-2">
                    @csrf
                    @method('PATCH')
                    <input type="text" name="title" value="{{ $chapter->title }}" required maxlength="255" placeholder="{{ __('Chapter title') }}" class="bk-input">
                    <input type="number" name="chapter_number" value="{{ $chapter->chapter_number }}" min="1" max="9999" placeholder="{{ __('Chapter number (optional)') }}" class="bk-input">
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                    </div>
                </form>
            </template>
        </div>

        <form method="POST" action="{{ route('chapters.quotes.store', $chapter) }}" class="bk-card" x-data="{ open: false }">
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
            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
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
                <x-quote-card :quote="$quote" :book="$book" :chapter="$chapter" />
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
                            <input type="hidden" name="book_id" value="{{ $book->id }}">
                            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
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
