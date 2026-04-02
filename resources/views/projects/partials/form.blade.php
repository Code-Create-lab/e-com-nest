@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="customer_id" class="mb-2 block text-sm font-medium text-slate-700">Customer</label>
        <select id="customer_id" name="customer_id" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            <option value="">Select a customer</option>
            @foreach ($customers as $customerOption)
                <option value="{{ $customerOption->id }}" @selected((string) old('customer_id', $project->customer_id) === (string) $customerOption->id)>{{ $customerOption->name }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="project_name" class="mb-2 block text-sm font-medium text-slate-700">Project Name</label>
        <input id="project_name" name="project_name" type="text" value="{{ old('project_name', $project->project_name) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="start_date" class="mb-2 block text-sm font-medium text-slate-700">Start Date</label>
        <input id="start_date" name="start_date" type="date" value="{{ old('start_date', $project->start_date?->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="end_date" class="mb-2 block text-sm font-medium text-slate-700">End Date</label>
        <input id="end_date" name="end_date" type="date" value="{{ old('end_date', $project->end_date?->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
        <select id="status" name="status" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(old('status', $project->status?->value ?? 'planning') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="progress" class="mb-2 block text-sm font-medium text-slate-700">Progress (%)</label>
        <input id="progress" name="progress" type="number" min="0" max="100" value="{{ old('progress', $project->progress ?? 0) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div class="md:col-span-2">
        <label for="description" class="mb-2 block text-sm font-medium text-slate-700">Description</label>
        <textarea id="description" name="description" rows="5" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('description', $project->description) }}</textarea>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
        Cancel
    </a>
</div>
