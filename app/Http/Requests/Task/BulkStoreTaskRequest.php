<?php

namespace App\Http\Requests\Task;

use Illuminate\Foundation\Http\FormRequest;

class BulkStoreTaskRequest extends FormRequest
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
            'raw' => ['required', 'string', 'max:20000'],
            'meeting_date' => ['nullable', 'date'],
            'group_name' => ['nullable', 'string', 'max:120'],
            'billable' => ['nullable', 'boolean'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'billable' => $this->boolean('billable'),
        ]);
    }
}
