<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Books') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body x-data="{ sidebarOpen: false }">
    <div class="flex min-h-screen">
        <div class="sm:hidden fixed inset-x-0 top-0 z-30 flex items-center justify-between px-4 h-14"
            style="background: var(--sidebar); border-bottom: 1px solid var(--line);">
            <button type="button" @click="sidebarOpen = true" class="p-2 -ml-2" aria-label="{{ __('Open menu') }}">
                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M3 5h14M3 10h14M3 15h14" stroke="var(--ink)" stroke-width="1.5" stroke-linecap="round" />
                </svg>
            </button>
            <span class="font-semibold text-sm" style="color: var(--ink);">{{ config('app.name', 'Books') }}</span>
            <span class="w-9"></span>
        </div>

        <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" class="sm:hidden fixed inset-0 z-30" style="background: rgba(0, 0, 0, 0.3);"></div>

        <aside class="bk-sidebar w-64 shrink-0 flex flex-col p-4 fixed inset-y-0 left-0 z-40 transition-transform duration-200 sm:static sm:translate-x-0 sm:flex"
            :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="flex items-center justify-between mb-6 px-1">
                <span class="font-semibold text-sm" style="color: var(--ink);">{{ config('app.name', 'Books') }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="text-xs" style="color: var(--ink-soft);">{{ __('Log out') }}</button>
                </form>
            </div>

            <a href="{{ route('books.index') }}" @click="sidebarOpen = false"
                class="bk-sidebar-link mb-1 {{ request()->routeIs('books.index') ? 'is-active' : '' }}">
                <span>{{ __('Books') }}</span>
            </a>
            <a href="{{ route('notes.index') }}" @click="sidebarOpen = false"
                class="bk-sidebar-link mb-4 {{ request()->routeIs('notes.index') ? 'is-active' : '' }}">
                <span>{{ __('Notes (no book)') }}</span>
            </a>

            <div class="text-xs font-semibold uppercase tracking-wide px-1 mb-1" style="color: var(--ink-soft);">
                {{ __('Recent books') }}
            </div>

            <nav class="flex-1 space-y-1 overflow-y-auto">
                @foreach (($sidebarBooks ?? \App\Models\Book::orderByDesc('year_read')->orderByDesc('month_read')->limit(15)->get()) as $sidebarBook)
                    <a href="{{ route('books.show', $sidebarBook) }}" @click="sidebarOpen = false"
                        class="bk-sidebar-link {{ (isset($book) && $book?->id === $sidebarBook->id) ? 'is-active' : '' }}">
                        <span class="truncate">{{ $sidebarBook->title }}</span>
                    </a>
                @endforeach
            </nav>
        </aside>

        <main class="flex-1 min-w-0 pt-14 sm:pt-0">
            {{ $slot }}
        </main>
    </div>
</body>
</html>

