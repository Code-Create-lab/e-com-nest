<!DOCTYPE html>
<html lang="en" class="h-full" data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Admin Panel'))</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpeg') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script>
        (function () {
            try {
                var saved = localStorage.getItem('theme');
                var prefersDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
                var theme = saved || (prefersDark ? 'dark' : 'light');
                document.documentElement.setAttribute('data-theme', theme);
            } catch (e) {}
        })();
    </script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full antialiased">
    @auth
        @php
            $navItems = [
                ['route' => 'dashboard',         'label' => 'Dashboard', 'active' => request()->routeIs('dashboard'),       'icon' => 'grid'],
                ['route' => 'customers.index',   'label' => 'Customers', 'active' => request()->routeIs('customers.*'),     'icon' => 'users'],
                ['route' => 'leads.index',       'label' => 'Leads',     'active' => request()->routeIs('leads.*'),         'icon' => 'sparkles'],
                ['route' => 'projects.index',    'label' => 'Projects',  'active' => request()->routeIs('projects.*'),      'icon' => 'briefcase'],
                ['route' => 'invoices.index',    'label' => 'Invoices',  'active' => request()->routeIs('invoices.*'),      'icon' => 'receipt'],
            ];
            $user = auth()->user();
            $initials = collect(preg_split('/\s+/', trim($user->name)))->filter()->take(2)->map(fn ($p) => mb_strtoupper(mb_substr($p, 0, 1)))->implode('');
        @endphp

        <div class="app-shell-content relative min-h-screen lg:grid lg:grid-cols-[auto_minmax(0,1fr)]">
            <div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-black/60 backdrop-blur-sm lg:hidden"></div>

            <aside
                data-sidebar
                data-collapsed="false"
                class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full overflow-hidden transition-[transform,width] duration-300 ease-out lg:sticky lg:top-0 lg:h-screen lg:w-72 lg:translate-x-0 data-[collapsed=true]:lg:w-[5.25rem]"
            >
                <div class="sidebar-glow" aria-hidden="true"></div>
                <div class="relative flex h-full flex-col px-4 py-5">
                    {{-- Brand --}}
                    <div class="flex items-center gap-3 px-1 pb-5">
                        <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5">
                            <span class="relative inline-flex h-10 w-10 items-center justify-center overflow-hidden rounded-xl border border-[var(--border-default)] bg-[var(--bg-elevated)] shadow-sm">
                                <img src="{{ asset('logo.jpeg') }}" alt="logo" class="h-full w-full object-cover">
                            </span>
                            <div class="sidebar-brand-text leading-tight">
                                <p class="text-sm font-bold text-[var(--fg-strong)]">{{ config('app.name', 'Admin') }}</p>
                                <p class="text-[0.68rem] font-medium text-[var(--fg-muted)]">Operations workspace</p>
                            </div>
                        </a>
                        <button type="button" data-sidebar-collapse class="icon-btn ml-auto hidden h-8 w-8 lg:inline-flex" aria-label="Collapse sidebar" title="Collapse">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M15 6l-6 6 6 6"/></svg>
                        </button>
                    </div>

                    <div class="sidebar-collapse-hide px-1 pb-3">
                        <p class="text-[0.62rem] font-bold uppercase tracking-[0.18em] text-[var(--fg-faint)]">Workspace</p>
                    </div>

                    <nav class="flex-1 space-y-0.5" data-motion-reveal data-motion-stagger data-motion-variant="fade">
                        @foreach ($navItems as $item)
                            <a href="{{ route($item['route']) }}" class="sidebar-link {{ $item['active'] ? 'is-active' : '' }}">
                                <span class="sidebar-icon">
                                    @switch($item['icon'])
                                        @case('grid')
                                            <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="3" y="3" width="7" height="7" rx="1.5"/><rect x="14" y="3" width="7" height="7" rx="1.5"/><rect x="3" y="14" width="7" height="7" rx="1.5"/><rect x="14" y="14" width="7" height="7" rx="1.5"/></svg>
                                            @break
                                        @case('users')
                                            <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 21v-2a4 4 0 0 0-3-3.87M16 3.13a4 4 0 0 1 0 7.75"/></svg>
                                            @break
                                        @case('sparkles')
                                            <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3l1.6 4.4L18 9l-4.4 1.6L12 15l-1.6-4.4L6 9l4.4-1.6L12 3zM19 14l.8 2.2L22 17l-2.2.8L19 20l-.8-2.2L16 17l2.2-.8L19 14zM5 14l.7 1.9L7.6 16.6 5.7 17.3 5 19.2 4.3 17.3 2.4 16.6 4.3 15.9 5 14z"/></svg>
                                            @break
                                        @case('briefcase')
                                            <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
                                            @break
                                        @case('receipt')
                                            <svg viewBox="0 0 24 24" class="h-[18px] w-[18px]" fill="none" stroke="currentColor" stroke-width="1.8"><path stroke-linecap="round" stroke-linejoin="round" d="M4 3h16v18l-3-2-3 2-3-2-3 2-4-2V3z"/><path stroke-linecap="round" d="M8 8h8M8 12h8M8 16h5"/></svg>
                                            @break
                                    @endswitch
                                </span>
                                <span class="sidebar-label">{{ $item['label'] }}</span>
                                <span class="sidebar-tooltip">{{ $item['label'] }}</span>
                            </a>
                        @endforeach
                    </nav>

                    {{-- Footer card --}}
                    <div class="mt-4 sidebar-collapse-hide">
                        <div class="relative overflow-hidden rounded-2xl border border-[var(--border-default)] bg-[var(--bg-elevated)] p-3.5 shadow-sm">
                            <div class="pointer-events-none absolute inset-x-0 -top-12 h-24 bg-[var(--accent-1)] opacity-15 blur-2xl"></div>
                            <div class="relative flex items-center gap-3">
                                <span class="flex h-9 w-9 items-center justify-center rounded-full text-[0.72rem] font-bold shadow-sm" style="background: var(--accent-1); color: var(--accent-on);">{{ $initials ?: 'A' }}</span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-[0.82rem] font-semibold text-[var(--fg-strong)]">{{ $user->name }}</p>
                                    <p class="truncate text-[0.7rem] text-[var(--fg-muted)]">{{ $user->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Collapsed mini avatar --}}
                    <div class="mt-4 hidden items-center justify-center [[data-collapsed=true]_&]:flex">
                        <span class="flex h-9 w-9 items-center justify-center rounded-full text-[0.72rem] font-bold shadow-sm" style="background: var(--accent-1); color: var(--accent-on);" title="{{ $user->name }}">{{ $initials ?: 'A' }}</span>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen min-w-0 flex-col">
                <header data-topbar class="sticky top-0 z-20">
                    <div class="flex items-center gap-3 px-4 py-3 sm:px-6 lg:px-8">
                        <button type="button" data-sidebar-open class="icon-btn lg:hidden" aria-label="Open menu">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 6h18M3 12h18M3 18h18"/></svg>
                        </button>

                        <button type="button" data-sidebar-collapse class="icon-btn hidden lg:inline-flex" aria-label="Toggle sidebar" title="Toggle sidebar">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h10M4 18h16"/></svg>
                        </button>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="truncate text-base font-semibold text-[var(--fg-strong)] sm:text-lg">@yield('page-title', 'Dashboard')</h2>
                                <span class="hidden h-3 w-px bg-[var(--border-default)] sm:inline-block"></span>
                                <span class="hidden text-[0.72rem] font-medium text-[var(--fg-muted)] sm:inline">@yield('page-eyebrow', 'Admin Workspace')</span>
                            </div>
                        </div>

                        <label class="topbar-search hidden md:inline-flex" aria-label="Search">
                            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="7"/><path stroke-linecap="round" d="m21 21-4.3-4.3"/></svg>
                            <input type="search" placeholder="Search workspace…" data-global-search>
                            <kbd>⌘K</kbd>
                        </label>

                        <button type="button" data-theme-toggle class="icon-btn" aria-label="Toggle theme" title="Toggle theme">
                            <svg data-theme-icon="light" viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="4"/><path stroke-linecap="round" d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M4.93 19.07l1.41-1.41M17.66 6.34l1.41-1.41"/></svg>
                            <svg data-theme-icon="dark" viewBox="0 0 24 24" class="hidden h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                        </button>

                        <button type="button" class="icon-btn" aria-label="Notifications" title="Notifications">
                            <span class="relative inline-flex">
                                <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M18 8a6 6 0 1 0-12 0c0 7-3 9-3 9h18s-3-2-3-9"/><path stroke-linecap="round" d="M13.73 21a2 2 0 0 1-3.46 0"/></svg>
                                <span class="absolute right-0 top-0 h-1.5 w-1.5 rounded-full bg-[var(--tone-danger)] ring-2 ring-[var(--bg-elevated)]"></span>
                            </span>
                        </button>

                        <div class="relative" data-profile-menu>
                            <button type="button" class="flex items-center gap-2 rounded-xl border border-[var(--border-default)] bg-[var(--bg-elevated)] px-1.5 py-1.5 text-sm text-[var(--fg-default)] transition hover:border-[var(--border-strong)]" data-profile-trigger>
                                <span class="flex h-7 w-7 items-center justify-center rounded-lg text-[0.66rem] font-bold" style="background: var(--accent-1); color: var(--accent-on);">{{ $initials ?: 'A' }}</span>
                                <span class="hidden text-sm font-semibold text-[var(--fg-strong)] sm:inline">{{ $user->name }}</span>
                                <svg viewBox="0 0 24 24" class="hidden h-3 w-3 text-[var(--fg-muted)] sm:block" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/></svg>
                            </button>
                            <div class="absolute right-0 top-full z-40 mt-2 hidden w-60 origin-top-right scale-95 rounded-2xl border border-[var(--border-default)] bg-[var(--bg-elevated)] p-1.5 opacity-0 shadow-xl transition" data-profile-panel>
                                <div class="rounded-xl px-3 py-2.5">
                                    <p class="truncate text-sm font-semibold text-[var(--fg-strong)]">{{ $user->name }}</p>
                                    <p class="truncate text-xs text-[var(--fg-muted)]">{{ $user->email }}</p>
                                </div>
                                <div class="my-1 border-t border-[var(--border-soft)]"></div>
                                <a href="{{ route('dashboard') }}" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-[var(--fg-default)] hover:bg-[var(--bg-subtle)]">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 text-[var(--fg-muted)]" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"/></svg>
                                    Activity
                                </a>
                                <a href="#" class="flex items-center gap-2.5 rounded-lg px-3 py-2 text-sm text-[var(--fg-default)] hover:bg-[var(--bg-subtle)]">
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 text-[var(--fg-muted)]" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 1 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 1 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 1 1-2.83-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 1 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 1 1 2.83-2.83l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 1 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 1 1 2.83 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 1 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                                    Settings
                                </a>
                                <div class="my-1 border-t border-[var(--border-soft)]"></div>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="submit" class="flex w-full items-center gap-2.5 rounded-lg px-3 py-2 text-left text-sm text-[var(--tone-danger)] hover:bg-[var(--bg-subtle)]">
                                        <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4M16 17l5-5-5-5M21 12H9"/></svg>
                                        Sign out
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="page-enter flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    <x-admin.flash-messages />
                    @yield('content')
                </main>

                <footer class="border-t border-[var(--border-soft)] px-4 py-4 text-xs text-[var(--fg-muted)] sm:px-6 lg:px-8">
                    Crafted with Laravel · Tailwind · GSAP
                </footer>
            </div>
        </div>
    @else
        <main class="app-shell-content relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
            <div class="absolute left-[-8rem] top-[-6rem] h-64 w-64 rounded-full blur-3xl opacity-25" style="background: var(--accent-1);"></div>
            <div class="absolute bottom-[-6rem] right-[-4rem] h-72 w-72 rounded-full blur-3xl opacity-10" style="background: var(--fg-strong);"></div>

            <div class="relative z-10 w-full max-w-md" data-motion-reveal data-motion-variant="scale">
                @yield('content')
            </div>
        </main>
    @endauth

    @stack('scripts')
</body>
</html>
