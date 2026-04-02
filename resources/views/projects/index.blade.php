@extends('layouts.app')

@section('title', 'Projects')
@section('page-title', 'Projects')
@section('page-eyebrow', 'Project Management')

@section('content')
    <x-admin.page-header title="Projects" description="Track linked customer projects with progress updates, scheduling, and current delivery status.">
        <a href="{{ route('projects.create') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Add project
        </a>
    </x-admin.page-header>

    <section class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <form method="GET" class="grid gap-4 xl:grid-cols-[minmax(0,1fr)_16rem_16rem_auto]">
            <div>
                <label for="search" class="mb-2 block text-sm font-medium text-slate-700">Search projects</label>
                <input id="search" name="search" type="text" value="{{ $search }}" placeholder="Search by project name, customer, or description" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
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
            @forelse ($projects as $project)
                <div class="rounded-3xl border border-slate-100 bg-slate-50/80 p-5">
                    <div class="flex flex-col gap-4 xl:flex-row xl:items-start xl:justify-between">
                        <div class="min-w-0">
                            <div class="flex flex-wrap items-center gap-3">
                                <h2 class="text-lg font-semibold text-slate-950">{{ $project->project_name }}</h2>
                                <x-admin.status-badge :label="$project->status->label()" :classes="$project->status->badgeClasses()" />
                            </div>
                            <p class="mt-2 text-sm text-slate-500">{{ $project->customer?->name }} | {{ $project->start_date?->format('d M Y') ?: 'No start date' }}</p>
                            <p class="mt-3 max-w-3xl text-sm text-slate-600">{{ $project->description ?: 'No description added.' }}</p>
                        </div>

                        <div class="flex flex-wrap gap-2">
                            <a href="{{ route('projects.show', $project) }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-slate-700 transition hover:border-slate-300 hover:text-slate-950">
                                View
                            </a>
                            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center rounded-2xl border border-sky-200 bg-sky-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-sky-700 transition hover:border-sky-300 hover:text-sky-900">
                                Edit
                            </a>
                            <form action="{{ route('projects.destroy', $project) }}" method="POST" onsubmit="return confirm('Delete this project?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </div>

                    <div class="mt-4">
                        <x-admin.progress-bar :value="$project->progress" />
                    </div>
                </div>
            @empty
                <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-10 text-center text-slate-500">No projects found for the current filters.</p>
            @endforelse
        </div>

        <div class="mt-6">
            {{ $projects->links() }}
        </div>
    </section>
@endsection
