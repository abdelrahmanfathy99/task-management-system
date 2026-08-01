<?php

namespace App\Pagination\Contracts;

use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use Illuminate\Database\Eloquent\Builder;

interface PaginatorInterface
{
    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return PaginatedResultDTO<\Illuminate\Database\Eloquent\Model>
     */
    public function paginate(Builder $query, PaginationParamsDTO $params): PaginatedResultDTO;
}
