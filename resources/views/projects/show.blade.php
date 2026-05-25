@extends('layouts.app')

@section('title', $project->project_name)
@section('page-title', 'Project Details')
@section('page-eyebrow', 'Project Management')

@section('content')
    <x-admin.page-header :title="$project->project_name" :description="$project->customer?->name ?: 'Customer project'">
        <a href="{{ route('projects.edit', $project) }}" data-magnetic data-magnetic-strength="0.2" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            Edit project
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1.1fr)_minmax(0,0.9fr)]">
        <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="3" class="tilt-surface rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex flex-wrap items-center gap-3">
                <x-admin.status-badge :label="$project->status->label()" :classes="$project->status->badgeClasses()" />
                @if ($project->engagement_type)
                    <x-admin.status-badge :label="$project->engagement_type->label()" :classes="$project->engagement_type->badgeClasses()" />
                @endif
                <p class="text-sm text-slate-500">Customer: {{ $project->customer?->name }}</p>
            </div>

            @if ($project->engagement_type?->isRecurring())
                <div class="mt-6 grid gap-4 rounded-2xl border border-emerald-100 bg-emerald-50/60 p-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-emerald-700">Monthly Amount</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950">Rs {{ number_format((float) $project->monthly_amount, 2) }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-emerald-700">Billing Day</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950">{{ $project->billing_day ? 'Day '.$project->billing_day : '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-emerald-700">Hours / Month</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950">{{ $project->hours_per_month ?? '—' }}</p>
                    </div>
                    <div>
                        <p class="text-[0.65rem] font-semibold uppercase tracking-[0.2em] text-emerald-700">Next Renewal</p>
                        <p class="mt-2 text-xl font-semibold text-slate-950">{{ $project->support_renews_on?->format('d M Y') ?: '—' }}</p>
                    </div>
                </div>
            @endif

            <div class="mt-6">
                <x-admin.progress-bar :value="$project->progress" />
            </div>

            <dl class="mt-6 grid gap-5 md:grid-cols-2">
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Start Date</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $project->start_date?->format('d M Y') ?: 'Not set' }}</dd>
                </div>
                <div>
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">End Date</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $project->end_date?->format('d M Y') ?: 'Not set' }}</dd>
                </div>
                <div class="md:col-span-2">
                    <dt class="text-xs uppercase tracking-[0.2em] text-slate-500">Description</dt>
                    <dd class="mt-2 text-sm text-slate-900">{{ $project->description ?: 'No description added.' }}</dd>
                </div>
            </dl>
        </section>

        <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="3" class="tilt-surface rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Linked invoices</h2>
                    <p class="text-sm text-slate-500">Invoices already attached to this project.</p>
                </div>
                <a href="{{ route('invoices.create') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">Create invoice</a>
            </div>

            <div class="mt-5 space-y-4">
                @forelse ($project->invoices as $invoice)
                    <div class="lift-hover rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
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

    @include('projects.partials.tasks-panel', [
        'project' => $project,
        'tasks' => $tasks,
        'tasksByStatus' => $tasksByStatus,
        'taskStats' => $taskStats,
        'taskFilter' => $taskFilter,
        'taskView' => $taskView,
        'taskStatuses' => $taskStatuses,
        'taskPriorities' => $taskPriorities,
    ])
@endsection
