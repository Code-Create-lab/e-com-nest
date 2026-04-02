<?php

namespace App\Http\Requests\Lead;

use App\Enums\LeadStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateLeadRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:120'],
            'email' => ['nullable', 'email', 'max:150', 'required_without:phone'],
            'phone' => ['nullable', 'string', 'max:30', 'required_without:email'],
            'source' => ['required', 'string', 'max:120'],
            'status' => ['required', new Enum(LeadStatus::class)],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }
}
