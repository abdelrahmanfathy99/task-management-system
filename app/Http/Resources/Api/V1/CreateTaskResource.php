<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CreateTaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Task created successfully',
            'data' => new TaskResource($this->resource),
        ];
    }
}
