<?php

namespace App\Http\Controllers;

use App\Enums\LeadStatus;
use App\Http\Requests\Lead\StoreLeadRequest;
use App\Http\Requests\Lead\UpdateLeadRequest;
use App\Models\Lead;
use App\Services\LeadConversionService;
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

    public function convert(Lead $lead, LeadConversionService $leadConversionService): RedirectResponse
    {
        $customer = $leadConversionService->convert($lead);

        return redirect()
            ->route('customers.show', $customer)
            ->with('success', 'Lead converted to customer successfully.');
    }
}
