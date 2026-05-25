<?php

namespace App\Http\Requests\Task;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateTaskRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:4000'],
            'status' => ['required', new Enum(TaskStatus::class)],
            'priority' => ['required', new Enum(TaskPriority::class)],
            'due_date' => ['nullable', 'date'],
            'assignee' => ['nullable', 'string', 'max:120'],
            'billable' => ['nullable', 'boolean'],
            'hours_logged' => ['nullable', 'numeric', 'min:0', 'max:9999.99'],
            'hourly_rate' => ['nullable', 'numeric', 'min:0', 'max:999999.99'],
            'group_name' => ['nullable', 'string', 'max:120'],
            'paid' => ['nullable', 'boolean'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'billable' => $this->boolean('billable'),
            'paid' => $this->boolean('paid'),
        ]);
    }
}
