@extends('layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-eyebrow', 'Operations Overview')

@section('content')
    <x-admin.page-header title="Admin Dashboard" description="Track customers, lead flow, delivery progress, and paid revenue from a single responsive workspace.">
        <a href="{{ route('customers.create') }}" data-magnetic data-magnetic-strength="0.2" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            New customer
        </a>
        <a href="{{ route('projects.create') }}" data-magnetic data-magnetic-strength="0.18" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
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
            tone="rose"
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

    <div class="mt-6 grid gap-4 md:grid-cols-2">
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
    <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="2" class="tilt-surface mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <div class="flex flex-col gap-4 lg:flex-row lg:items-start lg:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Revenue</h2>
                <p class="text-sm text-slate-500">Paid invoices + paid CR tasks combined. Toggle period to drill in.</p>
                <p class="mt-2 text-3xl font-semibold tracking-tight text-slate-950">Rs {{ number_format((float) $revenueSeries['total'], 2) }}
                    <span class="ml-2 text-xs font-medium uppercase tracking-[0.2em] text-slate-500">{{ $periodLabels[$revenuePeriod] }}</span>
                </p>
                <p class="mt-1 text-xs text-slate-500">
                    Invoices: <span class="font-semibold text-sky-700">Rs {{ number_format((float) ($revenueSeries['invoice_total'] ?? 0), 2) }}</span>
                    · CR: <span class="font-semibold text-emerald-700">Rs {{ number_format((float) ($revenueSeries['cr_total'] ?? 0), 2) }}</span>
                </p>
            </div>

            <div class="inline-flex flex-wrap gap-1.5 rounded-2xl border border-slate-200 bg-white p-1 text-xs font-semibold uppercase tracking-[0.16em]">
                @foreach ($periodLabels as $key => $label)
                    @php $active = $revenuePeriod === $key; @endphp
                    <a
                        href="{{ route('dashboard', ['period' => $key]) }}"
                        data-magnetic data-magnetic-strength="0.12"
                        class="inline-flex items-center rounded-xl px-3 py-1.5 transition {{ $active ? 'bg-slate-950 text-white shadow' : 'text-slate-600 hover:text-slate-950' }}"
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
        <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="2" class="tilt-surface mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">CR breakdown by customer</h2>
                    <p class="text-sm text-slate-500">Billable change-request tasks across one-time projects.</p>
                </div>
                <a href="{{ route('customers.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">All customers</a>
            </div>

            <div class="mt-5 overflow-x-auto">
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500">
                            <th class="px-3 py-2">Customer</th>
                            <th class="px-3 py-2 text-right">Tasks</th>
                            <th class="px-3 py-2 text-right">Hours</th>
                            <th class="px-3 py-2 text-right">Paid</th>
                            <th class="px-3 py-2 text-right">Unpaid</th>
                            <th class="px-3 py-2 text-right">Total</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" data-motion-reveal data-motion-stagger data-motion-variant="up">
                        @foreach ($crByCustomer as $row)
                            @php $total = (float) $row->paid_amount + (float) $row->unpaid_amount; @endphp
                            <tr class="lift-hover hover:bg-slate-50/60">
                                <td class="px-3 py-3">
                                    <a href="{{ route('customers.show', $row->customer_id) }}" class="font-semibold text-slate-900 hover:text-sky-700">{{ $row->customer_name }}</a>
                                    @if ($row->company_name)
                                        <p class="text-xs text-slate-500">{{ $row->company_name }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-right text-xs text-slate-600">
                                    <span class="font-semibold text-slate-900">{{ $row->total_count }}</span>
                                    <span class="text-slate-400"> · {{ $row->paid_count }}P / {{ $row->unpaid_count }}U</span>
                                </td>
                                <td class="px-3 py-3 text-right text-sm text-slate-700">{{ rtrim(rtrim(number_format((float) $row->total_hours, 2), '0'), '.') ?: '0' }}h</td>
                                <td class="px-3 py-3 text-right">
                                    <span class="inline-flex items-center rounded-full bg-emerald-50 px-2.5 py-1 text-xs font-semibold text-emerald-700 ring-1 ring-emerald-200">
                                        Rs {{ number_format((float) $row->paid_amount, 0) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-right">
                                    <span class="inline-flex items-center rounded-full bg-rose-50 px-2.5 py-1 text-xs font-semibold text-rose-700 ring-1 ring-rose-200">
                                        Rs {{ number_format((float) $row->unpaid_amount, 0) }}
                                    </span>
                                </td>
                                <td class="px-3 py-3 text-right text-sm font-semibold text-slate-950">Rs {{ number_format($total, 0) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="text-sm">
                            <td class="px-3 py-3 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500">Total</td>
                            <td class="px-3 py-3 text-right text-xs text-slate-600">{{ $crByCustomer->sum('total_count') }}</td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-slate-900">{{ rtrim(rtrim(number_format((float) $crByCustomer->sum('total_hours'), 2), '0'), '.') ?: '0' }}h</td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-emerald-700">Rs {{ number_format($crPaidAmount, 0) }}</td>
                            <td class="px-3 py-3 text-right text-sm font-semibold text-rose-700">Rs {{ number_format($crUnpaidAmount, 0) }}</td>
                            <td class="px-3 py-3 text-right text-sm font-bold text-slate-950">Rs {{ number_format($crPaidAmount + $crUnpaidAmount, 0) }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </section>
    @endif

    <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="2" class="tilt-surface mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
        <div class="flex items-center justify-between gap-3">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Pending tasks</h2>
                <p class="text-sm text-slate-500">Top open tasks across projects. Overdue first, then by priority.</p>
            </div>
            <a href="{{ route('projects.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">All projects</a>
        </div>

        <div class="mt-5 overflow-x-auto">
            @if ($pendingTasks->isNotEmpty())
                <table class="min-w-full divide-y divide-slate-100 text-sm">
                    <thead>
                        <tr class="text-left text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500">
                            <th class="px-3 py-2">Task</th>
                            <th class="px-3 py-2">Project</th>
                            <th class="px-3 py-2">Priority</th>
                            <th class="px-3 py-2">Status</th>
                            <th class="px-3 py-2">Due</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100" data-motion-reveal data-motion-stagger data-motion-variant="up">
                        @foreach ($pendingTasks as $task)
                            @php $overdue = $task->isOverdue(); @endphp
                            <tr class="lift-hover hover:bg-slate-50/60">
                                <td class="px-3 py-3">
                                    <p class="font-semibold text-slate-900">{{ $task->title }}</p>
                                    @if ($task->assignee)
                                        <p class="text-xs text-slate-500">{{ $task->assignee }}</p>
                                    @endif
                                </td>
                                <td class="px-3 py-3 text-sm">
                                    @if ($task->project)
                                        <a href="{{ route('projects.show', $task->project_id) }}" class="font-medium text-slate-800 hover:text-sky-700">{{ $task->project->project_name }}</a>
                                        @if ($task->project->customer)
                                            <p class="text-xs text-slate-500">{{ $task->project->customer->name }}</p>
                                        @endif
                                    @else
                                        <span class="text-slate-400">—</span>
                                    @endif
                                </td>
                                <td class="px-3 py-3">
                                    <x-admin.status-badge :label="$task->priority->label()" :classes="$task->priority->badgeClasses()" />
                                </td>
                                <td class="px-3 py-3">
                                    <x-admin.status-badge :label="$task->status->label()" :classes="$task->status->badgeClasses()" />
                                </td>
                                <td class="px-3 py-3 text-xs">
                                    @if ($task->due_date)
                                        <span class="inline-flex items-center rounded-full px-2.5 py-1 font-semibold ring-1 {{ $overdue ? 'bg-rose-50 text-rose-700 ring-rose-200' : 'bg-slate-100 text-slate-700 ring-slate-200' }}">
                                            {{ $task->due_date->format('d M Y') }}
                                            @if ($overdue)
                                                · overdue
                                            @endif
                                        </span>
                                    @else
                                        <span class="text-slate-400">No due date</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-sm text-slate-500">No pending tasks. All caught up.</p>
            @endif
        </div>
    </section>

    <div class="mt-6 grid gap-6 xl:grid-cols-2">
        <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="3" class="tilt-surface rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent leads</h2>
                    <p class="text-sm text-slate-500">Latest pipeline updates and conversion status.</p>
                </div>
                <a href="{{ route('leads.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View all</a>
            </div>

            <div class="mt-5 space-y-4" data-motion-reveal data-motion-stagger data-motion-variant="up">
                @forelse ($recentLeads as $lead)
                    <div class="lift-hover rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
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

        <section data-motion-reveal data-motion-variant="up" data-tilt data-tilt-max="3" class="tilt-surface rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur">
            <div class="flex items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-semibold text-slate-950">Recent projects</h2>
                    <p class="text-sm text-slate-500">Current delivery status and progress snapshots.</p>
                </div>
                <a href="{{ route('projects.index') }}" class="text-sm font-medium text-sky-700 hover:text-sky-900">View all</a>
            </div>

            <div class="mt-5 space-y-4" data-motion-reveal data-motion-stagger data-motion-variant="up">
                @forelse ($recentProjects as $project)
                    <div class="lift-hover rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
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
