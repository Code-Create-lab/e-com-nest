@extends('layouts.app')

@section('title', $invoice->invoice_number)
@section('page-title', 'Invoice Details')
@section('page-eyebrow', 'Invoice Management')

@section('content')
    @php
        $companyName = '10xCart';
        $logoPath = asset('logo.jpeg');
        $navy = '#1e3a5f';
    @endphp

    <x-admin.page-header :title="$invoice->invoice_number" :description="'Customer: '.($invoice->customer?->name ?: 'N/A')">
        <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            Edit invoice
        </a>
        <a href="{{ route('invoices.print', $invoice) }}" class="inline-flex items-center gap-2 rounded-xl bg-[#1e3a5f] px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-[#16314f]">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect x="6" y="14" width="12" height="8"/></svg>
            Print invoice
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.2fr)_22rem]">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
            {{-- header band --}}
            <div class="bg-[#1e3a5f] px-6 py-6 text-white sm:px-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                    <div class="flex items-center gap-4">
                        <div class="flex h-24 w-24 items-center justify-center rounded-xl bg-white p-1.5">
                            <img src="{{ $logoPath }}" alt="{{ $companyName }} logo" class="h-full w-full rounded-lg object-contain">
                        </div>
                        <div>
                            <p class="text-[11px] font-medium uppercase tracking-[0.3em] text-slate-300">Issued By</p>
                            <h2 class="mt-1.5 text-2xl font-semibold">{{ $companyName }}</h2>
                            <p class="mt-1 max-w-md text-sm text-slate-300">Digital commerce services &amp; implementation</p>
                        </div>
                    </div>

                    <div class="grid gap-3 sm:grid-cols-2 lg:w-[23rem]">
                        <div class="rounded-lg border border-white/10 bg-white/[0.07] px-4 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-300">Invoice No</p>
                            <p class="mt-1.5 text-base font-semibold">{{ $invoice->invoice_number }}</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/[0.07] px-4 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-300">Status</p>
                            <p class="mt-1.5 text-base font-semibold">{{ $invoice->status->label() }}</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/[0.07] px-4 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-300">Issue Date</p>
                            <p class="mt-1.5 text-sm font-medium">{{ $invoice->issue_date?->format('d M Y') ?: 'Not set' }}</p>
                        </div>
                        <div class="rounded-lg border border-white/10 bg-white/[0.07] px-4 py-3">
                            <p class="text-[10px] font-medium uppercase tracking-[0.2em] text-slate-300">Due Date</p>
                            <p class="mt-1.5 text-sm font-medium">{{ $invoice->due_date?->format('d M Y') ?: 'Not set' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="px-6 py-6 sm:px-8">
                {{-- parties --}}
                <div class="grid gap-4 lg:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#1e3a5f]">From</p>
                        <p class="mt-3 text-lg font-semibold text-slate-950">{{ $companyName }}</p>
                        <p class="mt-2 text-sm text-slate-600">Digital commerce services and delivery operations</p>
                        <p class="mt-1 text-sm text-slate-600">Brand support through the 10xCart admin workspace</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 px-5 py-5">
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-[#1e3a5f]">Bill To</p>
                        <p class="mt-3 text-lg font-semibold text-slate-950">{{ $invoice->customer?->name ?: 'N/A' }}</p>
                        <p class="mt-2 text-sm text-slate-600">{{ $invoice->customer?->company_name ?: 'No company name provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->email ?: 'No email provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->phone ?: 'No phone provided' }}</p>
                        <p class="mt-1 text-sm text-slate-600">{{ $invoice->customer?->address ?: 'No address provided' }}</p>
                    </div>
                </div>

                {{-- project link --}}
                <div class="mt-4 flex flex-col gap-2 rounded-xl border border-slate-200 bg-white px-5 py-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Project Link</p>
                        <p class="mt-1.5 text-base font-semibold text-slate-950">{{ $invoice->project?->project_name ?: 'Customer-level invoice' }}</p>
                    </div>
                    <x-admin.status-badge :label="$invoice->status->label()" :classes="$invoice->status->badgeClasses()" />
                </div>

                {{-- items --}}
                <div class="mt-6 overflow-hidden rounded-xl border border-slate-200">
                    <div class="bg-[#1e3a5f] px-5 py-3.5 text-white">
                        <div class="grid grid-cols-[minmax(0,1.5fr)_80px_120px_120px] gap-4 text-[11px] font-medium uppercase tracking-[0.16em] text-slate-200">
                            <span>Item</span>
                            <span>Qty</span>
                            <span>Price</span>
                            <span class="text-right">Total</span>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-200 bg-white">
                        @foreach ($invoice->items as $item)
                            <div class="grid grid-cols-1 gap-3 px-5 py-4 text-sm odd:bg-white even:bg-slate-50/60 sm:grid-cols-[minmax(0,1.5fr)_80px_120px_120px] sm:items-center sm:gap-4">
                                <div>
                                    <p class="font-semibold text-slate-950">{{ $item->name }}</p>
                                    <p class="mt-0.5 text-xs text-slate-500">Invoice line item</p>
                                </div>
                                <div class="text-slate-600">{{ $item->quantity }}</div>
                                <div class="font-medium text-slate-700 tabular-nums">Rs {{ number_format((float) $item->price, 2) }}</div>
                                <div class="font-semibold text-slate-950 tabular-nums sm:text-right">Rs {{ number_format((float) $item->total, 2) }}</div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- notes --}}
                <div class="mt-6 rounded-xl border border-slate-200 bg-slate-50 px-5 py-5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Notes</p>
                    <p class="mt-3 text-sm leading-7 text-slate-700">{{ $invoice->notes ?: 'No notes added for this invoice.' }}</p>
                </div>
            </div>
        </section>

        <aside class="space-y-6">
            <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                    <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Invoice Summary</p>
                    <h2 class="mt-2 text-2xl font-semibold text-slate-950 tabular-nums">Rs {{ number_format((float) $invoice->final_amount, 2) }}</h2>
                    <p class="mt-1.5 text-sm text-slate-500">Final payable amount for this invoice.</p>
                </div>

                <div class="space-y-3 px-6 py-6">
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">
                        <span class="text-sm text-slate-600">Subtotal</span>
                        <span class="text-base font-semibold text-slate-950 tabular-nums">Rs {{ number_format((float) $invoice->subtotal_amount, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3.5">
                        <span class="text-sm text-slate-600">Discount</span>
                        <span class="text-base font-semibold text-slate-950 tabular-nums">- Rs {{ number_format((float) $invoice->discount, 2) }}</span>
                    </div>

                    <div class="flex items-center justify-between gap-3 rounded-xl bg-[#1e3a5f] px-4 py-4 text-white">
                        <span class="text-sm text-slate-200">Final Amount</span>
                        <span class="text-xl font-semibold tabular-nums">Rs {{ number_format((float) $invoice->final_amount, 2) }}</span>
                    </div>
                </div>
            </section>

            <section class="rounded-2xl border border-slate-200 bg-white px-6 py-6 shadow-sm">
                <p class="text-[11px] font-semibold uppercase tracking-[0.18em] text-slate-500">Quick Actions</p>
                <div class="mt-4 space-y-3">
                    <a href="{{ route('invoices.print', $invoice) }}" class="inline-flex w-full items-center justify-center rounded-xl bg-[#1e3a5f] px-5 py-3 text-sm font-semibold text-white transition hover:bg-[#16314f]">
                        Open print layout
                    </a>
                    <a href="{{ route('invoices.edit', $invoice) }}" class="inline-flex w-full items-center justify-center rounded-xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                        Edit invoice details
                    </a>
                    <form action="{{ route('invoices.destroy', $invoice) }}" method="POST" onsubmit="return confirm('Delete this invoice?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="inline-flex w-full items-center justify-center rounded-xl border border-rose-200 bg-rose-50 px-5 py-3 text-sm font-semibold text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                            Delete invoice
                        </button>
                    </form>
                </div>
            </section>
        </aside>
    </div>
@endsection
