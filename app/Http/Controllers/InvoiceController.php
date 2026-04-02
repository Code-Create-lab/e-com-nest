<?php

namespace App\Http\Controllers;

use App\Enums\InvoiceStatus;
use App\Http\Requests\Invoice\StoreInvoiceRequest;
use App\Http\Requests\Invoice\UpdateInvoiceRequest;
use App\Models\Customer;
use App\Models\Invoice;
use App\Models\Project;
use App\Services\InvoiceNumberGenerator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class InvoiceController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');
        $customerId = $request->input('customer_id');

        $invoices = Invoice::query()
            ->with(['customer', 'project'])
            ->search($search)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->when($customerId, fn ($query) => $query->where('customer_id', $customerId))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('invoices.index', [
            'invoices' => $invoices,
            'customers' => Customer::query()->orderBy('name')->get(),
            'statuses' => InvoiceStatus::cases(),
            'search' => $search,
            'selectedStatus' => $status,
            'selectedCustomerId' => $customerId,
        ]);
    }

    public function create(): View
    {
        return view('invoices.create', [
            'invoice' => new Invoice([
                'status' => InvoiceStatus::Unpaid,
                'issue_date' => now()->toDateString(),
                'due_date' => now()->addDays(7)->toDateString(),
            ]),
            'customers' => Customer::query()->orderBy('name')->get(),
            'projects' => Project::query()->with('customer')->orderBy('project_name')->get(),
            'statuses' => InvoiceStatus::cases(),
        ]);
    }

    public function store(StoreInvoiceRequest $request, InvoiceNumberGenerator $invoiceNumberGenerator): RedirectResponse
    {
        $validated = $request->validated();
        $project = $this->resolveProject($validated);
        $items = $this->normalizeItems($validated['items']);
        $subtotal = array_sum(array_column($items, 'total'));
        $discount = (float) ($validated['discount'] ?? 0);

        $invoice = DB::transaction(function () use ($validated, $invoiceNumberGenerator, $project, $items, $subtotal, $discount): Invoice {
            $invoice = Invoice::create([
                'customer_id' => (int) $validated['customer_id'],
                'project_id' => $project?->id,
                'invoice_number' => $invoiceNumberGenerator->generate(),
                'issue_date' => $validated['issue_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'subtotal_amount' => $subtotal,
                'discount' => $discount,
                'final_amount' => max($subtotal - $discount, 0),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->items()->createMany($items);

            return $invoice;
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice created successfully.');
    }

    public function show(Invoice $invoice): View
    {
        $invoice->load(['customer', 'project', 'items']);

        return view('invoices.show', [
            'invoice' => $invoice,
        ]);
    }

    public function edit(Invoice $invoice): View
    {
        $invoice->load('items');

        return view('invoices.edit', [
            'invoice' => $invoice,
            'customers' => Customer::query()->orderBy('name')->get(),
            'projects' => Project::query()->with('customer')->orderBy('project_name')->get(),
            'statuses' => InvoiceStatus::cases(),
        ]);
    }

    public function update(UpdateInvoiceRequest $request, Invoice $invoice): RedirectResponse
    {
        $validated = $request->validated();
        $project = $this->resolveProject($validated);
        $items = $this->normalizeItems($validated['items']);
        $subtotal = array_sum(array_column($items, 'total'));
        $discount = (float) ($validated['discount'] ?? 0);

        DB::transaction(function () use ($invoice, $validated, $project, $items, $subtotal, $discount): void {
            $invoice->update([
                'customer_id' => (int) $validated['customer_id'],
                'project_id' => $project?->id,
                'issue_date' => $validated['issue_date'] ?? null,
                'due_date' => $validated['due_date'] ?? null,
                'subtotal_amount' => $subtotal,
                'discount' => $discount,
                'final_amount' => max($subtotal - $discount, 0),
                'status' => $validated['status'],
                'notes' => $validated['notes'] ?? null,
            ]);

            $invoice->items()->delete();
            $invoice->items()->createMany($items);
        });

        return redirect()
            ->route('invoices.show', $invoice)
            ->with('success', 'Invoice updated successfully.');
    }

    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->delete();

        return redirect()
            ->route('invoices.index')
            ->with('success', 'Invoice deleted successfully.');
    }

    public function print(Invoice $invoice): View
    {
        $invoice->load(['customer', 'project', 'items']);

        return view('invoices.print', [
            'invoice' => $invoice,
        ]);
    }

    /**
     * @param  array<string, mixed>  $validated
     *
     * @throws ValidationException
     */
    private function resolveProject(array $validated): ?Project
    {
        if (blank($validated['project_id'] ?? null)) {
            return null;
        }

        $project = Project::query()->findOrFail($validated['project_id']);

        if ($project->customer_id !== (int) $validated['customer_id']) {
            throw ValidationException::withMessages([
                'project_id' => 'Selected project does not belong to the chosen customer.',
            ]);
        }

        return $project;
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, float|int|string>>
     */
    private function normalizeItems(array $items): array
    {
        return collect($items)
            ->filter(fn (array $item) => filled($item['name'] ?? null))
            ->map(function (array $item): array {
                $quantity = (int) $item['quantity'];
                $price = (float) $item['price'];

                return [
                    'name' => (string) $item['name'],
                    'quantity' => $quantity,
                    'price' => $price,
                    'total' => $quantity * $price,
                ];
            })
            ->values()
            ->all();
    }
}
