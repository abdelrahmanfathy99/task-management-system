<?php

namespace App\Http\Resources\Api\V1;

use App\DTOs\Pagination\PaginatedResultDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class TaskCollectionResource extends JsonResource
{
    /**
     * @param  PaginatedResultDTO<\App\DTOs\Api\V1\TaskResultDTO>  $resource
     */
    public function __construct(PaginatedResultDTO $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var PaginatedResultDTO<\App\DTOs\Api\V1\TaskResultDTO> $result */
        $result = $this->resource;

        return [
            'message' => 'Tasks retrieved successfully',
            'data' => TaskResource::collection($result->items),
            'meta' => new PaginationMetaResource($result),
        ];
    }
}
