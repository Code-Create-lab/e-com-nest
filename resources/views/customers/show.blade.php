@extends('layouts.app')

@section('title', $customer->name)
@section('page-title', 'Customer Details')
@section('page-eyebrow', 'Customer Management')

@section('content')
    <x-admin.page-header :title="$customer->name" :description="$customer->company_name ?: 'Customer profile and latest related records.'">
        @foreach ([
            ['label' => 'Live', 'url' => $customer->live_url, 'tone' => 'emerald'],
            ['label' => 'Staging', 'url' => $customer->stg_url, 'tone' => 'amber'],
            ['label' => 'Monitor', 'url' => $customer->system_monitor_url, 'tone' => 'sky'],
        ] as $env)
            @if ($env['url'])
                @php
                    $toneClass = match ($env['tone']) {
                        'emerald' => 'border-emerald-200 bg-emerald-50/70 text-emerald-700 hover:border-emerald-300',
                        'amber' => 'border-amber-200 bg-amber-50/70 text-amber-700 hover:border-amber-300',
                        default => 'border-sky-200 bg-sky-50/70 text-sky-700 hover:border-sky-300',
                    };
                    $dotClass = match ($env['tone']) {
                        'emerald' => 'bg-emerald-500',
                        'amber' => 'bg-amber-500',
                        default => 'bg-sky-500',
                    };
                @endphp
                <a href="{{ $env['url'] }}" target="_blank" rel="noopener noreferrer" data-magnetic data-magnetic-strength="0.15" class="lift-hover inline-flex items-center gap-2 rounded-2xl border {{ $toneClass }} px-3 py-2 text-xs font-semibold uppercase tracking-[0.14em] transition">
                    <span class="relative flex h-2 w-2">
                        <span class="absolute inline-flex h-full w-full animate-ping rounded-full {{ $dotClass }} opacity-60"></span>
                        <span class="relative inline-flex h-2 w-2 rounded-full {{ $dotClass }}"></span>
                    </span>
                    <span>{{ $env['label'] }}</span>
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9M5 19h7"/></svg>
                </a>
            @endif
        @endforeach
        <a href="{{ route('customers.edit', $customer) }}" data-magnetic data-magnetic-strength="0.2" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Edit customer
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_minmax(0,0.8fr)]">
        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <h2 class="text-lg font-semibold text-slate-950">Profile</h2>

            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $customer->email ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Phone</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $customer->phone ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Company</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $customer->company_name ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Created</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $customer->created_at->format('d M Y') }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Address</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $customer->address ?: 'Not provided' }}</dd>
                </div>
            </dl>

            @if ($customer->live_url || $customer->stg_url || $customer->system_monitor_url)
                <div class="mt-6 border-t border-slate-100 pt-5">
                    <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Environment URLs</p>
                    <div class="mt-4 grid gap-3 sm:grid-cols-3">
                        @foreach ([
                            ['label' => 'Live', 'url' => $customer->live_url, 'tone' => 'emerald'],
                            ['label' => 'Staging', 'url' => $customer->stg_url, 'tone' => 'amber'],
                            ['label' => 'Monitor', 'url' => $customer->system_monitor_url, 'tone' => 'sky'],
                        ] as $env)
                            @if ($env['url'])
                                @php
                                    $toneClass = match ($env['tone']) {
                                        'emerald' => 'border-emerald-200 bg-emerald-50/70 text-emerald-700',
                                        'amber' => 'border-amber-200 bg-amber-50/70 text-amber-700',
                                        default => 'border-sky-200 bg-sky-50/70 text-sky-700',
                                    };
                                @endphp
                                <a href="{{ $env['url'] }}" target="_blank" rel="noopener noreferrer" data-magnetic data-magnetic-strength="0.15" class="lift-hover group flex items-center justify-between gap-2 rounded-2xl border {{ $toneClass }} px-4 py-3 text-sm font-medium">
                                    <span class="flex flex-col">
                                        <span class="text-[0.65rem] font-semibold uppercase tracking-[0.18em] opacity-80">{{ $env['label'] }}</span>
                                        <span class="truncate text-slate-900">{{ parse_url($env['url'], PHP_URL_HOST) ?: $env['url'] }}</span>
                                    </span>
                                    <svg viewBox="0 0 24 24" class="h-4 w-4 flex-shrink-0 transition group-hover:translate-x-0.5" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path stroke-linecap="round" stroke-linejoin="round" d="M14 5h5v5M19 5l-9 9M5 19h7"/></svg>
                                </a>
                            @endif
                        @endforeach
                    </div>
                </div>
            @endif
        </section>

        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <h2 class="text-lg font-semibold text-slate-950">Quick summary</h2>

            <div class="mt-5 grid gap-4">
                <x-admin.stat-card title="Projects" :value="$customer->projects_count" hint="Total project records linked to this customer." />
                <x-admin.stat-card title="Invoices" :value="$customer->invoices_count" hint="Total invoices linked directly to this customer." tone="amber" />
            </div>
        </section>
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent projects</h2>
                    <p class="text-sm text-slate-500">Latest project work associated with this customer.</p>
                </div>
                <a href="{{ route('projects.create') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">Add project</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($customer->projects as $project)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="text-sm text-slate-500">{{ $project->start_date?->format('d M Y') ?: 'No start date' }}</p>
                            </div>
                            <x-admin.status-badge :label="$project->status->label()" :classes="$project->status->badgeClasses()" />
                        </div>
                        <div class="mt-4">
                            <x-admin.progress-bar :value="$project->progress" />
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">No projects linked yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent invoices</h2>
                    <p class="text-sm text-slate-500">Latest invoice activity for this customer.</p>
                </div>
                <a href="{{ route('invoices.create') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">Create invoice</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($customer->invoices as $invoice)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <div class="flex items-center justify-between gap-3">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $invoice->invoice_number }}</p>
                                <p class="text-sm text-slate-500">Rs {{ number_format((float) $invoice->final_amount, 2) }}</p>
                            </div>
                            <x-admin.status-badge :label="$invoice->status->label()" :classes="$invoice->status->badgeClasses()" />
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">No invoices linked yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
