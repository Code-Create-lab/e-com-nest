@extends('layouts.app')

@section('title', 'Leads')
@section('page-title', 'Leads')
@section('page-eyebrow', 'Lead Management')

@section('content')
    <x-admin.page-header title="Leads" description="Capture, qualify, and convert leads into customers from a single searchable pipeline.">
        <a href="{{ route('leads.create') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Add lead
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form method="GET" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_16rem_auto]">
            <div>
                <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Search leads</label>
                <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Search by name, source, email, or notes" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            </div>

            <div>
                <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
                <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    <option value="">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->value }}" @selected($selectedStatus === $status->value)>{{ $status->label() }}</option>
                    @endforeach
                </select>
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                    Filter
                </button>
            </div>
        </form>

        <div class="mt-6 space-y-4">
            @forelse ($leads as $lead)
                <div class="rounded-3xl border border-slate-100 bg-slate-50/80 p-5">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-center xl:justify-between">
                        <div>
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-lg font-semibold text-slate-950">{{ $lead->name }}</h2>
                                <x-admin.status-badge :label="$lead->status->label()" :classes="$lead->status->badgeClasses()" />
                            </div>
                            <p class="mt-2 text-sm text-slate-500">{{ $lead->source }} | {{ $lead->email ?: 'No email' }} | {{ $lead->phone ?: 'No phone' }}</p>
                            @if ($lead->customer)
                                <p class="mt-2 text-sm text-emerald-700">Converted customer: {{ $lead->customer->name }}</p>
                            @endif
                        </div>

                        <div class="flex flex-wrap gap-2">
                            @if ($lead->status->value !== 'converted')
                                <form action="{{ route('leads.convert', $lead) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="inline-flex items-center rounded-2xl border border-emerald-200 bg-emerald-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-emerald-700 transition hover:border-emerald-300 hover:text-emerald-900">
                                        Convert
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('leads.show', $lead) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                                View
                            </a>
                            <a href="{{ route('leads.edit', $lead) }}" class="inline-flex items-center rounded-2xl border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:border-sky-300 hover:text-sky-900">
                                Edit
                            </a>
                            <form action="{{ route('leads.destroy', $lead) }}" method="POST" onsubmit="return confirm('Delete this lead?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-10 text-center text-slate-500">No leads found for the current filters.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $leads->links() }}
        </div>
    </section>
@endsection
