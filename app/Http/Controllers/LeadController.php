<?php

namespace App\Http\Controllers;

use App\Enums\LeadStatus;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\LeadConversionService;
use App\Services\LeadImportService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LeadController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');
        $status = $request->input('status');

        $leads = Lead::query()
            ->with('customer')
            ->search($search)
            ->when($status, fn ($query) => $query->where('status', $status))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('leads.index', [
            'leads' => $leads,
            'search' => $search,
            'selectedStatus' => $status,
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function create(): View
    {
        return view('leads.create', [
            'lead' => new Lead(),
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function store(StoreLeadRequest $request): RedirectResponse
    {
        Lead::create($request->validated());

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead created successfully.');
    }

    public function show(Lead $lead): View
    {
        $lead->load('customer');

        return view('leads.show', [
            'lead' => $lead,
        ]);
    }

    public function edit(Lead $lead): View
    {
        return view('leads.edit', [
            'lead' => $lead,
            'statuses' => LeadStatus::cases(),
        ]);
    }

    public function update(UpdateLeadRequest $request, Lead $lead): RedirectResponse
    {
        $lead->update($request->validated());

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead updated successfully.');
    }

    public function destroy(Lead $lead): RedirectResponse
    {
        $lead->delete();

        return redirect()
            ->route('leads.index')
            ->with('success', 'Lead deleted successfully.');
    }

    public function import(Request $request, LeadImportService $leadImportService): RedirectResponse
    {
        $request->validate([
            'file' => ['required', 'file', 'mimes:xlsx,xls,csv', 'max:10240'],
        ]);

        $result = $leadImportService->import($request->file('file')->getRealPath());

        $message = sprintf(
            'Import complete: %d new, %d updated, %d skipped.',
            $result['imported'],
            $result['updated'],
            $result['skipped'],
        );

        return redirect()
            ->route('leads.index')
            ->with('success', $message);
    }

    public function updateStatus(Request $request, Lead $lead): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'string', 'in:'.implode(',', array_diff(LeadStatus::values(), [LeadStatus::Converted->value]))],
        ]);

        if ($lead->status === LeadStatus::Converted) {
            return back()->with('error', 'Converted leads cannot change status.');
        }

        $lead->update(['status' => $validated['status']]);

        return back()->with('success', 'Lead status updated.');
    }

    public function convert(Lead $lead, LeadConversionService $leadConversionService): RedirectResponse
    {
        $customer = $leadConversionService->convert($lead);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Lead converted to customer successfully.');
    }
}
