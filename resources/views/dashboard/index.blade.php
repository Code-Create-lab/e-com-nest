@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-eyebrow', 'Operations Overview')

@section('content')
    <x-admin.page-header title="Admin Dashboard" description="Track customers, lead flow, delivery progress, and paid revenue from a single responsive workspace.">
        <a href="{{ route('customers.create') }}" data-magnetic data-magnetic-strength="0.18" class="btn btn-gradient">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 5v14M5 12h14"/></svg>
            New customer
        </a>
        <a href="{{ route('projects.create') }}" data-magnetic data-magnetic-strength="0.16" class="btn btn-secondary">
            <svg viewBox="0 0 24 24" class="h-4 w-4" fill="none" stroke="currentColor" stroke-width="2.2"><rect x="2" y="7" width="20" height="14" rx="2"/><path stroke-linecap="round" stroke-linejoin="round" d="M16 21V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v16"/></svg>
            New project
        </a>
    </x-admin.page-header>

    <div class="mt-6 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
        <x-admin.stat-card title="Total Customers" :value="$totalCustomers" :count="(int) $totalCustomers" hint="Customer records available in the CRM." tone="sky" />
        <x-admin.stat-card title="Total Leads" :value="$totalLeads" :count="(int) $totalLeads" hint="Open pipeline entries across all sources." tone="amber" />
        <x-admin.stat-card title="Total Projects" :value="$totalProjects" :count="(int) $totalProjects" hint="Projects tracked with progress and status." tone="emerald" />
        <x-admin.stat-card
            title="Total Revenue"
            :value="'Rs '.number_format($totalRevenue, 2)"
            :count="(float) $totalRevenue"
            prefix="Rs "
            hint="Paid invoice total generated from the system."
            tone="primary"
        />
    </div>

    <div class="mt-4 grid gap-4 md:grid-cols-3">
        <x-admin.stat-card
            title="Pending Tasks"
            :value="$pendingTasksCount"
            :count="(int) $pendingTasksCount"
            hint="Tasks not yet marked done across all projects."
            tone="sky"
        />
        <x-admin.stat-card
            title="Overdue Tasks"
            :value="$overdueTasksCount"
            :count="(int) $overdueTasksCount"
            hint="Pending tasks past their due date."
            tone="rose"
        />
        <x-admin.stat-card
            title="Due This Week"
            :value="$dueThisWeekCount"
            :count="(int) $dueThisWeekCount"
            hint="Pending tasks due within the current week."
            tone="amber"
        />
    </div>

    <div class="mt-4 grid gap-4 md:grid-cols-2">
        <x-admin.stat-card
            title="CR Paid"
            :value="'Rs '.number_format($crPaidAmount, 2)"
            :count="(float) $crPaidAmount"
            prefix="Rs "
            :hint="$crPaidCount.' billable change-request task(s) marked paid.'"
            tone="emerald"
        />
        <x-admin.stat-card
            title="CR Unpaid"
            :value="'Rs '.number_format($crUnpaidAmount, 2)"
            :count="(float) $crUnpaidAmount"
            prefix="Rs "
            :hint="$crUnpaidCount.' billable change-request task(s) awaiting payment.'"
            tone="rose"
        />
    </div>

    @php
        $periodLabels = [
            'daily' => 'Daily · 30d',
            'weekly' => 'Weekly · 12w',
            'monthly' => 'Monthly · 12m',
            'yearly' => 'Yearly · 5y',
        ];
    @endphp
    <section data-page-section data-motion-reveal data-motion-variant="up" class="surface-elevated mt-6 p-6">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <span class="eyebrow">Revenue</span>
                <p class="mt-1 text-3xl font-semibold tracking-tight text-[var(--fg-strong)]">
                    Rs {{ number_format((float) $revenueSeries['total'], 2) }}
                </p>
                <p class="mt-1.5 text-xs text-[var(--fg-muted)]">
                    Paid invoices + paid CR tasks · {{ $periodLabels[$revenuePeriod] }}
                </p>
                <div class="mt-2 flex flex-wrap items-center gap-2 text-xs">
                    <span class="chip"><span class="chip-dot" style="background: var(--tone-info);"></span> Invoices Rs {{ number_format((float) ($revenueSeries['invoice_total'] ?? 0), 0) }}</span>
                    <span class="chip"><span class="chip-dot" style="background: var(--tone-success);"></span> CR Rs {{ number_format((float) ($revenueSeries['cr_total'] ?? 0), 0) }}</span>
                </div>
            </div>

            <div class="inline-flex flex-wrap gap-1 rounded-xl border p-1 text-xs font-semibold" style="border-color: var(--border-default); background: var(--bg-subtle);">
                @foreach ($periodLabels as $key => $label)
                    @php $active = $revenuePeriod === $key; @endphp
                    <a
                        href="{{ route('dashboard', ['period' => $key]) }}"
                        class="inline-flex items-center rounded-lg px-3 py-1.5 transition"
                        @if ($active)
                            style="background: var(--bg-elevated); color: var(--fg-strong); box-shadow: var(--shadow-sm);"
                        @else
                            style="color: var(--fg-muted);"
                        @endif
                    >
                        {{ explode(' · ', $label)[0] }}
                    </a>
                @endforeach
            </div>
        </div>

        <div class="mt-6 h-72">
            <canvas
                data-revenue-chart
                data-series="{{ json_encode($revenueSeries, JSON_HEX_APOS | JSON_HEX_QUOT) }}"
                class="!h-full !w-full"
            ></canvas>
        </div>
    </section>

    @if ($crByCustomer->isNotEmpty())
        <section data-page-section data-motion-reveal data-motion-variant="up" class="surface-elevated mt-6 p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-[var(--fg-strong)]">CR breakdown by customer</h2>
                    <p class="text-sm text-[var(--fg-muted)]">Billable change-request tasks across one-time projects.</p>
                </div>
                <a href="{{ route('customers.index') }}" class="text-sm font-semibold text-[var(--accent-1)] hover:underline">All customers →</a>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="data-table min-w-full">
                    <thead>
                        <tr>
                            <th>Customer</th>
                            <th class="text-right">Tasks</th>
                            <th class="text-right">Hours</th>
                            <th class="text-right">Paid</th>
                            <th class="text-right">Unpaid</th>
                            <th class="text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($crByCustomer as $row)
                            @php $total = (float) $row->paid_amount + (float) $row->unpaid_amount; @endphp
                            <tr>
                                <td>
                                    <a href="{{ route('customers.show', $row->customer_id) }}" class="font-semibold text-[var(--fg-strong)] hover:text-[var(--accent-1)]">{{ $row->customer_name }}</a>
                                    @if ($row->company_name)
                                        <p class="text-xs text-[var(--fg-muted)]">{{ $row->company_name }}</p>
                                    @endif
                                </td>
                                <td class="text-right text-xs">
                                    <span class="font-semibold text-[var(--fg-strong)]">{{ $row->total_count }}</span>
                                    <span class="text-[var(--fg-faint)]"> · {{ $row->paid_count }}P / {{ $row->unpaid_count }}U</span>
                                </td>
                                <td class="text-right text-[var(--fg-default)]">{{ rtrim(rtrim(number_format((float) $row->total_hours, 2), '0'), '.') ?: '0' }}h</td>
                                <td class="text-right">
                                    <span class="badge" style="background: color-mix(in oklab, var(--tone-success) 12%, var(--bg-elevated)); border-color: color-mix(in oklab, var(--tone-success) 30%, var(--border-soft)); color: var(--tone-success);">
                                        Rs {{ number_format((float) $row->paid_amount, 0) }}
                                    </span>
                                </td>
                                <td class="text-right">
                                    <span class="badge" style="background: color-mix(in oklab, var(--tone-danger) 12%, var(--bg-elevated)); border-color: color-mix(in oklab, var(--tone-danger) 30%, var(--border-soft)); color: var(--tone-danger);">
                                        Rs {{ number_format((float) $row->unpaid_amount, 0) }}
                                    </span>
                                </td>
                                <td class="text-right font-semibold text-[var(--fg-strong)]">Rs {{ number_format($total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr style="background: var(--bg-subtle);">
                            <td class="text-[0.66rem] font-bold uppercase tracking-[0.16em] text-[var(--fg-muted)]">Total</td>
                            <td class="text-right text-xs text-[var(--fg-muted)]">{{ $crByCustomer->sum('total_count') }}</td>
                            <td class="text-right font-semibold text-[var(--fg-strong)]">{{ rtrim(rtrim(number_format((float) $crByCustomer->sum('total_hours'), 2), '0'), '.') ?: '0' }}h</td>
                            <td class="text-right font-semibold" style="color: var(--tone-success);">Rs {{ number_format($crPaidAmount, 0) }}</td>
                            <td class="text-right font-semibold" style="color: var(--tone-danger);">Rs {{ number_format($crUnpaidAmount, 0) }}</td>
                            <td class="text-right font-bold text-[var(--fg-strong)]">Rs {{ number_format($crPaidAmount + $crUnpaidAmount, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    @endif

    <section data-page-section data-motion-reveal data-motion-variant="up" class="surface-elevated mt-6 p-6">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-base font-semibold text-[var(--fg-strong)]">Pending tasks</h2>
                <p class="text-sm text-[var(--fg-muted)]">Top open tasks across projects. Overdue first, then by priority.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-[var(--accent-1)] hover:underline">All projects →</a>
        </div>

        <div class="mt-5 overflow-x-auto">
            @if ($pendingTasks->isNotEmpty())
                <table class="data-table min-w-full">
                    <thead>
                        <tr>
                            <th>Task</th>
                            <th>Project</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Due</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pendingTasks as $task)
                            @php $overdue = $task->isOverdue(); @endphp
                            <tr>
                                <td>
                                    <p class="font-semibold text-[var(--fg-strong)]">{{ $task->title }}</p>
                                    @if ($task->assignee)
                                        <p class="text-xs text-[var(--fg-muted)]">{{ $task->assignee }}</p>
                                    @endif
                                </td>
                                <td>
                                    @if ($task->project)
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-medium text-[var(--fg-default)] hover:text-[var(--accent-1)]">{{ $task->project->project_name }}</a>
                                        @if ($task->project->customer)
                                            <p class="text-xs text-[var(--fg-muted)]">{{ $task->project->customer->name }}</p>
                                        @endif
                                    @else
                                        <span class="text-[var(--fg-faint)]">—</span>
                                    @endif
                                </td>
                                <td>
                                    <x-admin.status-badge :label="$task->priority->label()" :classes="$task->priority->badgeClasses()" />
                                </td>
                                <td>
                                    <x-admin.status-badge :label="$task->status->label()" :classes="$task->status->badgeClasses()" />
                                </td>
                                <td class="text-xs">
                                    @if ($task->due_date)
                                        <span class="badge" @if ($overdue) style="background: color-mix(in oklab, var(--tone-danger) 12%, var(--bg-elevated)); color: var(--tone-danger); border-color: color-mix(in oklab, var(--tone-danger) 30%, var(--border-soft));" @endif>
                                            {{ $task->due_date->format('d M Y') }}
                                            @if ($overdue)
                                                · overdue
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-[var(--fg-faint)]">No due date</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="flex flex-col items-center justify-center gap-2 rounded-xl border border-dashed px-6 py-10 text-sm" style="border-color: var(--border-default); color: var(--fg-muted);">
                    <svg viewBox="0 0 24 24" class="h-8 w-8 opacity-50" fill="none" stroke="currentColor" stroke-width="1.5"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                    All caught up. No pending tasks.
                </div>
            @endif
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section data-page-section data-motion-reveal data-motion-variant="up" class="surface-elevated p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-[var(--fg-strong)]">Recent leads</h2>
                    <p class="text-sm text-[var(--fg-muted)]">Latest pipeline updates and conversion status.</p>
                </div>
                <a href="{{ route('leads.index') }}" class="text-sm font-semibold text-[var(--accent-1)] hover:underline">View all →</a>
            </div>

            <div class="mt-5 space-y-2.5" data-motion-reveal data-motion-stagger data-motion-variant="up">
                @forelse ($recentLeads as $lead)
                    <div class="lift-hover flex flex-col gap-2 rounded-xl border p-3.5 sm:flex-row sm:items-center sm:justify-between" style="border-color: var(--border-soft); background: var(--bg-subtle);">
                        <div class="flex items-center gap-3">
                            <span class="flex h-9 w-9 items-center justify-center rounded-lg text-[0.72rem] font-bold" style="background: var(--accent-1); color: var(--accent-on);">
                                {{ mb_strtoupper(mb_substr($lead->name, 0, 2)) }}
                            </span>
                            <div>
                                <p class="font-semibold text-[var(--fg-strong)]">{{ $lead->name }}</p>
                                <p class="text-xs text-[var(--fg-muted)]">{{ $lead->source }} · {{ $lead->email ?: $lead->phone }}</p>
                            </div>
                        </div>
                        <x-admin.status-badge :label="$lead->status->label()" :classes="$lead->status->badgeClasses()" />
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed px-4 py-6 text-sm" style="border-color: var(--border-default); color: var(--fg-muted);">No leads recorded yet.</p>
                @endforelse
            </div>
        </section>

        <section data-page-section data-motion-reveal data-motion-variant="up" class="surface-elevated p-6">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-base font-semibold text-[var(--fg-strong)]">Recent projects</h2>
                    <p class="text-sm text-[var(--fg-muted)]">Current delivery status and progress snapshots.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-semibold text-[var(--accent-1)] hover:underline">View all →</a>
            </div>

            <div class="mt-5 space-y-2.5" data-motion-reveal data-motion-stagger data-motion-variant="up">
                @forelse ($recentProjects as $project)
                    <div class="lift-hover rounded-xl border p-3.5" style="border-color: var(--border-soft); background: var(--bg-subtle);">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="font-semibold text-[var(--fg-strong)]">{{ $project->project_name }}</p>
                                <p class="text-xs text-[var(--fg-muted)]">{{ $project->customer?->name }}</p>
                            </div>
                            <x-admin.status-badge :label="$project->status->label()" :classes="$project->status->badgeClasses()" />
                        </div>
                        <div class="mt-3.5">
                            <x-admin.progress-bar :value="$project->progress" />
                        </div>
                    </div>
                @empty
                    <p class="rounded-xl border border-dashed px-4 py-6 text-sm" style="border-color: var(--border-default); color: var(--fg-muted);">No projects recorded yet.</p>
                @endforelse
            </div>
        </section>
    </div>
@endsection
