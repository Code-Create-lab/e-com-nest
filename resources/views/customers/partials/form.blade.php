@csrf

<div class="grid gap-5 md:grid-cols-2">
    <div>
        <label for="name" class="mb-2 block text-sm font-medium text-slate-700">Name</label>
        <input id="name" name="name" type="text" value="{{ old('name', $customer->name) }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="company_name" class="mb-2 block text-sm font-medium text-slate-700">Company Name</label>
        <input id="company_name" name="company_name" type="text" value="{{ old('company_name', $customer->company_name) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="email" class="mb-2 block text-sm font-medium text-slate-700">Email</label>
        <input id="email" name="email" type="email" value="{{ old('email', $customer->email) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div>
        <label for="phone" class="mb-2 block text-sm font-medium text-slate-700">Phone</label>
        <input id="phone" name="phone" type="text" value="{{ old('phone', $customer->phone) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
    </div>

    <div class="md:col-span-2">
        <label for="address" class="mb-2 block text-sm font-medium text-slate-700">Address</label>
        <textarea id="address" name="address" rows="4" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('address', $customer->address) }}</textarea>
    </div>

    <div class="md:col-span-2">
        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Environment URLs</p>
    </div>

    <div>
        <label for="live_url" class="mb-2 block text-sm font-medium text-slate-700">Live URL</label>
        <input id="live_url" name="live_url" type="url" inputmode="url" placeholder="https://example.com" value="{{ old('live_url', $customer->live_url) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('live_url')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div>
        <label for="stg_url" class="mb-2 block text-sm font-medium text-slate-700">Staging URL</label>
        <input id="stg_url" name="stg_url" type="url" inputmode="url" placeholder="https://stg.example.com" value="{{ old('stg_url', $customer->stg_url) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('stg_url')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>

    <div class="md:col-span-2">
        <label for="system_monitor_url" class="mb-2 block text-sm font-medium text-slate-700">System Monitor URL</label>
        <input id="system_monitor_url" name="system_monitor_url" type="url" inputmode="url" placeholder="https://status.example.com" value="{{ old('system_monitor_url', $customer->system_monitor_url) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        @error('system_monitor_url')<p class="mt-2 text-xs text-rose-600">{{ $message }}</p>@enderror
    </div>
</div>

<div class="mt-8 flex flex-wrap gap-3">
    <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
        {{ $submitLabel }}
    </button>
    <a href="{{ route('customers.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
        Cancel
    </a>
</div>
