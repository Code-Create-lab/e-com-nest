@extends('layouts.app')

@section('title', 'Customers')
@section('page-title', 'Customers')
@section('page-eyebrow', 'Customer Management')

@section('content')
    <x-admin.page-header title="Customers" description="Manage customer records, keep contact details centralized, and track project and invoice volume per account.">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Add customer
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form method="GET" class="grid gap-4 md:grid-cols-[minmax(0,1fr)_auto]">
            <div>
                <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Search customers</label>
                <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Search by name, email, phone, company, or address" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            </div>

            <div class="flex items-end gap-3">
                <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
                    Search
                </button>
                <a href="{{ route('customers.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
                    Reset
                </a>
            </div>
        </form>

        <div class="mt-6 overflow-x-auto">
            <table class="min-w-full divide-y divide-slate-200 text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <th class="pb-4 pr-4 font-medium">Customer</th>
                        <th class="pb-4 pr-4 font-medium">Contact</th>
                        <th class="pb-4 pr-4 font-medium">Projects</th>
                        <th class="pb-4 pr-4 font-medium">Invoices</th>
                        <th class="pb-4 text-right font-medium">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($customers as $customer)
                        <tr class="align-top">
                            <td class="py-5 pr-4">
                                <p class="font-semibold text-slate-900">{{ $customer->name }}</p>
                                <p class="text-slate-500">{{ $customer->company_name ?: 'No company assigned' }}</p>
                            </td>
                            <td class="py-5 pr-4 text-slate-600">
                                <p>{{ $customer->email ?: 'No email' }}</p>
                                <p>{{ $customer->phone ?: 'No phone' }}</p>
                            </td>
                            <td class="py-5 pr-4 text-slate-600">{{ $customer->projects_count }}</td>
                            <td class="py-5 pr-4 text-slate-600">{{ $customer->invoices_count }}</td>
                            <td class="py-5 text-right">
                                <div class="flex justify-end gap-2">
                                    <a href="{{ route('customers.show', $customer) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                                        View
                                    </a>
                                    <a href="{{ route('customers.edit', $customer) }}" class="inline-flex items-center rounded-2xl border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:border-sky-300 hover:text-sky-900">
                                        Edit
                                    </a>
                                    <form action="{{ route('customers.destroy', $customer) }}" method="POST" onsubmit="return confirm('Delete this customer?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                                            Delete
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="py-10 text-center text-slate-500">No customers found for the current filters.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="mt-6">
            {{ $customers->links() }}
        </div>
    </section>
@endsection
