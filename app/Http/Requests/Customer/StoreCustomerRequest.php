<?php

namespace App\Http\Requests\Customer;

use Illuminate\Foundation\Http\FormRequest;

class StoreCustomerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, string>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150', 'unique:customers,email'],
            'phone' => ['nullable', 'string', 'max:30'],
            'company_name' => ['nullable', 'string', 'max:150'],
            'address' => ['nullable', 'string', 'max:1000'],
            'live_url' => ['nullable', 'url', 'max:255'],
            'stg_url' => ['nullable', 'url', 'max:255'],
            'system_monitor_url' => ['nullable', 'url', 'max:255'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }
}
