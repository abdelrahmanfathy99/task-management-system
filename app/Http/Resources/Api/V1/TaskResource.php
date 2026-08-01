<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->resource->id,
            'project_id' => $this->resource->projectId,
            'title' => $this->resource->title,
            'description' => $this->resource->description,
            'priority' => $this->resource->priority,
            'status' => $this->resource->status,
            'due_date' => $this->resource->dueDate,
            'created_at' => $this->resource->createdAt,
            'updated_at' => $this->resource->updatedAt,
        ];
    }
}
