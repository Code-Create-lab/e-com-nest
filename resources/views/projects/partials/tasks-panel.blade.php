@props([
    'project',
    'tasks',
    'tasksByStatus',
    'taskStats',
    'taskFilter' => 'all',
    'taskView' => 'list',
    'taskStatuses',
    'taskPriorities',
])

@php
    $filterChips = [
        'all' => 'All',
        'open' => 'Open',
        'overdue' => 'Overdue',
        'week' => 'This week',
        'done' => 'Done',
    ];

    $billableTasks = $project->tasks->where('billable', true);
    $totalBillableHours = (float) $billableTasks->sum('hours_logged');
    $unbilledAmount = (float) $billableTasks->whereNull('billed_invoice_id')->sum(fn ($t) => $t->billableAmount());
    $billedAmount = (float) $billableTasks->whereNotNull('billed_invoice_id')->sum(fn ($t) => $t->billableAmount());
    $supportsHourly = $project->hourly_rate || $project->engagement_type?->value === 'hourly' || $project->engagement_type?->value === 'monthly_retainer';
@endphp

@if ($supportsHourly)
    <section class="mt-6 rounded-[2rem] border border-amber-100 bg-amber-50/60 p-6">
        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">
                    @if ($project->engagement_type?->value === 'one_time')
                        Change Requests (Hourly)
                    @else
                        Hourly Billing
                    @endif
                </h2>
                <p class="text-sm text-slate-600">
                    Rate: <span class="font-semibold text-slate-900">Rs {{ number_format((float) ($project->hourly_rate ?? 0), 2) }}/hr</span>
                    @if ($project->engagement_type?->value === 'monthly_retainer' && $project->hours_per_month)
                        · Retainer covers {{ $project->hours_per_month }} hrs/mo; extra hours billed hourly.
                    @elseif ($project->engagement_type?->value === 'one_time')
                        · Dev complete; new tasks billed as CRs.
                    @endif
                </p>
            </div>
            <a href="{{ route('projects.edit', $project) }}" class="inline-flex items-center rounded-xl border border-amber-200 bg-white px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700 hover:border-amber-300">
                Edit rate
            </a>
        </div>

        <div class="mt-5 grid gap-3 sm:grid-cols-4">
            <div class="rounded-2xl border border-amber-100 bg-white p-4">
                <p class="text-[0.65rem] uppercase tracking-[0.22em] text-amber-700">Billable Tasks</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $billableTasks->count() }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-4">
                <p class="text-[0.65rem] uppercase tracking-[0.22em] text-amber-700">Total Hours</p>
                <p class="mt-2 text-2xl font-semibold text-slate-950">{{ number_format($totalBillableHours, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-4">
                <p class="text-[0.65rem] uppercase tracking-[0.22em] text-amber-700">Unbilled</p>
                <p class="mt-2 text-2xl font-semibold text-rose-700">Rs {{ number_format($unbilledAmount, 2) }}</p>
            </div>
            <div class="rounded-2xl border border-amber-100 bg-white p-4">
                <p class="text-[0.65rem] uppercase tracking-[0.22em] text-amber-700">Billed</p>
                <p class="mt-2 text-2xl font-semibold text-emerald-700">Rs {{ number_format($billedAmount, 2) }}</p>
            </div>
        </div>
    </section>
@endif

<section
    id="tasks"
    data-tasks-panel
    data-project-id="{{ $project->id }}"
    class="mt-6 rounded-[2rem] border border-white/70 bg-white/90 p-6 shadow-lg shadow-slate-900/5 backdrop-blur"
>
    <div class="flex flex-col gap-3 lg:flex-row lg:items-start lg:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-slate-950">Tasks</h2>
            <p class="text-sm text-slate-500">Action items for this project. Paste meeting notes to add many at once.</p>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            {{-- View switcher --}}
            <div class="inline-flex rounded-2xl border border-slate-200 bg-white p-1 text-xs font-semibold uppercase tracking-[0.16em]">
                @php
                    $listUrl = route('projects.show', $project) . '?taskView=list&taskFilter=' . urlencode($taskFilter) . '#tasks';
                    $kanbanUrl = route('projects.show', $project) . '?taskView=kanban&taskFilter=' . urlencode($taskFilter) . '#tasks';
                @endphp
                <a href="{{ $listUrl }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 transition {{ $taskView === 'list' ? 'bg-slate-950 text-white' : 'text-slate-600 hover:text-slate-950' }}">
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    List
                </a>
                <a href="{{ $kanbanUrl }}" class="inline-flex items-center gap-1.5 rounded-xl px-3 py-1.5 transition {{ $taskView === 'kanban' ? 'bg-slate-950 text-white' : 'text-slate-600 hover:text-slate-950' }}">
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="none" stroke="currentColor" stroke-width="2.2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 5h4v14H4zM10 5h4v9h-4zM16 5h4v6h-4z"/></svg>
                    Kanban
                </a>
            </div>

            <button
                type="button"
                data-tasks-toggle="newTaskForm"
                class="inline-flex items-center gap-2 rounded-2xl bg-slate-950 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white shadow-md shadow-slate-900/15 transition hover:bg-slate-800"
            >
                + Add task
            </button>
            <button
                type="button"
                data-tasks-toggle="pasteForm"
                class="inline-flex items-center gap-2 rounded-2xl border border-slate-200 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950"
            >
                Paste from meeting
            </button>
        </div>
    </div>

    {{-- Stats strip --}}
    <div class="mt-5 grid gap-3 sm:grid-cols-4">
        <div class="rounded-2xl border border-slate-100 bg-slate-50/80 p-4">
            <p class="text-[0.65rem] uppercase tracking-[0.22em] text-slate-500">Total</p>
            <p class="mt-2 text-2xl font-semibold text-slate-950">{{ $taskStats['total'] }}</p>
        </div>
        <div class="rounded-2xl border border-sky-100 bg-sky-50/80 p-4">
            <p class="text-[0.65rem] uppercase tracking-[0.22em] text-sky-700">Open</p>
            <p class="mt-2 text-2xl font-semibold text-sky-900">{{ $taskStats['open'] }}</p>
        </div>
        <div class="rounded-2xl border border-rose-100 bg-rose-50/80 p-4">
            <p class="text-[0.65rem] uppercase tracking-[0.22em] text-rose-700">Overdue</p>
            <p class="mt-2 text-2xl font-semibold text-rose-900">{{ $taskStats['overdue'] }}</p>
        </div>
        <div class="rounded-2xl border border-emerald-100 bg-emerald-50/80 p-4">
            <p class="text-[0.65rem] uppercase tracking-[0.22em] text-emerald-700">Done</p>
            <p class="mt-2 text-2xl font-semibold text-emerald-900">{{ $taskStats['done'] }}</p>
        </div>
    </div>

    {{-- Filter pills --}}
    <div class="mt-5 flex flex-wrap gap-2">
        @foreach ($filterChips as $key => $label)
            @php $active = $taskFilter === $key; @endphp
            <a
                href="{{ route('projects.show', $project) }}?taskFilter={{ $key }}&taskView={{ $taskView }}#tasks"
                class="inline-flex items-center rounded-full px-3 py-1.5 text-xs font-semibold uppercase tracking-[0.18em] ring-1 transition
                    {{ $active
                        ? 'bg-slate-950 text-white ring-slate-950'
                        : 'bg-white text-slate-600 ring-slate-200 hover:text-slate-950 hover:ring-slate-300' }}"
            >
                {{ $label }}
            </a>
        @endforeach
    </div>

    {{-- New task form (collapsed by default) --}}
    <form
        method="POST"
        action="{{ route('projects.tasks.store', $project) }}"
        data-tasks-pane="newTaskForm"
        class="mt-5 hidden rounded-2xl border border-slate-200 bg-slate-50/70 p-4"
    >
        @csrf
        <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_10rem_10rem_10rem_auto]">
            <input
                type="text"
                name="title"
                required
                maxlength="255"
                placeholder="Task title"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
            >
            <select
                name="priority"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
            >
                @foreach ($taskPriorities as $p)
                    <option value="{{ $p->value }}" @selected($p->value === 'normal')>{{ $p->label() }}</option>
                @endforeach
            </select>
            <input
                type="date"
                name="due_date"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
            >
            <input
                type="text"
                name="assignee"
                maxlength="120"
                placeholder="Assignee"
                class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
            >
            <button
                type="submit"
                class="inline-flex items-center justify-center rounded-xl bg-slate-950 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white shadow-md shadow-slate-900/15 transition hover:bg-slate-800"
            >
                Save
            </button>
        </div>
        <div class="mt-3">
            <input
                type="text"
                name="group_name"
                maxlength="120"
                placeholder="Group name (optional) — e.g. CR-014 Login fixes, Sprint 4, Meeting 21 May"
                class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
            >
        </div>
        @if ($supportsHourly)
            <div class="mt-3 grid items-end gap-3 md:grid-cols-[auto_8rem_10rem]">
                <label class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                    <input type="checkbox" name="billable" value="1" class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                    Billable
                </label>
                <input
                    type="number"
                    name="hours_logged"
                    step="0.25"
                    min="0"
                    placeholder="Hours"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                >
                <input
                    type="number"
                    name="hourly_rate"
                    step="0.01"
                    min="0"
                    placeholder="Rate override (Rs)"
                    class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                >
            </div>
        @endif
        @error('title')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </form>

    {{-- Paste-from-meeting form (collapsed by default) --}}
    <form
        method="POST"
        action="{{ route('projects.tasks.bulk', $project) }}"
        data-tasks-pane="pasteForm"
        class="mt-5 hidden rounded-2xl border border-slate-200 bg-slate-50/70 p-4"
    >
        @csrf
        <div class="grid gap-3 md:grid-cols-[minmax(0,1fr)_14rem]">
            <div>
                <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Meeting notes</label>
                <textarea
                    name="raw"
                    rows="6"
                    required
                    placeholder="One task per line.&#10;Prefix tokens:&#10;  ! high  |  !! urgent&#10;  @name (assignee)&#10;  ^YYYY-MM-DD (due date)&#10;Example: ! Send revised quote @snehal ^2026-05-28"
                    class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm font-mono shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                ></textarea>
            </div>
            <div class="space-y-3">
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Meeting date</label>
                    <input
                        type="date"
                        name="meeting_date"
                        value="{{ now()->toDateString() }}"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                    >
                </div>
                <div>
                    <label class="mb-2 block text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Group name (optional)</label>
                    <input
                        type="text"
                        name="group_name"
                        maxlength="120"
                        placeholder="e.g. CR-014, Sprint 4"
                        class="w-full rounded-xl border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100"
                    >
                    <p class="mt-1 text-[0.65rem] text-slate-500">Used to bulk-complete or mark paid later. Defaults to meeting date.</p>
                </div>
                @if ($supportsHourly)
                    <div class="rounded-xl border border-amber-200 bg-amber-50/60 p-3">
                        <label class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                            <input type="checkbox" name="billable" value="1" class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                            Mark whole group billable
                        </label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="hourly_rate"
                            placeholder="Rate override (Rs)"
                            class="mt-2 w-full rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs shadow-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100"
                        >
                    </div>
                @endif
                <div class="rounded-xl border border-slate-200 bg-white p-3 text-[0.7rem] leading-5 text-slate-600">
                    <p class="font-semibold uppercase tracking-[0.18em] text-slate-500">Legend</p>
                    <p class="mt-1"><code class="rounded bg-slate-100 px-1">!</code> high &nbsp; <code class="rounded bg-slate-100 px-1">!!</code> urgent</p>
                    <p><code class="rounded bg-slate-100 px-1">@name</code> assignee</p>
                    <p><code class="rounded bg-slate-100 px-1">^2026-05-28</code> due date</p>
                </div>
                <button
                    type="submit"
                    class="w-full rounded-xl bg-slate-950 px-4 py-2 text-xs font-semibold uppercase tracking-[0.18em] text-white shadow-md shadow-slate-900/15 transition hover:bg-slate-800"
                >
                    Import tasks
                </button>
            </div>
        </div>
        @error('raw')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </form>

    @if ($taskView === 'kanban')
        @include('projects.partials.task-kanban', [
            'project' => $project,
            'tasksByStatus' => $tasksByStatus,
            'taskStatuses' => $taskStatuses,
            'taskPriorities' => $taskPriorities,
        ])
    @else
    {{-- Task list grouped by group_name / meeting_date --}}
    @php
        $grouped = $tasks->groupBy(fn ($t) => $t->groupKey());
    @endphp
    <div class="mt-6 space-y-4">
        @forelse ($grouped as $groupKey => $groupTasks)
            @php
                $first = $groupTasks->first();
                $groupLabel = $first->groupLabel();
                $groupCount = $groupTasks->count();
                $groupDone = $groupTasks->where('status', \App\Enums\TaskStatus::Done)->count();
                $groupBillable = $groupTasks->where('billable', true);
                $groupHours = (float) $groupBillable->sum('hours_logged');
                $groupAmount = (float) $groupBillable->sum(fn ($t) => $t->billableAmount());
                $groupPaidCount = $groupBillable->where('paid', true)->count();
                $allDone = $groupCount > 0 && $groupDone === $groupCount;
                $allPaid = $groupBillable->count() > 0 && $groupPaidCount === $groupBillable->count();

                $hiddenFields = '';
                if ($groupKey === 'none') {
                    $hiddenFields = '<input type="hidden" name="ungrouped" value="1">';
                } elseif (str_starts_with($groupKey, 'name:')) {
                    $hiddenFields = '<input type="hidden" name="group_name" value="' . e(substr($groupKey, 5)) . '">';
                } elseif (str_starts_with($groupKey, 'date:')) {
                    $hiddenFields = '<input type="hidden" name="meeting_date" value="' . e(substr($groupKey, 5)) . '">';
                }
            @endphp

            <div class="overflow-hidden rounded-2xl border border-slate-200 bg-slate-50/40">
                {{-- Group header --}}
                <div class="flex flex-col gap-3 border-b border-slate-200 bg-white/70 px-4 py-3 sm:flex-row sm:items-center sm:justify-between">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex h-6 min-w-6 items-center justify-center rounded-full bg-slate-950 px-1.5 text-[0.6rem] font-bold text-white">
                            {{ $groupCount }}
                        </span>
                        <h3 class="text-sm font-semibold text-slate-950">{{ $groupLabel }}</h3>
                        <span class="text-xs text-slate-500">{{ $groupDone }}/{{ $groupCount }} done</span>
                        @if ($groupBillable->count() > 0)
                            <span class="inline-flex items-center rounded-full bg-amber-50 px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-amber-700 ring-1 ring-amber-200">
                                {{ rtrim(rtrim(number_format($groupHours, 2), '0'), '.') ?: '0' }}h · Rs {{ number_format($groupAmount, 0) }}
                            </span>
                            @if ($allPaid)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[0.65rem] font-bold uppercase tracking-[0.16em] text-emerald-700 ring-1 ring-emerald-200">Paid</span>
                            @elseif ($groupPaidCount > 0)
                                <span class="inline-flex items-center rounded-full bg-emerald-50 px-2 py-0.5 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-emerald-700 ring-1 ring-emerald-200">{{ $groupPaidCount }}/{{ $groupBillable->count() }} paid</span>
                            @endif
                        @endif
                    </div>

                    <div class="flex flex-wrap items-center gap-1.5">
                        @if (! $allDone)
                            <form method="POST" action="{{ route('projects.tasks.group-action', $project) }}" class="inline">
                                @csrf
                                {!! $hiddenFields !!}
                                <input type="hidden" name="action" value="complete">
                                <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-emerald-700 transition hover:border-emerald-300">
                                    <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="2.6"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                    Complete all
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('projects.tasks.group-action', $project) }}" class="inline">
                                @csrf
                                {!! $hiddenFields !!}
                                <input type="hidden" name="action" value="reopen">
                                <button type="submit" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-slate-700 transition hover:border-slate-300">
                                    Reopen all
                                </button>
                            </form>
                        @endif

                        @if ($groupBillable->count() > 0)
                            @if (! $allPaid)
                                <form method="POST" action="{{ route('projects.tasks.group-action', $project) }}" class="inline">
                                    @csrf
                                    {!! $hiddenFields !!}
                                    <input type="hidden" name="action" value="mark_paid">
                                    <button type="submit" class="inline-flex items-center gap-1 rounded-lg border border-emerald-200 bg-white px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-emerald-700 transition hover:border-emerald-300">
                                        Mark paid
                                    </button>
                                </form>
                            @else
                                <form method="POST" action="{{ route('projects.tasks.group-action', $project) }}" class="inline">
                                    @csrf
                                    {!! $hiddenFields !!}
                                    <input type="hidden" name="action" value="mark_unpaid">
                                    <button type="submit" class="inline-flex items-center rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-slate-700 transition hover:border-slate-300">
                                        Mark unpaid
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                {{-- Group tasks --}}
                <div class="space-y-2 p-3">
                @foreach ($groupTasks as $task)
            @php
                $isDone = $task->status === \App\Enums\TaskStatus::Done;
                $overdue = $task->isOverdue();
            @endphp
            <div class="group rounded-2xl border border-slate-100 bg-white p-4 shadow-sm transition hover:border-slate-200">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div class="flex min-w-0 flex-1 items-start gap-3">
                        <form method="POST" action="{{ route('projects.tasks.toggle', [$project, $task]) }}" data-task-toggle data-task-id="{{ $task->id }}" class="pt-0.5">
                            @csrf
                            @method('PATCH')
                            <button
                                type="submit"
                                aria-label="Toggle task"
                                class="flex h-5 w-5 items-center justify-center rounded-md border-2 transition
                                    {{ $isDone
                                        ? 'border-emerald-500 bg-emerald-500 text-white'
                                        : 'border-slate-300 bg-white hover:border-slate-500' }}"
                            >
                                @if ($isDone)
                                    <svg viewBox="0 0 24 24" class="h-3 w-3" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                @endif
                            </button>
                        </form>

                        <div class="min-w-0 flex-1">
                            <div class="flex flex-wrap items-center gap-2">
                                <p class="text-sm font-semibold text-slate-950 {{ $isDone ? 'line-through opacity-60' : '' }}">
                                    {{ $task->title }}
                                </p>
                                <x-admin.status-badge :label="$task->status->label()" :classes="$task->status->badgeClasses()" />
                                <x-admin.status-badge :label="$task->priority->label()" :classes="$task->priority->badgeClasses()" />
                                @if ($task->source === 'meeting')
                                    <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-0.5 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-indigo-700 ring-1 ring-indigo-200">
                                        Meeting{{ $task->meeting_date ? ' · ' . $task->meeting_date->format('d M') : '' }}
                                    </span>
                                @endif
                                @if ($task->billable && $project->engagement_type?->value === 'one_time')
                                    <span class="inline-flex items-center rounded-full bg-purple-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-purple-700 ring-1 ring-purple-200">
                                        CR
                                    </span>
                                @endif
                                @if ($task->billable)
                                    <span class="inline-flex items-center gap-1 rounded-full bg-amber-50 px-2.5 py-0.5 text-[0.65rem] font-semibold uppercase tracking-[0.16em] text-amber-700 ring-1 ring-amber-200">
                                        {{ rtrim(rtrim(number_format((float) $task->hours_logged, 2), '0'), '.') ?: '0' }}h · Rs {{ number_format($task->billableAmount(), 0) }}
                                    </span>
                                    @if ($task->paid)
                                        <span class="inline-flex items-center gap-1 rounded-full bg-emerald-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-emerald-700 ring-1 ring-emerald-200">
                                            <svg viewBox="0 0 24 24" class="h-2.5 w-2.5" fill="none" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="m5 12 5 5L20 7"/></svg>
                                            Paid{{ $task->paid_at ? ' · ' . $task->paid_at->format('d M') : '' }}
                                        </span>
                                    @endif
                                    @if ($task->isBilled())
                                        <span class="inline-flex items-center rounded-full bg-sky-50 px-2.5 py-0.5 text-[0.65rem] font-bold uppercase tracking-[0.18em] text-sky-700 ring-1 ring-sky-200">
                                            Invoiced
                                        </span>
                                    @endif
                                @endif
                            </div>

                            <div class="mt-1 flex flex-wrap gap-x-4 gap-y-1 text-xs text-slate-500">
                                @if ($task->assignee)
                                    <span class="inline-flex items-center gap-1">
                                        <span class="text-slate-400">@</span>{{ $task->assignee }}
                                    </span>
                                @endif
                                @if ($task->due_date)
                                    <span class="inline-flex items-center gap-1 {{ $overdue ? 'font-semibold text-rose-600' : '' }}">
                                        Due {{ $task->due_date->format('d M Y') }}
                                        @if ($overdue) · overdue @endif
                                    </span>
                                @endif
                                @if ($task->description)
                                    <span class="truncate">{{ \Illuminate\Support\Str::limit($task->description, 80) }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center gap-2 opacity-100 md:opacity-0 md:transition md:group-hover:opacity-100">
                        <button
                            type="button"
                            data-task-edit-toggle="task-edit-{{ $task->id }}"
                            class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-700 transition hover:border-slate-300 hover:text-slate-950"
                        >
                            Edit
                        </button>
                        <form method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}" onsubmit="return confirm('Delete this task?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="inline-flex items-center rounded-xl border border-rose-200 bg-rose-50 px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>

                {{-- Edit pane --}}
                <form
                    id="task-edit-{{ $task->id }}"
                    method="POST"
                    action="{{ route('projects.tasks.update', [$project, $task]) }}"
                    class="mt-3 hidden rounded-xl border border-slate-200 bg-slate-50/70 p-3"
                >
                    @csrf
                    @method('PATCH')
                    <div class="grid gap-3 md:grid-cols-2">
                        <input type="text" name="title" required maxlength="255" value="{{ $task->title }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <input type="text" name="assignee" maxlength="120" value="{{ $task->assignee }}" placeholder="Assignee" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <select name="status" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                            @foreach ($taskStatuses as $s)
                                <option value="{{ $s->value }}" @selected($task->status === $s)>{{ $s->label() }}</option>
                            @endforeach
                        </select>
                        <select name="priority" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                            @foreach ($taskPriorities as $p)
                                <option value="{{ $p->value }}" @selected($task->priority === $p)>{{ $p->label() }}</option>
                            @endforeach
                        </select>
                        <input type="date" name="due_date" value="{{ $task->due_date?->toDateString() }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <input type="text" name="group_name" maxlength="120" value="{{ $task->group_name }}" placeholder="Group name (e.g. CR-014)" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                        <textarea name="description" rows="2" placeholder="Description (optional)" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100 md:col-span-2">{{ $task->description }}</textarea>
                    </div>
                    @if ($supportsHourly)
                        <div class="mt-3 grid items-end gap-3 md:grid-cols-[auto_auto_8rem_10rem_1fr]">
                            <label class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-amber-700">
                                <input type="checkbox" name="billable" value="1" @checked($task->billable) class="h-4 w-4 rounded border-slate-300 text-amber-600 focus:ring-amber-500">
                                Billable
                            </label>
                            <label class="inline-flex items-center gap-2 text-xs font-semibold uppercase tracking-[0.18em] text-emerald-700">
                                <input type="checkbox" name="paid" value="1" @checked($task->paid) class="h-4 w-4 rounded border-slate-300 text-emerald-600 focus:ring-emerald-500">
                                Paid
                            </label>
                            <input type="number" step="0.25" min="0" name="hours_logged" value="{{ $task->hours_logged }}" placeholder="Hours" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                            <input type="number" step="0.01" min="0" name="hourly_rate" value="{{ $task->hourly_rate }}" placeholder="Rate override" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-amber-400 focus:ring-4 focus:ring-amber-100">
                            <div class="text-xs text-slate-500">
                                Effective: Rs {{ number_format($task->effectiveHourlyRate(), 2) }}/hr<br>
                                Amount: <span class="font-semibold text-slate-900">Rs {{ number_format($task->billableAmount(), 2) }}</span>
                            </div>
                        </div>
                    @endif
                    <div class="mt-3 flex justify-end gap-2">
                        <button type="button" data-task-edit-cancel="task-edit-{{ $task->id }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-700 hover:border-slate-300">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex items-center rounded-xl bg-slate-950 px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-white shadow-md shadow-slate-900/15 hover:bg-slate-800">
                            Update
                        </button>
                    </div>
                </form>
            </div>
                @endforeach
                </div>
            </div>
        @empty
            <p class="rounded-2xl border border-dashed border-slate-200 px-4 py-10 text-center text-sm text-slate-500">
                No tasks for this filter. Add one above, or paste your meeting notes.
            </p>
        @endforelse
    </div>
    @endif
</section>
