@props([
    'title',
    'value',
    'hint' => null,
    'tone' => 'sky',
    'count' => null,
    'prefix' => '',
    'suffix' => '',
])

@php
    $toneClasses = match ($tone) {
        'amber' => 'from-amber-500/15 to-amber-100 ring-amber-200',
        'emerald' => 'from-emerald-500/15 to-emerald-100 ring-emerald-200',
        'rose' => 'from-rose-500/15 to-rose-100 ring-rose-200',
        default => 'from-sky-500/15 to-sky-100 ring-sky-200',
    };
@endphp

<div
    data-motion-reveal
    data-motion-variant="tilt"
    data-tilt
    data-tilt-max="6"
    class="stat-card-3d tilt-surface rounded-[1.75rem] border border-white/70 bg-gradient-to-br {{ $toneClasses }} p-5 shadow-lg shadow-slate-900/5 ring-1"
>
    <p class="relative text-sm font-medium text-slate-500">{{ $title }}</p>
    <p
        class="relative mt-4 text-3xl font-semibold tracking-tight text-slate-950"
        @if (! is_null($count))
            data-count-to="{{ $count }}"
            data-count-prefix="{{ $prefix }}"
            data-count-suffix="{{ $suffix }}"
        @endif
    >{{ $value }}</p>
    @if ($hint)
        <p class="relative mt-3 text-sm text-slate-500">{{ $hint }}</p>
    @endif
</div>
