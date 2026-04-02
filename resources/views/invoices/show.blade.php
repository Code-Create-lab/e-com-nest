@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice Details')
@section('page-eyebrow', 'Invoice Management')

@section('content')
    @php
        $companyName = 'eComNest Soultions';
        $logoPath = asset('logo.jpeg');
    @endphp

    <x-admin.page-header :title="$invoice->invoice_number" :description="'Customer: '.($invoice->customer?->name ?: 'N/A')">
        <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            Edit invoice
        </a>
        <a href="{{ route('invoices.print', $invoice) }}" class="inline-flex items-center rounded-2xl bg-[linear-gradient(135deg,_#0f3d91,_#ff9f1c)] px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:brightness-105">
            Print invoice
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_22rem]">
        <section class="overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-xl shadow-slate-900/5">
            <div class="bg-[linear-gradient(135deg,_#0f3d91_0%,_#1d5fd0_48%,_#ff9f1c_100%)] px-6 py-6 text-white sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-20 w-20 items-center justify-center rounded-[1.5rem] bg-white/95 p-2 shadow-lg shadow-slate-950/10">
                            <img src="{{ $logoPath }}" alt="{{ $companyName }} logo" class="h-full w-full rounded-[1.1rem] object-contain">
                        </div>
                        <div>
                            <p class="text-xs uppercase tracking-[0.35em] text-white/70">Issued By</p>
                            <h2 class="mt-2 text-2xl font-semibold">{{ $companyName }}</h2>
                            <p class="mt-2 max-w-xl text-sm text-white/80">Branded invoice presentation for ecommerce services, implementation work, and digital growth retainers.</p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:w-[23rem]">
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.24em] text-white/65">Invoice No</p>
                            <p class="mt-2 text-lg font-semibold">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.24em] text-white/65">Status</p>
                            <p class="mt-2 text-lg font-semibold">{{ $invoice->status->label() }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.24em] text-white/65">Issue Date</p>
                            <p class="mt-2 text-sm font-medium">{{ $invoice->issue_date?->format('d M Y') ?: 'Not set' }}</p>
                        </div>
                        <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-4 backdrop-blur">
                            <p class="text-xs uppercase tracking-[0.24em] text-white/65">Due Date</p>
                            <p class="mt-2 text-sm font-medium">{{ $invoice->due_date?->format('d M Y') ?: 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6 sm:px-8">
                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-slate-500">From</p>
                        <p class="mt-3 text-xl font-semibold text-slate-950">{{ $companyName }}</p>
                        <p class="mt-2 text-sm text-slate-600">Digital commerce services and delivery operations</p>
                        <p class="mt-1 text-sm text-slate-600">Brand support through the eComNest admin workspace</p>
                    </div>

                    <div class="rounded-[1.5rem] border border-orange-100 bg-orange-50/70 px-5 py-5">
                        <p class="text-xs uppercase tracking-[0.25em] text-orange-700">Bill To</p>
                        <p class="mt-3 text-xl font-semibold text-slate-950">{{ $invoice->customer?->name ?: 'N/A' }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $invoice->customer?->company_name ?: 'No company name provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->email ?: 'No email provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->phone ?: 'No phone provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->address ?: 'No address provided' }}</p>
                    </div>
                </div>

                <div class="mt-4 rounded-[1.5rem] border border-sky-100 bg-sky-50/70 px-5 py-4">
                    <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="text-xs uppercase tracking-[0.25em] text-sky-700">Project Link</p>
                            <p class="mt-2 text-base font-semibold text-slate-950">{{ $invoice->project?->project_name ?: 'Customer-level invoice' }}</p>
                        </div>
                        <x-admin.status-badge :label="$invoice->status->label()" :classes="$invoice->status->badgeClasses()" />
                    </div>
                </div>

                <div class="mt-6 overflow-hidden rounded-[1.75rem] border border-slate-200">
                    <div class="bg-slate-950 px-5 py-4 text-white">
                        <div class="grid grid-cols-[minmax(0,1.5fr)_100px_120px_120px] gap-4 text-xs uppercase tracking-[0.24em] text-slate-300">
                            <span>Item</span>
                            <span>Qty</span>
                            <span>Price</span>
                            <span>Total</span>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-200 bg-white">
                        @foreach ($invoice->items as $item)
                            <div class="grid grid-cols-1 gap-3 px-5 py-5 text-sm sm:grid-cols-[minmax(0,1.5fr)_100px_120px_120px] sm:items-center sm:gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $item->name }}</p>
                                    <p class="mt-1 text-xs uppercase tracking-[0.18em] text-slate-400">Invoice line item</p>
                                </div>
                                <div class="text-slate-600">{{ $item->quantity }}</div>
                                <div class="font-medium text-slate-700">Rs {{ number_format((float) $item->price, 2) }}</div>
                                <div class="font-semibold text-slate-950">Rs {{ number_format((float) $item->total, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="mt-6 rounded-[1.5rem] border border-slate-200 bg-slate-50 px-5 py-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Notes</p>
                    <p class="mt-3 text-sm leading-7 text-slate-700">{{ $invoice->notes ?: 'No notes added for this invoice.' }}</p>
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="overflow-hidden rounded-[2rem] border border-white/70 bg-white shadow-xl shadow-slate-900/5">
                <div class="bg-[linear-gradient(180deg,_#fff7ed,_#ffffff)] px-6 py-5">
                    <p class="text-xs uppercase tracking-[0.25em] text-orange-600">Invoice Summary</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-950">Rs {{ number_format((float) $invoice->final_amount, 2) }}</h2>
                    <p class="mt-2 text-sm text-slate-500">Final payable amount for this invoice.</p>
                </div>

                <div class="space-y-4 px-6 py-6">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-600">Subtotal</span>
                            <span class="text-lg font-semibold text-slate-950">Rs {{ number_format((float) $invoice->subtotal_amount, 2) }}</span>
                        </div>
                    </div>

                    <div class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-4">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-slate-600">Discount</span>
                            <span class="text-lg font-semibold text-slate-950">Rs {{ number_format((float) $invoice->discount, 2) }}</span>
                        </div>
                    </div>

                    <div class="rounded-2xl bg-[linear-gradient(135deg,_#0f3d91,_#ff9f1c)] px-4 py-4 text-white">
                        <div class="flex items-center justify-between gap-3">
                            <span class="text-sm text-white/80">Final Amount</span>
                            <span class="text-2xl font-semibold">Rs {{ number_format((float) $invoice->final_amount, 2) }}</span>
                        </div>
                    </div>
                </div>
            </section>

            <section class="rounded-[2rem] border border-white/70 bg-white px-6 py-6 shadow-xl shadow-slate-900/5">
                <p class="text-xs uppercase tracking-[0.25em] text-slate-500">Quick Actions</p>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('invoices.print', $invoice) }}" class="inline-flex w-full items-center justify-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white transition hover:bg-slate-800">
                        Open print layout
                    </a>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex w-full items-center justify-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                        Edit invoice details
                    </a>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-2xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                            Delete invoice
                        </button>
                    </form>
                </div>
            </section>
        </aside>
    </div>
@endsection
