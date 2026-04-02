@props(['href', 'active' => false])

<a href="{{ $href }}"
    @class([
        'flex items-center rounded-2xl px-4 py-3 text-sm font-medium transition',
        'bg-slate-950 text-white shadow-lg shadow-slate-900/10' => $active,
        'text-slate-600 hover:bg-slate-100 hover:text-slate-950' => ! $active,
    ])>
    {{ $slot }}
</a>
