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

    <div class="md:col-span-2 mt-2 border-t border-slate-100 pt-5">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Engagement</p>
    </div>

    <div class="md:col-span-2">
        <label for="engagement_type" class="mb-2 block text-sm font-medium text-slate-700">Engagement Type</label>
        <select id="engagement_type" name="engagement_type" required data-engagement-toggle class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @foreach ($engagementTypes as $type)
                <option value="{{ $type->value }}" @selected(old('engagement_type', $project->engagement_type?->value ?? 'one_time') === $type->value)>{{ $type->label() }}</option>
            @endforeach
        </select>
        <p class="mt-2 text-xs text-slate-500">"New Development" = fixed scope. "Monthly Support" = recurring retainer. "Hourly" = ad-hoc billing per task.</p>
        @error('engagement_type')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="hourly_rate" class="mb-2 block text-sm font-medium text-slate-700">Default Hourly Rate (Rs)</label>
        <input id="hourly_rate" name="hourly_rate" type="number" step="0.01" min="0" value="{{ old('hourly_rate', $project->hourly_rate) }}" placeholder="1500.00" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        <p class="mt-2 text-xs text-slate-500">Used for billable tasks. For Monthly Support, applies to overage hours beyond retainer.</p>
        @error('hourly_rate')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    @php
        $isRetainer = old('engagement_type', $project->engagement_type?->value) === 'monthly_retainer';
    @endphp

    <div data-retainer-fields class="md:col-span-2 grid gap-5 md:grid-cols-2 {{ $isRetainer ? '' : 'hidden' }}">
        <div>
            <label for="monthly_amount" class="mb-2 block text-sm font-medium text-slate-700">Monthly Amount (Rs)</label>
            <input id="monthly_amount" name="monthly_amount" type="number" step="0.01" min="0" value="{{ old('monthly_amount', $project->monthly_amount) }}" placeholder="15000.00" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @error('monthly_amount')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="billing_day" class="mb-2 block text-sm font-medium text-slate-700">Billing Day (1-31)</label>
            <input id="billing_day" name="billing_day" type="number" min="1" max="31" value="{{ old('billing_day', $project->billing_day) }}" placeholder="1" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @error('billing_day')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="hours_per_month" class="mb-2 block text-sm font-medium text-slate-700">Hours / Month</label>
            <input id="hours_per_month" name="hours_per_month" type="number" min="0" max="1000" value="{{ old('hours_per_month', $project->hours_per_month) }}" placeholder="20" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @error('hours_per_month')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>

        <div>
            <label for="support_renews_on" class="mb-2 block text-sm font-medium text-slate-700">Next Renewal</label>
            <input id="support_renews_on" name="support_renews_on" type="date" value="{{ old('support_renews_on', $project->support_renews_on?->format('Y-m-d')) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @error('support_renews_on')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
        </div>
    </div>
</div>

<script>
    (function () {
        const toggle = document.querySelector('[data-engagement-toggle]');
        const block = document.querySelector('[data-retainer-fields]');
        if (!toggle || !block) return;
        const update = () => block.classList.toggle('hidden', toggle.value !== 'monthly_retainer');
        toggle.addEventListener('change', update);
        update();
    })();
</script>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('projects.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
        Cancel
    </a>
</div>
