<!DOCTYPE html>
<html lang="en" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', config('app.name', 'Admin Panel'))</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-full bg-[var(--app-shell)] text-slate-900 antialiased">
    @if (auth()->check())
        <div class="relative min-h-screen lg:grid lg:grid-cols-[18rem_minmax(0,1fr)]">
            <div data-sidebar-backdrop class="fixed inset-0 z-30 hidden bg-slate-950/50 backdrop-blur-sm lg:hidden"></div>

            <aside data-sidebar class="fixed inset-y-0 left-0 z-40 w-72 -translate-x-full border-r border-white/60 bg-white/90 px-6 py-8 shadow-2xl shadow-slate-900/10 backdrop-blur transition-transform duration-300 lg:sticky lg:top-0 lg:translate-x-0">
                <div class="flex h-full flex-col">
                    <div>
                        <p class="text-xs uppercase tracking-[0.4em] text-[var(--app-primary)]">Laravel 12</p>
                        <h1 class="mt-3 text-2xl font-semibold text-slate-950">Admin Panel</h1>
                        <p class="mt-2 text-sm text-slate-500">Customers, leads, projects, and invoices in one workspace.</p>
                    </div>

                    <nav class="mt-10 space-y-2">
                        <x-admin.sidebar-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">Dashboard</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('customers.index')" :active="request()->routeIs('customers.*')">Customers</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('leads.index')" :active="request()->routeIs('leads.*')">Leads</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('projects.index')" :active="request()->routeIs('projects.*')">Projects</x-admin.sidebar-link>
                        <x-admin.sidebar-link :href="route('invoices.index')" :active="request()->routeIs('invoices.*')">Invoices</x-admin.sidebar-link>
                    </nav>

                    <div class="mt-auto rounded-3xl border border-slate-200 bg-slate-950 px-5 py-4 text-white">
                        <p class="text-xs uppercase tracking-[0.3em] text-slate-400">Signed In</p>
                        <p class="mt-3 text-sm font-semibold">{{ auth()->user()->name }}</p>
                        <p class="text-sm text-slate-400">{{ auth()->user()->email }}</p>
                    </div>
                </div>
            </aside>

            <div class="flex min-h-screen min-w-0 flex-col">
                <header class="sticky top-0 z-20 border-b border-white/60 bg-[rgba(245,247,251,0.92)] backdrop-blur">
                    <div class="flex items-center justify-between gap-4 px-4 py-4 sm:px-6 lg:px-8">
                        <div class="flex items-center gap-4">
                            <button type="button" data-sidebar-open class="inline-flex h-11 items-center justify-center rounded-2xl border border-slate-200 bg-white px-4 text-sm font-semibold text-slate-700 shadow-sm lg:hidden">
                                <span>Menu</span>
                            </button>

                            <div>
                                <p class="text-xs uppercase tracking-[0.35em] text-slate-500">@yield('page-eyebrow', 'Admin Workspace')</p>
                                <h2 class="mt-1 text-xl font-semibold text-slate-950">@yield('page-title', 'Dashboard')</h2>
                            </div>
                        </div>

                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-medium text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
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
        <main class="relative flex min-h-screen items-center justify-center overflow-hidden px-4 py-10">
            <div class="absolute inset-0 bg-[radial-gradient(circle_at_top_left,_rgba(14,165,233,0.18),_transparent_35%),radial-gradient(circle_at_bottom_right,_rgba(251,146,60,0.15),_transparent_32%),linear-gradient(180deg,_#f8fafc_0%,_#eef4ff_100%)]"></div>
            <div class="absolute left-[-8rem] top-[-6rem] h-64 w-64 rounded-full bg-sky-200/40 blur-3xl"></div>
            <div class="absolute bottom-[-6rem] right-[-4rem] h-72 w-72 rounded-full bg-amber-200/40 blur-3xl"></div>

            <div class="relative z-10 w-full max-w-md">
                @yield('content')
            </div>
        </main>
    @endif

    @stack('scripts')
</body>
</html>
