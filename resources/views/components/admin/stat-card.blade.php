@props(['title', 'value', 'hint' => null, 'tone' => 'sky'])

@php
    $toneClasses = match ($tone) {
        'amber' => 'from-amber-500/15 to-amber-100 ring-amber-200',
        'emerald' => 'from-emerald-500/15 to-emerald-100 ring-emerald-200',
        'rose' => 'from-rose-500/15 to-rose-100 ring-rose-200',
        default => 'from-sky-500/15 to-sky-100 ring-sky-200',
    };
@endphp

<div class="rounded-[1.75rem] border border-white/70 bg-gradient-to-br {{ $toneClasses }} p-5 shadow-lg shadow-slate-900/5 ring-1">
    <p class="text-sm font-medium text-slate-500">{{ $title }}</p>
    <p class="mt-4 text-3xl font-semibold tracking-tight text-slate-950">{{ $value }}</p>
    @if ($hint)
        <p class="mt-3 text-sm text-slate-500">{{ $hint }}</p>
    @endif
</div>
