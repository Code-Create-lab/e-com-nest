@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-eyebrow', 'Operations Overview')

@section('content')
    <x-admin.page-header title="Admin Dashboard" description="Track customers, lead flow, delivery progress, and paid revenue from a single responsive workspace.">
        <a href="{{ route('customers.create') }}" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            New customer
        </a>
        <a href="{{ route('projects.create') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            New project
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat-card title="Total Customers" :value="$totalCustomers" hint="Customer records available in the CRM." tone="sky" />
        <x-admin.stat-card title="Total Leads" :value="$totalLeads" hint="Open pipeline entries across all sources." tone="amber" />
        <x-admin.stat-card title="Total Projects" :value="$totalProjects" hint="Projects tracked with progress and status." tone="emerald" />
        <x-admin.stat-card title="Total Revenue" :value="'Rs '.number_format($totalRevenue, 2)" hint="Paid invoice total generated from the system." tone="rose" />
    </div>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent leads</h2>
                    <p class="text-sm text-slate-500">Latest pipeline updates and conversion status.</p>
                </div>
                <a href="{{ route('leads.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View all</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($recentLeads as $lead)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $lead->name }}</p>
                                <p class="text-sm text-slate-500">{{ $lead->source }} | {{ $lead->email ?: $lead->phone }}</p>
                            </div>
                            <x-admin.status-badge :label="$lead->status->label()" :classes="$lead->status->badgeClasses()" />
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">No leads recorded yet.</p>
                @endforelse
            </div>
        </section>

        <section class="rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent projects</h2>
                    <p class="text-sm text-slate-500">Current delivery status and progress snapshots.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View all</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($recentProjects as $project)
                    <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-semibold text-slate-900">{{ $project->project_name }}</p>
                                <p class="text-sm text-slate-500">{{ $project->customer?->name }}</p>
                            </div>
                            <x-admin.status-badge :label="$project->status->label()" :classes="$project->status->badgeClasses()" />
                        </div>

                        <div class="mt-4">
                            <x-admin.progress-bar :value="$project->progress" />
                        </div>
                    </div>
                @empty
                    <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">No projects recorded yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
