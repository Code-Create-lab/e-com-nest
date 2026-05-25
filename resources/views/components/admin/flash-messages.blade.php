@php
    $hasFlash = session('success') || session('error') || $errors->any();
@endphp

@if ($hasFlash)
    <div class="mb-5 space-y-2.5" data-motion-reveal data-motion-variant="up">
        @if (session('success'))
            <div class="flex items-start gap-3 rounded-xl border px-4 py-3 text-sm" style="background: color-mix(in oklab, var(--tone-success) 10%, var(--bg-elevated)); border-color: color-mix(in oklab, var(--tone-success) 30%, var(--border-default)); color: var(--fg-strong);">
                <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" style="color: var(--tone-success);"><path stroke-linecap="round" stroke-linejoin="round" d="m5 13 4 4L19 7"/></svg>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @if (session('error'))
            <div class="flex items-start gap-3 rounded-xl border px-4 py-3 text-sm" style="background: color-mix(in oklab, var(--tone-danger) 10%, var(--bg-elevated)); border-color: color-mix(in oklab, var(--tone-danger) 30%, var(--border-default)); color: var(--fg-strong);">
                <svg viewBox="0 0 24 24" class="mt-0.5 h-4 w-4 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" style="color: var(--tone-danger);"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M12 8v4M12 16h.01"/></svg>
                <span>{{ session('error') }}</span>
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border px-4 py-3 text-sm" style="background: color-mix(in oklab, var(--tone-warning) 10%, var(--bg-elevated)); border-color: color-mix(in oklab, var(--tone-warning) 30%, var(--border-default)); color: var(--fg-strong);">
                <div class="flex items-center gap-2 font-semibold">
                    <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5" style="color: var(--tone-warning);"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z"/></svg>
                    Please review the issues below
                </div>
                <ul class="mt-2 list-disc space-y-1 pl-6 text-[var(--fg-default)]">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
    </div>
@endif
