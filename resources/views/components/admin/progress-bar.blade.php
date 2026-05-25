@props(['value', 'label' => 'Progress', 'showHeader' => true])

@php
    $progress = max(0, min(100, (int) $value));
@endphp

<div data-progress-bar data-progress-value="{{ $progress }}">
    @if ($showHeader)
        <div class="mb-1.5 flex items-center justify-between text-[0.7rem] font-semibold uppercase tracking-[0.16em] text-[var(--fg-muted)]">
            <span>{{ $label }}</span>
            <span class="text-[var(--fg-default)]">{{ $progress }}%</span>
        </div>
    @endif
    <div class="relative h-1.5 overflow-hidden rounded-full" style="background: var(--bg-subtle);">
        <div
            class="absolute inset-y-0 left-0 rounded-full transition-[width] duration-700 ease-out"
            style="width: {{ $progress }}%; background: var(--accent-grad); box-shadow: 0 0 12px color-mix(in oklab, var(--accent-1) 50%, transparent);"
        ></div>
    </div>
</div>
