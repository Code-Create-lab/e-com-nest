@extends('layouts.app')

@section('title', $lead->name)
@section('page-title', 'Lead Details')
@section('page-eyebrow', 'Lead Management')

@section('content')
    <x-admin.page-header :title="$lead->name" :description="'Lead source: '.$lead->source.($lead->source_handle ? ' ('.$lead->source_handle.')' : '')">
        <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            Edit lead
        </a>
        @if ($lead->status->value !== 'converted')
            <form action="{{ route('leads.convert', $lead) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                    Convert to customer
                </button>
            </form>
        @endif
    </x-admin.page-header>

    {{-- Status & scoring overview --}}
    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <div class="flex flex-wrap items-center gap-3">
            <x-admin.status-badge :label="$lead->status->label()" :classes="$lead->status->badgeClasses()" />
            @if ($lead->best_pick)
                <span class="inline-flex items-center gap-1 rounded-full bg-yellow-50 px-3 py-1 text-xs font-semibold text-yellow-700 ring-1 ring-inset ring-yellow-200">
                    ★ Best pick
                </span>
            @endif
            @if ($lead->converted_at)
                <p class="text-sm text-slate-500">Converted on {{ $lead->converted_at->format('d M Y') }}</p>
            @endif
        </div>

        @if ($lead->lead_score !== null)
            <div class="mt-6">
                <div class="flex items-center justify-between">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Lead score</p>
                    <p class="text-sm font-semibold {{ $lead->lead_score >= 80 ? 'text-emerald-600' : ($lead->lead_score >= 50 ? 'text-amber-600' : 'text-slate-600') }}">{{ $lead->lead_score }}/100</p>
                </div>
                <div class="mt-2 h-2 w-full overflow-hidden rounded-full bg-slate-100">
                    <div class="h-full rounded-full {{ $lead->lead_score >= 80 ? 'bg-emerald-500' : ($lead->lead_score >= 50 ? 'bg-amber-500' : 'bg-slate-400') }}" style="width: {{ min($lead->lead_score, 100) }}%"></div>
                </div>
                @if ($lead->score_reason)
                    <p class="mt-3 text-sm text-slate-600">{{ $lead->score_reason }}</p>
                @endif
            </div>
        @endif
    </section>

    <div class="mt-6 grid gap-6 lg:grid-cols-2">
        {{-- Contact & company --}}
        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Contact &amp; Company</h2>
            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->email ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Phone</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->phone ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Website</dt>
                    <dd class="mt-2 text-sm text-slate-900">
                        @if ($lead->website)
                            <a href="{{ $lead->website }}" target="_blank" rel="noopener noreferrer" class="break-all font-medium text-sky-600 transition hover:text-sky-800">{{ $lead->website }}</a>
                        @else
                            No website
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">City</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->city ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Industry</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->industry ?: 'Not provided' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Customer</dt>
                    <dd class="mt-2 text-sm text-slate-900">
                        @if ($lead->customer)
                            <a href="{{ route('customers.show', $lead->customer) }}" class="font-medium text-sky-600 transition hover:text-sky-800">{{ $lead->customer->name }}</a>
                        @else
                            Not converted yet
                        @endif
                    </dd>
                </div>
            </dl>
        </section>

        {{-- Social profile --}}
        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Social Profile</h2>
            <dl class="mt-5 grid gap-5 sm:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Source</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->source }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Handle</dt>
                    <dd class="mt-2 text-sm text-slate-900">
                        @if ($lead->source_handle && str_contains(strtolower($lead->source), 'instagram'))
                            <a href="https://instagram.com/{{ ltrim($lead->source_handle, '@') }}" target="_blank" rel="noopener noreferrer" class="font-medium text-indigo-600 hover:text-indigo-800 hover:underline">{{ $lead->source_handle }}</a>
                        @else
                            {{ $lead->source_handle ?: 'Not provided' }}
                        @endif
                    </dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Followers</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $lead->followers !== null ? number_format($lead->followers) : 'Unknown' }}</dd>
                </div>
                <div class="sm:col-span-2">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Bio</dt>
                    <dd class="mt-2 whitespace-pre-line text-sm text-slate-900">{{ $lead->bio ?: 'No bio captured.' }}</dd>
                </div>
            </dl>
        </section>
    </div>

    {{-- Website audit --}}
    @php
        $hasAuditData = $lead->audit_score !== null
            || $lead->page_speed_ms !== null
            || $lead->audit_issues
            || $lead->audit_summary
            || $lead->has_ssl !== null;
    @endphp
    @if ($hasAuditData)
        <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex flex-wrap items-center justify-between gap-3">
                <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Website Audit</h2>
                <div class="flex flex-wrap items-center gap-4">
                    @if ($lead->audit_score !== null)
                        <p class="text-sm text-slate-600">Audit score: <span class="font-semibold text-slate-900">{{ $lead->audit_score }}/100</span></p>
                    @endif
                    @if ($lead->page_speed_ms !== null)
                        <p class="text-sm text-slate-600">Page speed: <span class="font-semibold text-slate-900">{{ number_format($lead->page_speed_ms) }} ms</span></p>
                    @endif
                </div>
            </div>

            <div class="mt-5 flex flex-wrap gap-2">
                @foreach ([
                    'SSL' => $lead->has_ssl,
                    'Mobile friendly' => $lead->mobile_friendly,
                    'Contact form' => $lead->has_contact_form,
                    'WhatsApp' => $lead->has_whatsapp,
                    'E-commerce' => $lead->is_ecommerce,
                ] as $label => $flag)
                    <span class="inline-flex items-center gap-1.5 rounded-full px-3 py-1.5 text-xs font-semibold ring-1 ring-inset {{ $flag ? 'bg-emerald-50 text-emerald-700 ring-emerald-200' : 'bg-slate-100 text-slate-500 ring-slate-200' }}">
                        {{ $flag ? '✓' : '✕' }} {{ $label }}
                    </span>
                @endforeach
            </div>

            @if ($lead->audit_issues)
                <div class="mt-5">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Issues</p>
                    <p class="mt-2 whitespace-pre-line text-sm text-slate-900">{{ $lead->audit_issues }}</p>
                </div>
            @endif

            @if ($lead->audit_summary)
                <div class="mt-5 rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                    <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Summary</p>
                    <p class="mt-2 text-sm leading-relaxed text-slate-700">{{ $lead->audit_summary }}</p>
                </div>
            @endif
        </section>
    @endif

    {{-- Notes & meta --}}
    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <h2 class="text-sm font-semibold uppercase tracking-[0.2em] text-slate-500">Notes</h2>
        <p class="mt-4 whitespace-pre-line text-sm text-slate-900">{{ $lead->notes ?: 'No notes added.' }}</p>

        <div class="mt-6 flex flex-wrap gap-6 border-t border-slate-100 pt-4 text-xs text-slate-500">
            <p>Added on {{ $lead->created_at->format('d M Y, H:i') }}</p>
            <p>Last updated {{ $lead->updated_at->format('d M Y, H:i') }}</p>
        </div>
    </section>
@endsection
