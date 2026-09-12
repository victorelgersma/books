{{-- resources/views/components/term-card.blade.php --}}
@props(['term', 'book', 'chapter' => null])

<div class="bk-card" x-data="{ editing: false }">
    <template x-if="!editing">
        <div>
            <div class="flex items-baseline gap-2">
                <span class="font-semibold" style="color: var(--ink);">{{ $term->term }}</span>
            </div>
            <p class="bk-note-body mt-1">{{ $term->definition }}</p>
            <div class="flex justify-end gap-3 mt-2">
                <button type="button" @click="editing = true" class="text-xs underline" style="color: var(--ink-soft);">{{ __('Edit') }}</button>
                <form method="POST" action="{{ route('terms.destroy', $term) }}">
                    @csrf @method('DELETE')
                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Delete term') }}</button>
                </form>
            </div>
        </div>
    </template>
    <template x-if="editing">
        <form method="POST" action="{{ route('terms.update', $term) }}" class="space-y-2">
            @csrf
            @method('PATCH')
            <input type="text" name="term" value="{{ $term->term }}" required maxlength="255" placeholder="{{ __('Term') }}" class="bk-input">
            <textarea name="definition" required rows="3" class="bk-input" style="resize: vertical;">{{ $term->definition }}</textarea>
            <div class="flex justify-end gap-2">
                <button type="button" @click="editing = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                <button type="submit" class="bk-btn bk-btn-solid">{{ __('Save') }}</button>
            </div>
        </form>
    </template>
</div>
