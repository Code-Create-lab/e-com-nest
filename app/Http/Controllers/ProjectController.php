<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
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

        $projects = Project::query()
            ->with('customer')
            ->search($search)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($customerId, fn ($query) => $query->where('customer_id', $customerId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('projects.index', [
            'projects' => $projects,
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
            'search' => $search,
            'selectedStatus' => $status,
            'selectedCustomerId' => $customerId,
        ]);
    }

    public function create(): View
    {
        return view('projects.create', [
            'project' => new Project(),
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
        ]);
    }

    public function store(StoreProjectRequest $request): RedirectResponse
    {
        Project::create($request->validated());

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function show(Project $project): View
    {
        $project->load(['customer', 'invoices.items']);

        return view('projects.show', [
            'project' => $project,
        ]);
    }

    public function edit(Project $project): View
    {
        return view('projects.edit', [
            'project' => $project,
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => ProjectStatus::cases(),
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
