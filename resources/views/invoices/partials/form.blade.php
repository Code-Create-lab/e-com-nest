@csrf

@php
    $items = old('items');

    if (! $items) {
        $items = isset($invoice) && $invoice->relationLoaded('items') && $invoice->items->isNotEmpty()
            ? $invoice->items->map(fn ($item) => [
                'name' => $item->name,
                'quantity' => $item->quantity,
                'price' => $item->price,
            ])->toArray()
            : [['name' => '', 'quantity' => 1, 'price' => '']];
    }
@endphp

<div data-invoice-form class="space-y-8">
    <div class="overflow-hidden rounded-[1.85rem] border border-white/70 bg-white shadow-xl shadow-slate-900/5">
        <div class="bg-[linear-gradient(135deg,_#0f3d91_0%,_#1d5fd0_45%,_#ff9f1c_100%)] px-6 py-5 text-white">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="flex items-center gap-4">
                    <div class="flex h-16 w-16 items-center justify-center rounded-[1.2rem] bg-white/95 p-2 shadow-lg shadow-slate-950/10">
                        <img src="{{ asset('logo.jpeg') }}" alt="eComNest Soultions logo" class="h-full w-full rounded-[0.9rem] object-contain">
                    </div>
                    <div>
                        <p class="text-xs uppercase tracking-[0.3em] text-white/70">Invoice Builder</p>
                        <h2 class="mt-2 text-2xl font-semibold">eComNest Soultions</h2>
                        <p class="mt-2 text-sm text-white/80">Create a cleaner invoice with customer linkage, live totals, and print-ready output.</p>
                    </div>
                </div>

                <div class="rounded-2xl border border-white/15 bg-white/10 px-4 py-3 text-sm backdrop-blur">
                    Invoice number will be auto-generated on save
                </div>
            </div>
        </div>
    </div>

    <div class="grid gap-5 md:grid-cols-2 xl:grid-cols-4">
        <div>
            <label for="customer_id" class="mb-2 block text-sm font-medium text-slate-700">Customer</label>
            <select id="customer_id" name="customer_id" required data-customer-select class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                <option value="">Select a customer</option>
                @foreach ($customers as $customer)
                    <option value="{{ $customer->id }}" @selected((string) old('customer_id', $invoice->customer_id) === (string) $customer->id)>{{ $customer->name }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="project_id" class="mb-2 block text-sm font-medium text-slate-700">Project</label>
            <select id="project_id" name="project_id" data-project-select class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                <option value="">Invoice at customer level</option>
                @foreach ($projects as $project)
                    <option value="{{ $project->id }}" data-customer-id="{{ $project->customer_id }}" @selected((string) old('project_id', $invoice->project_id) === (string) $project->id)>
                        {{ $project->project_name }} | {{ $project->customer?->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="issue_date" class="mb-2 block text-sm font-medium text-slate-700">Issue Date</label>
            <input id="issue_date" name="issue_date" type="date" value="{{ old('issue_date', optional($invoice->issue_date)->format('Y-m-d') ?: $invoice->issue_date) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        </div>

        <div>
            <label for="due_date" class="mb-2 block text-sm font-medium text-slate-700">Due Date</label>
            <input id="due_date" name="due_date" type="date" value="{{ old('due_date', optional($invoice->due_date)->format('Y-m-d') ?: $invoice->due_date) }}" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        </div>

        <div>
            <label for="status" class="mb-2 block text-sm font-medium text-slate-700">Status</label>
            <select id="status" name="status" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                @foreach ($statuses as $status)
                    <option value="{{ $status->value }}" @selected(old('status', $invoice->status?->value ?? 'unpaid') === $status->value)>{{ $status->label() }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="discount" class="mb-2 block text-sm font-medium text-slate-700">Discount</label>
            <input id="discount" name="discount" type="number" min="0" step="0.01" value="{{ old('discount', $invoice->discount ?? 0) }}" data-discount-input class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
        </div>

        <div class="md:col-span-2 xl:col-span-2">
            <label for="notes" class="mb-2 block text-sm font-medium text-slate-700">Notes</label>
            <textarea id="notes" name="notes" rows="1" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">{{ old('notes', $invoice->notes) }}</textarea>
        </div>
    </div>

    <div class="rounded-[1.75rem] border border-slate-200 bg-[linear-gradient(180deg,_#f8fafc,_#ffffff)] p-5 shadow-lg shadow-slate-900/5">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <h2 class="text-lg font-semibold text-slate-950">Invoice items</h2>
                <p class="text-sm text-slate-500">Add line items. Invoice number is generated automatically when you save.</p>
            </div>
            <button type="button" data-add-item class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-4 py-2 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
                Add item
            </button>
        </div>

        <div class="mt-5 overflow-x-auto">
            <table class="min-w-full text-sm">
                <thead>
                    <tr class="text-left text-xs uppercase tracking-[0.2em] text-slate-500">
                        <th class="pb-3 pr-4">Item</th>
                        <th class="pb-3 pr-4">Qty</th>
                        <th class="pb-3 pr-4">Price</th>
                        <th class="pb-3 pr-4">Total</th>
                        <th class="pb-3 text-right">Action</th>
                    </tr>
                </thead>
                <tbody data-items-body class="divide-y divide-slate-200">
                    @foreach ($items as $index => $item)
                        <tr data-item-row>
                            <td class="py-3 pr-4">
                                <input name="items[{{ $index }}][name]" type="text" value="{{ $item['name'] }}" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                            </td>
                            <td class="py-3 pr-4">
                                <input name="items[{{ $index }}][quantity]" type="number" min="1" value="{{ $item['quantity'] }}" required data-item-quantity class="w-24 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                            </td>
                            <td class="py-3 pr-4">
                                <input name="items[{{ $index }}][price]" type="number" min="0" step="0.01" value="{{ $item['price'] }}" required data-item-price class="w-32 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                            </td>
                            <td class="py-3 pr-4 text-sm font-semibold text-slate-900" data-item-total>Rs 0.00</td>
                            <td class="py-3 text-right">
                                <button type="button" data-remove-item class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                                    Remove
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <template data-item-template>
            <tr data-item-row>
                <td class="py-3 pr-4">
                    <input name="items[__INDEX__][name]" type="text" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </td>
                <td class="py-3 pr-4">
                    <input name="items[__INDEX__][quantity]" type="number" min="1" value="1" required data-item-quantity class="w-24 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </td>
                <td class="py-3 pr-4">
                    <input name="items[__INDEX__][price]" type="number" min="0" step="0.01" value="0" required data-item-price class="w-32 rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm shadow-sm outline-none transition focus:border-sky-400 focus:ring-4 focus:ring-sky-100">
                </td>
                <td class="py-3 pr-4 text-sm font-semibold text-slate-900" data-item-total>Rs 0.00</td>
                <td class="py-3 text-right">
                    <button type="button" data-remove-item class="inline-flex items-center rounded-2xl border border-rose-200 bg-rose-50 px-4 py-2 text-xs font-semibold uppercase tracking-[0.2em] text-rose-700 transition hover:border-rose-300 hover:text-rose-900">
                        Remove
                    </button>
                </td>
            </tr>
        </template>

        <div class="mt-6 grid gap-3 sm:grid-cols-3">
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Subtotal</p>
                <p class="mt-2 text-xl font-semibold text-slate-950" data-subtotal>Rs 0.00</p>
            </div>
            <div class="rounded-2xl border border-slate-200 bg-white px-4 py-4">
                <p class="text-xs uppercase tracking-[0.2em] text-slate-500">Discount</p>
                <p class="mt-2 text-xl font-semibold text-slate-950" data-discount>Rs 0.00</p>
            </div>
            <div class="rounded-2xl bg-[linear-gradient(135deg,_#0f3d91,_#ff9f1c)] px-4 py-4 text-white">
                <p class="text-xs uppercase tracking-[0.2em] text-white/70">Final Amount</p>
                <p class="mt-2 text-xl font-semibold" data-final-total>Rs 0.00</p>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap gap-3">
        <button type="submit" class="inline-flex items-center rounded-2xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-900/15 transition hover:bg-slate-800">
            {{ $submitLabel }}
        </button>
        <a href="{{ route('invoices.index') }}" class="inline-flex items-center rounded-2xl border border-slate-200 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-slate-300 hover:text-slate-950">
            Cancel
        </a>
    </div>
</div>

@once
    @push('scripts')
        <script>
            document.querySelectorAll('[data-invoice-form]').forEach((form) => {
                const itemsBody = form.querySelector('[data-items-body]');
                const template = form.querySelector('[data-item-template]');
                const addButton = form.querySelector('[data-add-item]');
                const discountInput = form.querySelector('[data-discount-input]');
                const customerSelect = form.querySelector('[data-customer-select]');
                const projectSelect = form.querySelector('[data-project-select]');

                const currency = (value) => `Rs ${Number(value || 0).toFixed(2)}`;

                const updateProjectOptions = () => {
                    const customerId = customerSelect.value;

                    Array.from(projectSelect.options).forEach((option, index) => {
                        if (index === 0) {
                            option.hidden = false;
                            option.disabled = false;
                            return;
                        }

                        const matches = !customerId || option.dataset.customerId === customerId;
                        option.hidden = !matches;
                        option.disabled = !matches;

                        if (!matches && option.selected) {
                            projectSelect.value = '';
                        }
                    });
                };

                const recalculateTotals = () => {
                    let subtotal = 0;

                    itemsBody.querySelectorAll('[data-item-row]').forEach((row) => {
                        const quantity = Number(row.querySelector('[data-item-quantity]').value || 0);
                        const price = Number(row.querySelector('[data-item-price]').value || 0);
                        const total = quantity * price;

                        row.querySelector('[data-item-total]').textContent = currency(total);
                        subtotal += total;
                    });

                    const discount = Number(discountInput.value || 0);
                    const finalTotal = Math.max(subtotal - discount, 0);

                    form.querySelector('[data-subtotal]').textContent = currency(subtotal);
                    form.querySelector('[data-discount]').textContent = currency(discount);
                    form.querySelector('[data-final-total]').textContent = currency(finalTotal);
                };

                addButton.addEventListener('click', () => {
                    const index = itemsBody.querySelectorAll('[data-item-row]').length;
                    const html = template.innerHTML.replaceAll('__INDEX__', index);
                    itemsBody.insertAdjacentHTML('beforeend', html);
                    recalculateTotals();
                });

                itemsBody.addEventListener('click', (event) => {
                    const button = event.target.closest('[data-remove-item]');

                    if (!button) {
                        return;
                    }

                    const rows = itemsBody.querySelectorAll('[data-item-row]');

                    if (rows.length === 1) {
                        rows[0].querySelectorAll('input').forEach((input) => {
                            input.value = input.name.includes('[quantity]') ? 1 : (input.type === 'number' ? 0 : '');
                        });
                    } else {
                        button.closest('[data-item-row]').remove();
                    }

                    recalculateTotals();
                });

                itemsBody.addEventListener('input', recalculateTotals);
                discountInput.addEventListener('input', recalculateTotals);
                customerSelect.addEventListener('change', updateProjectOptions);

                updateProjectOptions();
                recalculateTotals();
            });
        </script>
    @endpush
@endonce
