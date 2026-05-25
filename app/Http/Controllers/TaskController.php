<?php

namespace App\Http\Controllers;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Task\BulkStoreTaskRequest;
use App\Http\Requests\Task\StoreTaskRequest;
use App\Http\Requests\Task\UpdateTaskRequest;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();
        $data['status'] = $data['status'] ?? TaskStatus::Todo->value;
        $data['priority'] = $data['priority'] ?? TaskPriority::Normal->value;
        $data['position'] = (int) ($project->tasks()->max('position') ?? 0) + 1;
        $data['source'] = 'manual';

        if (! empty($data['paid'])) {
            $data['paid_at'] = now();
        }

        $project->tasks()->create($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task added.');
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task): RedirectResponse
    {
        $this->ensureOwnership($project, $task);

        $data = $request->validated();

        if ($data['status'] === TaskStatus::Done->value && $task->status !== TaskStatus::Done) {
            $data['completed_at'] = now();
        } elseif ($data['status'] !== TaskStatus::Done->value) {
            $data['completed_at'] = null;
        }

        $newPaid = (bool) ($data['paid'] ?? false);
        if ($newPaid && ! $task->paid) {
            $data['paid_at'] = now();
        } elseif (! $newPaid && $task->paid) {
            $data['paid_at'] = null;
        }

        $task->update($data);

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task updated.');
    }

    public function destroy(Project $project, Task $task): RedirectResponse
    {
        $this->ensureOwnership($project, $task);

        $task->delete();

        return redirect()
            ->route('projects.show', $project)
            ->with('success', 'Task removed.');
    }

    public function toggle(Request $request, Project $project, Task $task): JsonResponse|RedirectResponse
    {
        $this->ensureOwnership($project, $task);

        if ($task->status === TaskStatus::Done) {
            $task->status = TaskStatus::Todo;
            $task->completed_at = null;
        } else {
            $task->status = TaskStatus::Done;
            $task->completed_at = now();
        }
        $task->save();

        if ($request->wantsJson()) {
            return response()->json([
                'id' => $task->id,
                'status' => $task->status->value,
                'completed_at' => $task->completed_at?->toIso8601String(),
            ]);
        }

        return redirect()->route('projects.show', $project);
    }

    public function reorder(Request $request, Project $project): JsonResponse
    {
        $data = $request->validate([
            'items' => ['required', 'array'],
            'items.*.id' => ['required', 'integer'],
            'items.*.status' => ['required', Rule::in(TaskStatus::values())],
            'items.*.position' => ['required', 'integer', 'min:0'],
        ]);

        $ids = collect($data['items'])->pluck('id')->all();

        $owned = Task::query()
            ->where('project_id', $project->id)
            ->whereIn('id', $ids)
            ->pluck('id')
            ->all();

        if (count($owned) !== count($ids)) {
            abort(403, 'Task ownership mismatch.');
        }

        DB::transaction(function () use ($data, $project) {
            foreach ($data['items'] as $item) {
                $update = [
                    'status' => $item['status'],
                    'position' => $item['position'],
                ];

                $task = Task::where('project_id', $project->id)->where('id', $item['id'])->first();
                if (! $task) {
                    continue;
                }

                if ($item['status'] === TaskStatus::Done->value && $task->status !== TaskStatus::Done) {
                    $update['completed_at'] = now();
                } elseif ($item['status'] !== TaskStatus::Done->value && $task->status === TaskStatus::Done) {
                    $update['completed_at'] = null;
                }

                $task->update($update);
            }
        });

        return response()->json(['ok' => true]);
    }

    public function bulkStore(BulkStoreTaskRequest $request, Project $project): RedirectResponse
    {
        $data = $request->validated();
        $meetingDate = isset($data['meeting_date']) ? Carbon::parse($data['meeting_date'])->toDateString() : null;
        $groupName = filled($data['group_name'] ?? null) ? trim($data['group_name']) : null;
        $billable = (bool) ($data['billable'] ?? false);
        $hourlyRate = $data['hourly_rate'] ?? null;

        $lines = preg_split('/\r\n|\r|\n/', $data['raw']);
        $position = (int) ($project->tasks()->max('position') ?? 0);
        $created = 0;

        foreach ($lines as $line) {
            $parsed = $this->parseLine($line);
            if ($parsed === null) {
                continue;
            }

            $position++;
            $project->tasks()->create([
                'title' => $parsed['title'],
                'priority' => $parsed['priority'],
                'due_date' => $parsed['due_date'],
                'assignee' => $parsed['assignee'],
                'status' => TaskStatus::Todo->value,
                'position' => $position,
                'source' => 'meeting',
                'meeting_date' => $meetingDate,
                'group_name' => $groupName,
                'billable' => $billable,
                'hourly_rate' => $hourlyRate,
            ]);
            $created++;
        }

        return redirect()
            ->route('projects.show', $project)
            ->with('success', "Added {$created} task(s) from meeting notes.");
    }

    public function groupAction(Request $request, Project $project): RedirectResponse
    {
        $data = $request->validate([
            'action' => ['required', Rule::in(['complete', 'reopen', 'mark_paid', 'mark_unpaid', 'delete'])],
            'group_name' => ['nullable', 'string', 'max:120'],
            'meeting_date' => ['nullable', 'date'],
            'ungrouped' => ['nullable', 'boolean'],
        ]);

        $query = $project->tasks();

        if (! empty($data['ungrouped'])) {
            $query->whereNull('group_name')->whereNull('meeting_date');
        } elseif (filled($data['group_name'] ?? null)) {
            $query->where('group_name', $data['group_name']);
        } elseif (filled($data['meeting_date'] ?? null)) {
            $query->whereNull('group_name')->whereDate('meeting_date', $data['meeting_date']);
        } else {
            return back()->with('error', 'No group selected.');
        }

        $tasks = $query->get();
        if ($tasks->isEmpty()) {
            return back()->with('error', 'Group is empty.');
        }

        $count = 0;
        DB::transaction(function () use ($tasks, $data, &$count) {
            foreach ($tasks as $task) {
                switch ($data['action']) {
                    case 'complete':
                        if ($task->status !== TaskStatus::Done) {
                            $task->status = TaskStatus::Done;
                            $task->completed_at = now();
                            $task->save();
                            $count++;
                        }
                        break;
                    case 'reopen':
                        if ($task->status === TaskStatus::Done) {
                            $task->status = TaskStatus::Todo;
                            $task->completed_at = null;
                            $task->save();
                            $count++;
                        }
                        break;
                    case 'mark_paid':
                        if (! $task->paid) {
                            $task->paid = true;
                            $task->paid_at = now();
                            $task->save();
                            $count++;
                        }
                        break;
                    case 'mark_unpaid':
                        if ($task->paid) {
                            $task->paid = false;
                            $task->paid_at = null;
                            $task->save();
                            $count++;
                        }
                        break;
                    case 'delete':
                        $task->delete();
                        $count++;
                        break;
                }
            }
        });

        $messages = [
            'complete' => "Completed {$count} task(s).",
            'reopen' => "Reopened {$count} task(s).",
            'mark_paid' => "Marked {$count} task(s) as paid.",
            'mark_unpaid' => "Marked {$count} task(s) as unpaid.",
            'delete' => "Deleted {$count} task(s).",
        ];

        return redirect()
            ->route('projects.show', $project)
            ->with('success', $messages[$data['action']]);
    }

    /**
     * Parse one line. Returns null if blank.
     *
     * Tokens recognised anywhere in the line:
     *   - leading "- " or "* " or "1." bullet — stripped
     *   - "!!"  => urgent
     *   - "!"   => high   (when not followed by another !)
     *   - "@name" => assignee (single word, no spaces)
     *   - "^YYYY-MM-DD" => due date
     *
     * @return array{title:string, priority:string, due_date:?string, assignee:?string}|null
     */
    private function parseLine(string $line): ?array
    {
        $trim = trim($line);
        if ($trim === '') {
            return null;
        }

        $trim = preg_replace('/^\s*(?:[-*•]|\d+[.)])\s+/u', '', $trim) ?? $trim;

        $priority = TaskPriority::Normal->value;
        if (preg_match('/(^|\s)!!(\s|$)/u', $trim)) {
            $priority = TaskPriority::Urgent->value;
            $trim = preg_replace('/(^|\s)!!(\s|$)/u', '$1$2', $trim) ?? $trim;
        } elseif (preg_match('/(^|\s)!(\s|$)/u', $trim)) {
            $priority = TaskPriority::High->value;
            $trim = preg_replace('/(^|\s)!(\s|$)/u', '$1$2', $trim) ?? $trim;
        }

        $assignee = null;
        if (preg_match('/(^|\s)@([A-Za-z0-9_.\-]{1,60})/u', $trim, $m)) {
            $assignee = $m[2];
            $trim = preg_replace('/(^|\s)@[A-Za-z0-9_.\-]{1,60}/u', '$1', $trim, 1) ?? $trim;
        }

        $dueDate = null;
        if (preg_match('/(^|\s)\^(\d{4}-\d{2}-\d{2})/u', $trim, $m)) {
            try {
                $dueDate = Carbon::parse($m[2])->toDateString();
            } catch (\Throwable) {
                $dueDate = null;
            }
            $trim = preg_replace('/(^|\s)\^\d{4}-\d{2}-\d{2}/u', '$1', $trim, 1) ?? $trim;
        }

        $title = trim(preg_replace('/\s+/u', ' ', $trim) ?? $trim);
        if ($title === '') {
            return null;
        }

        return [
            'title' => mb_substr($title, 0, 255),
            'priority' => $priority,
            'due_date' => $dueDate,
            'assignee' => $assignee,
        ];
    }

    private function ensureOwnership(Project $project, Task $task): void
    {
        abort_unless($task->project_id === $project->id, 404);
    }
}
