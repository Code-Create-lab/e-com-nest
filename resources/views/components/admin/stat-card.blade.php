@props([
    'title',
    'value',
    'hint' => null,
    'tone' => 'sky',
    'count' => null,
    'prefix' => '',
    'suffix' => '',
    'trend' => null, // optional ['dir' => 'up'|'down', 'value' => '12%']
])

@php
    $accent = match ($tone) {
        'amber', 'primary', 'yellow' => ['glow' => 'rgba(231, 200, 64, 0.30)', 'dot' => '#e7c840'],
        'emerald', 'success' => ['glow' => 'rgba(34, 197, 94, 0.22)', 'dot' => '#22c55e'],
        'rose', 'danger' => ['glow' => 'rgba(239, 68, 68, 0.22)', 'dot' => '#ef4444'],
        'violet' => ['glow' => 'rgba(231, 200, 64, 0.28)', 'dot' => '#e7c840'],
        default => ['glow' => 'rgba(96, 165, 250, 0.22)', 'dot' => '#60a5fa'],
    };
@endphp

<div
    data-motion-reveal
    data-motion-variant="up"
    data-tilt
    data-tilt-max="4"
    data-page-section
    class="stat-card-3d tilt-surface group relative overflow-hidden rounded-2xl border p-5"
    style="border-color: var(--border-default); background: var(--bg-elevated);"
>
    <div class="pointer-events-none absolute -right-16 -top-16 h-40 w-40 rounded-full" style="background: radial-gradient(circle, {{ $accent['glow'] }}, transparent 60%);"></div>

    <div class="relative flex items-center justify-between">
        <p class="text-[0.72rem] font-semibold uppercase tracking-[0.14em] text-[var(--fg-muted)]">{{ $title }}</p>
        <span class="inline-flex h-2 w-2 rounded-full" style="background: {{ $accent['dot'] }}; box-shadow: 0 0 12px {{ $accent['glow'] }};"></span>
    </div>

    <p
        class="relative mt-3 text-[1.85rem] font-semibold tracking-tight text-[var(--fg-strong)]"
        @if (! is_null($count))
            data-count-to="{{ $count }}"
            data-count-prefix="{{ $prefix }}"
            data-count-suffix="{{ $suffix }}"
        @endif
    >{{ $value }}</p>

    @if ($trend)
        <p class="relative mt-1 inline-flex items-center gap-1 text-xs font-semibold {{ $trend['dir'] === 'down' ? 'text-[var(--tone-danger)]' : 'text-[var(--tone-success)]' }}">
            <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.5">
                @if ($trend['dir'] === 'down')
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 9 6 6 6-6"/>
                @else
                    <path stroke-linecap="round" stroke-linejoin="round" d="m6 15 6-6 6 6"/>
                @endif
            </svg>
            {{ $trend['value'] }}
        </p>
    @endif

    @if ($hint)
        <p class="relative mt-2 text-xs leading-relaxed text-[var(--fg-muted)]">{{ $hint }}</p>
    @endif
</div>
