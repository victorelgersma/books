<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 sm:px-10 py-10">
        <h1 class="text-2xl font-semibold mb-1" style="color: var(--ink);">{{ __('Notes') }}</h1>
        <p class="text-sm mb-8" style="color: var(--ink-soft);">
            {{ __("Notes that aren't tied to a particular book. Book notes live on the book's own page.") }}
        </p>

        <form method="POST" action="{{ route('notes.store') }}" class="bk-card" x-data="{ open: false }">
            @csrf
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

        @forelse ($notes as $note)
            <div class="bk-card">
                <p class="bk-note-body">{{ $note->body }}</p>
                @if ($note->link)
                    <a href="{{ $note->link }}" target="_blank" rel="noopener" class="text-xs underline" style="color: var(--ink-soft);">{{ $note->link }}</a>
                @endif
                @if ($note->quotes->isNotEmpty())
                    <div class="mt-3 space-y-2">
                        @foreach ($note->quotes as $quote)
                            <div class="bk-quote">
                                “{{ $quote->text }}”
                                <div class="bk-quote-author">
                                    — {{ $quote->quote_author ?: $quote->book->author }}
                                    <a href="{{ route('books.show', $quote->book) }}" class="underline">({{ $quote->book->title }})</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
                <div class="flex justify-end mt-2">
                    <form method="POST" action="{{ route('notes.destroy', $note) }}">
                        @csrf @method('DELETE')
                        <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete note') }}</button>
                    </form>
                </div>
            </div>
        @empty
            <p class="text-sm py-6" style="color: var(--ink-soft);">{{ __('No book-less notes yet.') }}</p>
        @endforelse
    </div>
</x-app-layout>
