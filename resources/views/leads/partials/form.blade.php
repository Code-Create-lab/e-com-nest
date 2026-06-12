@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $lead->name) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="source" class="mb-2 block text-sm font-medium text-slate-700">Source</label>
        <input id="source" name="source" type="text" value="{{ old('source', $lead->source) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $lead->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">Phone</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $lead->phone) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="website" class="mb-2 block text-sm font-medium text-slate-700">Website <span class="text-slate-400">(optional)</span></label>
        <input id="website" name="website" type="text" value="{{ old('website', $lead->website) }}" placeholder="https://example.com" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="city" class="mb-2 block text-sm font-medium text-slate-700">City <span class="text-slate-400">(optional)</span></label>
        <input id="city" name="city" type="text" value="{{ old('city', $lead->city) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="industry" class="mb-2 block text-sm font-medium text-slate-700">Industry <span class="text-slate-400">(optional)</span></label>
        <input id="industry" name="industry" type="text" value="{{ old('industry', $lead->industry) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="source_handle" class="mb-2 block text-sm font-medium text-slate-700">Handle <span class="text-slate-400">(optional)</span></label>
        <input id="source_handle" name="source_handle" type="text" value="{{ old('source_handle', $lead->source_handle) }}" placeholder="@username" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="followers" class="mb-2 block text-sm font-medium text-slate-700">Followers <span class="text-slate-400">(optional)</span></label>
        <input id="followers" name="followers" type="number" min="0" value="{{ old('followers', $lead->followers) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
        <select id="status" name="status" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
            @foreach ($statuses as $status)
                <option value="{{ $status->value }}" @selected(old('status', $lead->status?->value ?? 'new') === $status->value)>{{ $status->label() }}</option>
            @endforeach
        </select>
    </div>

    <div class="md:col-span-2">
        <label for="bio" class="mb-2 block text-sm font-medium text-slate-700">Bio <span class="text-slate-400">(optional)</span></label>
        <textarea id="bio" name="bio" rows="3" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('bio', $lead->bio) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
        <textarea id="notes" name="notes" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('notes', $lead->notes) }}</textarea>
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('leads.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
        Cancel
    </a>
</div>
