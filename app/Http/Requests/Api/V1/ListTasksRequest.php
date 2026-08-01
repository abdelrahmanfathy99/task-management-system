<?php

namespace App\Http\Requests\Api\V1;

use App\Enums\TaskPriority;
use App\Enums\TaskStatus;
use App\Http\Requests\Concerns\HasPaginationRules;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ListTasksRequest extends FormRequest
{
    use HasPaginationRules;

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'search' => ['sometimes', 'nullable', 'string', 'max:255'],
            'status' => ['sometimes', 'nullable', 'string', Rule::enum(TaskStatus::class)],
            'priority' => ['sometimes', 'nullable', 'string', Rule::enum(TaskPriority::class)],
            ...$this->paginationRules(),
        ];
    }
}
