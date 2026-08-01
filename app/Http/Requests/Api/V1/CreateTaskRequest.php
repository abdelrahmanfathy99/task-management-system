<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateTaskRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'min:1', 'max:255'],
            'description' => ['nullable', 'string'],
            'priority' => ['sometimes', 'string', Rule::enum(TaskPriority::class)],
            'status' => ['sometimes', 'string', Rule::enum(TaskStatus::class)],
            'due_date' => ['nullable', 'date'],
        ];
    }
}
