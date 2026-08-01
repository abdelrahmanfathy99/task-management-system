<?php

namespace App\DTOs\Pagination;

use Illuminate\Support\Collection;

/**
 * @template TItem
 */
final readonly class PaginatedResultDTO
{
    /**
     * @param  Collection<int, TItem>  $items
     */
    public function __construct(
        public Collection $items,
        public int $perPage,
        public ?string $nextCursor,
        public ?string $previousCursor,
        public bool $hasMore
    ) {}

    /**
     * @template TOut
     *
     * @param  callable(TItem): TOut  $callback
     * @return PaginatedResultDTO<TOut>
     */
    public function map(callable $callback): self
    {
        return new self(
            items: $this->items->map($callback)->values(),
            perPage: $this->perPage,
            nextCursor: $this->nextCursor,
            previousCursor: $this->previousCursor,
            hasMore: $this->hasMore
        );
    }
}
