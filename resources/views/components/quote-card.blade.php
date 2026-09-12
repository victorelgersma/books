@props(['quote', 'book', 'chapter' => null])

<div class="bk-card" x-data="{ editingQuote: false, commenting: false }">
    <template x-if="!editingQuote">
        <div>
            <div class="bk-quote">
                “{{ $quote->text }}”
                <div class="bk-quote-author">— {{ $quote->quote_author ?: $book->author }}</div>
            </div>

            @if ($quote->notes->isNotEmpty())
                <div class="mt-3 pl-3 space-y-3" style="border-left: 2px solid var(--line);">
                    @foreach ($quote->notes as $comment)
                        <div x-data="{ editingComment: false }">
                            <template x-if="!editingComment">
                                <div>
                                    <p class="bk-note-body">{{ $comment->body }}</p>
                                    @if ($comment->link)
                                        <a href="{{ $comment->link }}" target="_blank" rel="noopener" class="text-xs underline" style="color: var(--ink-soft);">{{ $comment->link }}</a>
                                    @endif
                                    <div class="flex justify-end gap-3 mt-1">
                                        <button type="button" @click="editingComment = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                                        <form method="POST" action="{{ route('notes.destroy', $comment) }}">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete') }}</button>
                                        </form>
                                    </div>
                                </div>
                            </template>
                            <template x-if="editingComment">
                                <form method="POST" action="{{ route('notes.update', $comment) }}" class="space-y-2">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="book_id" value="{{ $book->id }}">
                                    @if ($chapter)
                                        <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                                    @endif
                                    <textarea name="body" required rows="2" class="bk-input" style="resize: vertical;">{{ $comment->body }}</textarea>
                                    <input type="url" name="link" value="{{ $comment->link }}" maxlength="2048" placeholder="{{ __('Link (optional)') }}" class="bk-input">
                                    <div class="flex justify-end gap-2">
                                        <button type="button" @click="editingComment = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
                                    </div>
                                </form>
                            </template>
                        </div>
                    @endforeach
                </div>
            @endif

            <div class="mt-2">
                <button type="button" @click="commenting = !commenting" class="text-xs underline" style="color: var(--ink-soft);">+ {{ __('Add a comment') }}</button>
                <template x-if="commenting">
                    <form method="POST" action="{{ route('notes.store') }}" class="space-y-2 mt-2">
                        @csrf
                        <input type="hidden" name="book_id" value="{{ $book->id }}">
                        @if ($chapter)
                            <input type="hidden" name="chapter_id" value="{{ $chapter->id }}">
                        @endif
                        <input type="hidden" name="quote_id" value="{{ $quote->id }}">
                        <textarea name="body" required rows="2" placeholder="{{ __('Comment') }}" class="bk-input" style="resize: vertical;"></textarea>
                        <div class="flex justify-end gap-2">
                            <button type="button" @click="commenting = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                            <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add comment') }}</button>
                        </div>
                    </form>
                </template>
            </div>

            <div class="flex justify-end gap-3 mt-3">
                <button type="button" @click="editingQuote = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                <form method="POST" action="{{ route('quotes.destroy', $quote) }}">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete quote') }}</button>
                </form>
            </div>
        </div>
    </template>
    <template x-if="editingQuote">
        <form method="POST" action="{{ route('quotes.update', $quote) }}" class="space-y-2">
            @csrf
            @method('PATCH')
            <textarea name="text" required rows="2" class="bk-input" style="resize: vertical;">{{ $quote->text }}</textarea>
            <input type="text" name="quote_author" value="{{ $quote->quote_author }}" maxlength="255" placeholder="{{ __('Quote author (if different from book author)') }}" class="bk-input">
            <div class="flex justify-end gap-2">
                <button type="button" @click="editingQuote = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
            </div>
        </form>
    </template>
</div>
