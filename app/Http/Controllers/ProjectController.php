<?php

namespace App\Http\Controllers;

use App\Enums\EngagementType;
use App\Enums\ProjectStatus;
use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Project\StoreProjectRequest;
use App\Http\Requests\Project\UpdateProjectRequest;
use App\Models\Customer;
use App\Models\Project;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProjectController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $customerId = $request->input('customer_id');
        $engagement = $request->input('engagement_type');

        $projects = Project::query()
            ->with('customer')
            ->search($search)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($customerId, fn ($query) => $query->where('customer_id', $customerId))
            ->when($engagement, fn ($query) => $query->where('engagement_type', $engagement))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
            'engagementTypes' => EngagementType::cases(),
            'search' => $search,
            'selectedStatus' => $status,
            'selectedCustomerId' => $customerId,
            'selectedEngagement' => $engagement,
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'project' => new Project(),
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
            'engagementTypes' => EngagementType::cases(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Request $request, Project $project): View
    {
        $project->load(['customer', 'invoices.items', 'tasks']);

        $taskFilter = $request->input('taskFilter', 'all');
        $taskView = $request->input('taskView') === 'kanban' ? 'kanban' : 'list';
        $allTasks = $project->tasks;
        $today = now()->startOfDay();
        $weekEnd = now()->endOfWeek();

        $taskStats = [
            'total' => $allTasks->count(),
            'open' => $allTasks->where('status', '!=', TaskStatus::Done)->count(),
            'overdue' => $allTasks->filter(fn ($t) => $t->isOverdue())->count(),
            'done' => $allTasks->where('status', TaskStatus::Done)->count(),
        ];

        $tasks = match ($taskFilter) {
            'open' => $allTasks->where('status', '!=', TaskStatus::Done),
            'overdue' => $allTasks->filter(fn ($t) => $t->isOverdue()),
            'week' => $allTasks->filter(
                fn ($t) => $t->due_date
                    && $t->due_date->between($today, $weekEnd)
                    && $t->status !== TaskStatus::Done
            ),
            'done' => $allTasks->where('status', TaskStatus::Done),
            default => $allTasks,
        };

        $tasksByStatus = collect(TaskStatus::cases())
            ->mapWithKeys(fn (TaskStatus $s) => [
                $s->value => $tasks->where('status', $s)->sortBy('position')->values(),
            ]);

        return view('projects.show', [
            'project' => $project,
            'tasks' => $tasks->values(),
            'tasksByStatus' => $tasksByStatus,
            'taskStats' => $taskStats,
            'taskFilter' => $taskFilter,
            'taskView' => $taskView,
            'taskStatuses' => TaskStatus::cases(),
            'taskPriorities' => TaskPriority::cases(),
        ]);
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
            'engagementTypes' => EngagementType::cases(),
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project): RedirectResponse
    {
        $project->update($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        if ($project->invoices()->exists()) {
            return back()->with('error', 'Delete the project invoices before removing this project.');
        }

        $project->delete();

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project deleted successfully.');
    }
}
