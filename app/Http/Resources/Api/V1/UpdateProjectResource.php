<?php

namespace App\Http\Resources\Api\V1;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UpdateProjectResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'message' => 'Project updated successfully',
            'data' => new ProjectResource($this->resource),
        ];
    }
}
