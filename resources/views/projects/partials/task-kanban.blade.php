@props([
    'project',
    'tasksByStatus',
    'taskStatuses',
])

@php
    $columnAccent = [
        'todo' => ['border' => 'border-slate-200', 'header' => 'text-slate-600', 'dot' => 'bg-slate-400'],
        'in_progress' => ['border' => 'border-sky-200', 'header' => 'text-sky-700', 'dot' => 'bg-sky-500'],
        'blocked' => ['border' => 'border-rose-200', 'header' => 'text-rose-700', 'dot' => 'bg-rose-500'],
        'done' => ['border' => 'border-emerald-200', 'header' => 'text-emerald-700', 'dot' => 'bg-emerald-500'],
    ];
@endphp

<div
    data-tasks-kanban
    data-project-id="{{ $project->id }}"
    data-reorder-url="{{ route('projects.tasks.reorder', $project) }}"
    data-csrf="{{ csrf_token() }}"
    data-motion-reveal
    data-motion-stagger
    data-motion-variant="up"
    class="mt-6 grid gap-4 lg:grid-cols-4"
>
    @foreach ($taskStatuses as $status)
        @php
            $columnTasks = $tasksByStatus[$status->value] ?? collect();
            $accent = $columnAccent[$status->value];
        @endphp
        <div class="kanban-column flex flex-col rounded-2xl border {{ $accent['border'] }} bg-white/95 p-3">
            <div class="flex items-center justify-between gap-2 px-1 pb-3">
                <div class="flex items-center gap-2">
                    <span class="h-2 w-2 rounded-full {{ $accent['dot'] }}"></span>
                    <p class="text-[0.7rem] font-semibold uppercase tracking-[0.2em] {{ $accent['header'] }}">{{ $status->label() }}</p>
                </div>
                <span class="rounded-full bg-slate-100 px-2 py-0.5 text-[0.65rem] font-semibold text-slate-600">
                    {{ $columnTasks->count() }}
                </span>
            </div>

            <div
                class="kanban-list flex min-h-[10rem] flex-col gap-2"
                data-kanban-list
                data-status="{{ $status->value }}"
            >
                @foreach ($columnTasks as $task)
                    @php
                        $overdue = $task->isOverdue();
                        $isDone = $task->status === \App\Enums\TaskStatus::Done;
                    @endphp
                    <article
                        class="kanban-card rounded-xl border border-slate-100 bg-white p-3 shadow-sm transition hover:border-slate-300 hover:shadow"
                        data-task-card
                        data-task-id="{{ $task->id }}"
                    >
                        <div class="flex items-start gap-2">
                            <span class="kanban-handle mt-1 cursor-grab text-slate-300 hover:text-slate-500" aria-hidden="true">
                                <svg viewBox="0 0 24 24" class="h-3.5 w-3.5" fill="currentColor"><path d="M9 5a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm9-14a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Zm0 7a1.5 1.5 0 1 1-3 0 1.5 1.5 0 0 1 3 0Z"/></svg>
                            </span>
                            <div class="min-w-0 flex-1">
                                <p class="text-sm font-semibold text-slate-950 {{ $isDone ? 'line-through opacity-60' : '' }}">
                                    {{ $task->title }}
                                </p>

                                <div class="mt-2 flex flex-wrap items-center gap-1.5">
                                    <span class="inline-flex items-center rounded-full px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.14em] ring-1 {{ $task->priority->badgeClasses() }}">
                                        {{ $task->priority->label() }}
                                    </span>
                                    @if ($task->source === 'meeting')
                                        <span class="inline-flex items-center rounded-full bg-indigo-50 px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.14em] text-indigo-700 ring-1 ring-indigo-200">
                                            Meeting
                                        </span>
                                    @endif
                                </div>

                                <div class="mt-2 flex flex-wrap gap-x-3 gap-y-1 text-[0.7rem] text-slate-500">
                                    @if ($task->assignee)
                                        <span class="inline-flex items-center gap-1"><span class="text-slate-400">@</span>{{ $task->assignee }}</span>
                                    @endif
                                    @if ($task->due_date)
                                        <span class="inline-flex items-center {{ $overdue ? 'font-semibold text-rose-600' : '' }}">
                                            {{ $task->due_date->format('d M') }}@if ($overdue) · overdue @endif
                                        </span>
                                    @endif
                                </div>
                            </div>
                        </div>

                        <div class="mt-2 flex items-center justify-end gap-1">
                            <button
                                type="button"
                                data-task-edit-toggle="task-edit-{{ $task->id }}"
                                class="rounded-md px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.16em] text-slate-500 hover:bg-slate-100 hover:text-slate-900"
                            >
                                Edit
                            </button>
                            <form method="POST" action="{{ route('projects.tasks.destroy', [$project, $task]) }}" onsubmit="return confirm('Delete this task?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="rounded-md px-2 py-0.5 text-[0.6rem] font-semibold uppercase tracking-[0.16em] text-rose-500 hover:bg-rose-50 hover:text-rose-700">
                                    Delete
                                </button>
                            </form>
                        </div>
                    </article>
                @endforeach

                <div class="kanban-empty rounded-xl border border-dashed border-slate-200 p-3 text-center text-[0.7rem] text-slate-400 {{ $columnTasks->count() ? 'hidden' : '' }}">
                    Drop tasks here
                </div>
            </div>
        </div>
    @endforeach
</div>

{{-- Edit forms for kanban cards (hidden, revealed by data-task-edit-toggle) --}}
<div class="mt-4 space-y-2">
    @foreach ($taskStatuses as $status)
        @foreach (($tasksByStatus[$status->value] ?? collect()) as $task)
            <form
                id="task-edit-{{ $task->id }}"
                method="POST"
                action="{{ route('projects.tasks.update', [$project, $task]) }}"
                class="hidden rounded-xl border border-slate-200 bg-slate-50/80 p-3"
            >
                @csrf
                @method('PATCH')
                <p class="mb-2 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-500">Editing: {{ $task->title }}</p>
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
                    <textarea name="description" rows="2" placeholder="Description (optional)" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm shadow-sm outline-none focus:border-sky-400 focus:ring-4 focus:ring-sky-100 md:col-span-2">{{ $task->description }}</textarea>
                </div>
                <div class="mt-3 flex justify-end gap-2">
                    <button type="button" data-task-edit-cancel="task-edit-{{ $task->id }}" class="inline-flex items-center rounded-xl border border-slate-200 bg-white px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-slate-700 hover:border-slate-300">
                        Cancel
                    </button>
                    <button type="submit" class="inline-flex items-center rounded-xl bg-slate-950 px-3 py-1.5 text-[0.65rem] font-semibold uppercase tracking-[0.18em] text-white shadow-md shadow-slate-900/15 hover:bg-slate-800">
                        Update
                    </button>
                </div>
            </form>
        @endforeach
    @endforeach
</div>
