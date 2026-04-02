<?php

namespace App\Http\Controllers;

use App\Http\Requests\Customer\StoreCustomerRequest;
use App\Http\Requests\Customer\UpdateCustomerRequest;
use App\Models\Customer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = $request->input('search');

        $customers = Customer::query()
            ->withCount(['projects', 'invoices'])
            ->search($search)
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('customers.index', [
            'customers' => $customers,
            'search' => $search,
        ]);
    }

    public function create(): View
    {
        return view('customers.create', [
            'customer' => new Customer(),
        ]);
    }

    public function store(StoreCustomerRequest $request): RedirectResponse
    {
        Customer::create($request->validated());

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer created successfully.');
    }

    public function show(Customer $customer): View
    {
        $customer->loadCount(['projects', 'invoices']);
        $customer->load([
            'projects' => fn ($query) => $query->latest()->take(5),
            'invoices' => fn ($query) => $query->latest()->take(5),
        ]);

        return view('customers.show', [
            'customer' => $customer,
        ]);
    }

    public function edit(Customer $customer): View
    {
        return view('customers.edit', [
            'customer' => $customer,
        ]);
    }

    public function update(UpdateCustomerRequest $request, Customer $customer): RedirectResponse
    {
        $customer->update($request->validated());

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        if ($customer->projects()->exists() || $customer->invoices()->exists()) {
            return back()->with('error', 'Delete the related projects and invoices before removing this customer.');
        }

        $customer->delete();

        return redirect()
            ->route('customers.index')
            ->with('success', 'Customer deleted successfully.');
    }
}
