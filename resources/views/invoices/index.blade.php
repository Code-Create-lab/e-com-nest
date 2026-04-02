@extends('layouts.app')

@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('page-eyebrow', 'Invoice Management')

@section('content')
    <x-admin.page-header title="Invoices" description="Create customer or project-linked invoices with repeatable line items and printable output.">
        <a href="{{ route('invoices.create') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Create invoice
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form method="GET" class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_16rem_16rem_auto]">
            <div>
                <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Search invoices</label>
                <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Search by invoice number, customer, or project" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
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

            <div>
                <label for="customer_id" class="mb-2 block text-sm font-medium text-slate-700">Customer</label>
                <select id="customer_id" name="customer_id" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                    <option value="">All customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}" @selected((string) $selectedCustomerId === (string) $customer->id)>{{ $customer->name }}</option>
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
            @forelse ($invoices as $invoice)
                <article class="rounded-[1.8rem] border border-slate-200 bg-[linear-gradient(180deg,_#ffffff,_#f8fbff)] p-5 shadow-lg shadow-slate-900/5">
                    <div class="flex flex-col gap-5 xl:flex-row xl:items-center xl:justify-between">
                        <div class="flex items-start gap-4">
                            <div class="flex h-16 w-16 items-center justify-center rounded-[1.25rem] bg-[linear-gradient(135deg,_#0f3d91,_#ff9f1c)] p-2 shadow-lg shadow-slate-900/10">
                                <img src="{{ asset('logo.jpeg') }}" alt="eComNest Soultions logo" class="h-full w-full rounded-[0.95rem] bg-white object-contain">
                            </div>
                            <div>
                                <div class="flex flex-wrap items-center gap-3">
                                    <h2 class="text-lg font-semibold text-slate-950">{{ $invoice->invoice_number }}</h2>
                                    <x-admin.status-badge :label="$invoice->status->label()" :classes="$invoice->status->badgeClasses()" />
                                </div>
                                <p class="mt-2 text-sm text-slate-500">{{ $invoice->issue_date?->format('d M Y') ?: 'No issue date' }} | {{ $invoice->project?->project_name ?: 'Customer-level invoice' }}</p>
                                <p class="mt-2 text-sm text-slate-700">{{ $invoice->customer?->name }}</p>
                            </div>
                        </div>

                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center">
                            <div class="rounded-2xl bg-slate-950 px-5 py-4 text-white">
                                <p class="text-xs uppercase tracking-[0.2em] text-white/60">Final Amount</p>
                                <p class="mt-2 text-2xl font-semibold">Rs {{ number_format((float) $invoice->final_amount, 2) }}</p>
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <a href="{{ route('invoices.show', $invoice) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                                    View
                                </a>
                                <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center rounded-2xl border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:border-sky-300 hover:text-sky-900">
                                    Edit
                                </a>
                                <a href="{{ route('invoices.print', $invoice) }}" class="inline-flex items-center rounded-2xl border border-amber-200 bg-amber-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-amber-700 transition hover:border-amber-300 hover:text-amber-900">
                                    Print
                                </a>
                            </div>
                        </div>
                    </div>
                </article>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-10 text-center text-slate-500">No invoices found for the current filters.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $invoices->links() }}
        </div>
    </section>
@endsection
