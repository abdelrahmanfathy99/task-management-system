<?php

namespace App\Http\Resources\Api\V1;

use App\DTOs\Pagination\PaginatedResultDTO;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaginationMetaResource extends JsonResource
{
    /**
     * @param  PaginatedResultDTO<mixed>  $resource
     */
    public function __construct(PaginatedResultDTO $resource)
    {
        parent::__construct($resource);
    }

    public function toArray(Request $request): array
    {
        /** @var PaginatedResultDTO<mixed> $result */
        $result = $this->resource;

        return [
            'per_page' => $result->perPage,
            'next_cursor' => $result->nextCursor,
            'prev_cursor' => $result->previousCursor,
            'has_more' => $result->hasMore,
        ];
    }
}
