@props(['value'])

@php
    $progress = max(0, min(100, (int) $value));
@endphp

<div>
    <div class="mb-2 flex items-center justify-between text-xs font-medium uppercase tracking-[0.2em] text-slate-500">
        <span>Progress</span>
        <span>{{ $progress }}%</span>
    </div>
    <div class="h-3 rounded-full bg-slate-200">
        <div class="h-3 rounded-full bg-[linear-gradient(90deg,_#0ea5e9,_#22c55e)]" style="width: {{ $progress }}%"></div>
    </div>
</div>
