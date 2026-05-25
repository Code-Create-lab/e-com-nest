@props(['href', 'active' => false])

<a href="{{ $href }}" class="sidebar-link {{ $active ? 'is-active' : '' }}">
    <span class="sidebar-icon">
        <span class="block h-1.5 w-1.5 rounded-full bg-current opacity-60"></span>
    </span>
    <span class="sidebar-label">{{ $slot }}</span>
    <span class="sidebar-tooltip">{{ $slot }}</span>
</a>
