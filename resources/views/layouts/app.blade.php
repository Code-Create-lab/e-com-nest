<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Admin Panel'))</title>
    <link rel="icon" type="image/jpeg" href="{{ asset('logo.jpeg') }}">
    <link rel="apple-touch-icon" href="{{ asset('logo.jpeg') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[var(--app-shell)] text-slate-900 antialiased">
    <div class="three-bg-layer" aria-hidden="true">
        <canvas data-three-bg="light" data-intensity="0.9" data-count="8"></canvas>
    </div>
    <div class="three-bg-mask" aria-hidden="true"></div>

    @if (auth()->check())
        <div class="app-shell-content relative min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
            <div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

            <aside data-sidebar class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full overflow-hidden border-r border-white/60 px-6 py-8 shadow-2xl shadow-slate-900/10 transition-transform duration-300 lg:sticky lg:top-0 lg:translate-x-0">
                <div class="sidebar-glow" aria-hidden="true"></div>
                <div class="relative flex h-full flex-col">
                    <div data-motion-reveal data-motion-variant="fade">
                        <a href="{{ route('dashboard') }}" class="block">
                            <img
                                src="{{ asset('logo.jpeg') }}"
                                alt="{{ config('app.name', 'Admin Panel') }}"
                                class="h-20 w-auto object-contain"
                            >
                        </a>
                        <p class="mt-3 text-sm text-slate-500">Customers, leads, projects, and invoices in one workspace.</p>
                    </div>

                    <nav class="mt-10 space-y-2" data-motion-reveal data-motion-stagger data-motion-variant="up">
                        <x-admin.sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">Customers</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">Leads</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Projects</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">Invoices</x-admin.sidebar-link>
                    </nav>

                    <div data-motion-reveal data-motion-variant="scale" data-tilt data-tilt-max="4" class="mt-auto rounded-3xl border border-slate-200 bg-gradient-to-br from-slate-950 via-slate-900 to-slate-800 px-5 py-4 text-white shadow-xl shadow-slate-900/20">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Signed In</p>
                        <p class="mt-3 text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen min-w-0 flex-col">
                <header class="sticky top-0 z-20 border-b border-white/60 bg-[rgba(245,247,251,0.82)] backdrop-blur">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-4">
                            <button type="button" data-sidebar-open data-magnetic data-magnetic-strength="0.18" class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm lg:hidden">
                                <span>Menu</span>
                            </button>

                            <div>
                                <span class="page-eyebrow-chip">@yield('page-eyebrow', 'Admin Workspace')</span>
                                <h2 class="mt-2 text-xl font-semibold text-slate-950">@yield('page-title', 'Dashboard')</h2>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" data-magnetic data-magnetic-strength="0.2" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
                                Logout
                            </button>
                        </form>
                    </div>
                </header>

                <main class="flex-1 px-4 py-6 sm:px-6 lg:px-8">
                    <x-admin.flash-messages />
                    @yield('content')
                </main>

                <footer class="border-t border-white/60 px-4 py-4 text-sm text-slate-500 sm:px-6 lg:px-8">
                    Built with Laravel 12, Blade, Tailwind CSS, and modular controllers.
                </footer>
            </div>
        </div>
    @else
        <main class="app-shell-content relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
            <div class="absolute left-[-8rem] top-[-6rem] h-64 w-64 rounded-full bg-sky-200/30 blur-3xl"></div>
            <div class="absolute bottom-[-6rem] right-[-4rem] h-72 w-72 rounded-full bg-amber-200/30 blur-3xl"></div>

            <div class="relative z-10 w-full max-w-md" data-motion-reveal data-motion-variant="scale">
                @yield('content')
            </div>
        </main>
    @endif

    @stack('scripts')
</body>
</html>
