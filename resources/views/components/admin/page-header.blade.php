@props(['title', 'description' => null])

<div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div>
        <p class="text-xs uppercase tracking-[0.35em] text-[var(--app-primary)]">Admin Panel</p>
        <h1 class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">{{ $title }}</h1>
        @if ($description)
            <p class="mt-3 max-w-2xl text-sm text-slate-500">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-3">
            {{ $slot }}
        </div>
    @endif
</div>
