<?php

namespace App\Http\Requests\Concerns;

trait HasPaginationRules
{
    /**
     * @return array<string, list<string>>
     */
    protected function paginationRules(): array
    {
        return [
            'cursor' => ['sometimes', 'nullable', 'string'],
            'per_page' => ['sometimes', 'integer', 'min:1', 'max:100'],
        ];
    }
}
