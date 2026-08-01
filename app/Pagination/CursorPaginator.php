<?php

namespace App\Pagination;

use App\DTOs\Pagination\PaginatedResultDTO;
use App\DTOs\Pagination\PaginationParamsDTO;
use App\Pagination\Contracts\PaginatorInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\Cursor;

class CursorPaginator implements PaginatorInterface
{
    /**
     * @param  Builder<\Illuminate\Database\Eloquent\Model>  $query
     * @return PaginatedResultDTO<\Illuminate\Database\Eloquent\Model>
     */
    public function paginate(Builder $query, PaginationParamsDTO $params): PaginatedResultDTO
    {
        $cursor = $params->cursor
            ? Cursor::fromEncoded($params->cursor)
            : null;

        $paginator = $query->cursorPaginate(
            perPage: $params->perPage,
            cursor: $cursor
        );

        return new PaginatedResultDTO(
            items: collect($paginator->items()),
            perPage: $paginator->perPage(),
            nextCursor: $paginator->nextCursor()?->encode(),
            previousCursor: $paginator->previousCursor()?->encode(),
            hasMore: $paginator->hasMorePages()
        );
    }
}
