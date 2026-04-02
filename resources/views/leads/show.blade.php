@extends('layouts.app')

@section('title', $lead->name)
@section('page-title', 'Lead Details')
@section('page-eyebrow', 'Lead Management')

@section('content')
    <x-admin.page-header :title="$lead->name" :description="'Lead source: '.$lead->source">
        @if ($lead->status->value !== 'converted')
            <form action="{{ route('leads.convert', $lead) }}" method="POST">
                @csrf
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                    Convert to customer
                </button>
            </form>
        @endif
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <div class="flex flex-wrap items-center gap-3">
            <x-admin.status-badge :label="$lead->status->label()" :classes="$lead->status->badgeClasses()" />
            @if ($lead->converted_at)
                <p class="text-sm text-slate-500">Converted on {{ $lead->converted_at->format('d M Y') }}</p>
            @endif
        </div>

        <dl class="mt-6 grid gap-5 md:grid-cols-2">
            <div>
                <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Email</dt>
                <dd class="mt-2 text-sm text-slate-900">{{ $lead->email ?: 'Not provided' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Phone</dt>
                <dd class="mt-2 text-sm text-slate-900">{{ $lead->phone ?: 'Not provided' }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Source</dt>
                <dd class="mt-2 text-sm text-slate-900">{{ $lead->source }}</dd>
            </div>
            <div>
                <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Customer</dt>
                <dd class="mt-2 text-sm text-slate-900">{{ $lead->customer?->name ?: 'Not converted yet' }}</dd>
            </div>
            <div class="md:col-span-2">
                <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Notes</dt>
                <dd class="mt-2 text-sm text-slate-900">{{ $lead->notes ?: 'No notes added.' }}</dd>
            </div>
        </dl>
    </section>
@endsection
