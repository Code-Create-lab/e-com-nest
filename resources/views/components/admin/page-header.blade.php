@props(['title', 'description' => null])

<div data-page-section class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
    <div class="min-w-0">
        <h1 class="text-2xl font-semibold tracking-tight text-[var(--fg-strong)] sm:text-[1.75rem]">{{ $title }}</h1>
        @if ($description)
            <p class="mt-1.5 max-w-2xl text-sm leading-relaxed text-[var(--fg-muted)]">{{ $description }}</p>
        @endif
    </div>

    @if ($slot->isNotEmpty())
        <div class="flex flex-wrap items-center gap-2">
            {{ $slot }}
        </div>
    @endif
</div>
