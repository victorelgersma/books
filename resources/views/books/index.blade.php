<x-app-layout>
    <div class="max-w-2xl mx-auto px-6 sm:px-10 py-10">
        <h1 class="text-2xl font-semibold mb-6" style="color: var(--ink);">{{ __('Books') }}</h1>

        <form method="POST" action="{{ route('books.store') }}" class="bk-card" x-data="{ open: false }">
            @csrf
            <template x-if="!open">
                <button type="button" @click="open = true" class="bk-btn bk-btn-ghost">+ {{ __('Add a book') }}</button>
            </template>
            <template x-if="open">
                <div class="space-y-2">
                    <input type="text" name="title" required maxlength="255" placeholder="{{ __('Title') }}" class="bk-input">
                    <input type="text" name="author" required maxlength="255" placeholder="{{ __('Author') }}" class="bk-input">

                    <input type="url" name="url" maxlength="2048" placeholder="{{ __('Link (optional) — Goodreads, publisher page, etc.') }}" class="bk-input">
                    <div class="flex gap-2">
                        <input type="number" name="year_published" placeholder="{{ __('Year published') }}" class="bk-input">
                        <input type="number" name="year_read" placeholder="{{ __('Year read') }}" class="bk-input">
                        <select name="month_read" class="bk-input">
                            <option value="">{{ __('Month read') }}</option>
                            @foreach (range(1, 12) as $m)
                                <option value="{{ $m }}">{{ \Carbon\Carbon::create()->month($m)->format('F') }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex justify-end gap-2">
                        <button type="button" @click="open = false" class="bk-btn bk-btn-ghost">{{ __('Cancel') }}</button>
                        <button type="submit" class="bk-btn bk-btn-solid">{{ __('Add') }}</button>
                    </div>
                </div>
            </template>
        </form>

        @forelse ($books as $book)
            <a href="{{ route('books.show', $book) }}" class="bk-card block hover:opacity-80">
                <div class="flex items-baseline justify-between gap-2">
                    <span class="font-semibold" style="color: var(--ink);">{{ $book->title }}</span>
                    @if ($book->year_read)
                        <span class="text-xs shrink-0" style="color: var(--ink-soft);">
                            {{ $book->month_read ? \Carbon\Carbon::create()->month($book->month_read)->format('M') : '' }} {{ $book->year_read }}
                        </span>
                    @endif
                </div>
                <div class="text-sm" style="color: var(--ink-soft);">
                    {{ $book->author }}
                    @if ($book->year_published) · {{ $book->year_published }} @endif
                    @if ($book->url)
                        · <a href="{{ $book->url }}" target="_blank" rel="noopener" class="underline" onclick="event.stopPropagation()">{{ __('Link') }}</a>
                    @endif
                </div>
            </a>
        @empty
            <p class="text-sm py-6" style="color: var(--ink-soft);">{{ __('No books yet, add your first one above.') }}</p>
        @endforelse
    </div>
</x-app-layout>
