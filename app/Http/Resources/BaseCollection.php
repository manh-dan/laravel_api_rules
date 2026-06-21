<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Pagination\AbstractCursorPaginator;
use Illuminate\Pagination\AbstractPaginator;
use Illuminate\Pagination\LengthAwarePaginator;

class BaseCollection extends ResourceCollection
{
    /**
     * Default response message used when no custom message is provided.
     */
    protected string $message = 'success';

    /**
     * Default HTTP status code for successful collection responses.
     */
    protected int $statusCode = JsonResponse::HTTP_OK;

    /**
     * Set the message for the response.
     */
    public function withMessage(string $message): static
    {
        $this->message = $message;
        return $this;
    }

    /**
     * Set the HTTP status code.
     */
    public function withStatus(int $statusCode): static
    {
        $this->statusCode = $statusCode;
        return $this;
    }

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }

    /**
     * Add additional data to the top-level of the JSON array.
     *
     * @return array<string, mixed>
     */
    public function with(Request $request): array
    {
        // Build the default response envelope shared by all collections.
        $with = [
            'success' => true,
            'message' => $this->message,
            'meta'    => [
                'timestamp' => now()->timestamp,
            ],
        ];

        $pagination = $this->paginationMeta();

        // Add pagination only when the collection was created from a paginator.
        if (! empty($pagination)) {
            $with['meta']['pagination'] = $pagination;
        }

        // Merge additional data last so callers can override default keys.
        if (! empty($this->additional)) {
            $with = array_replace_recursive($with, $this->additional);
            $this->additional = [];
        }

        return $with;
    }

    /**
     * Disable Laravel's default pagination metadata.
     *
     * Pagination data is added by paginationMeta() to keep a consistent shape.
     *
     * @return array<string, mixed>
     */
    public function paginationInformation(Request $request, array $paginated, array $default): array
    {
        return [];
    }

    /**
     * Customize the response to change the HTTP status code.
     */
    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode($this->statusCode);
    }

    /**
     * Get pagination metadata for paginated collections.
     *
     * @return array<string, mixed>
     */
    protected function paginationMeta(): array
    {
        // cursorPaginate() uses cursor tokens instead of page numbers.
        if ($this->resource instanceof AbstractCursorPaginator) {
            return [
                'type'           => 'cursor',
                'per_page'       => $this->resource->perPage(),
                'next_cursor'    => $this->resource->nextCursor()?->encode(),
                'prev_cursor'    => $this->resource->previousCursor()?->encode(),
                'has_more_pages' => $this->resource->hasMorePages(),
            ];
        }

        // Non-paginated collections do not need pagination metadata.
        if (! $this->resource instanceof AbstractPaginator) {
            return [];
        }

        // paginate() includes total and last_page; simplePaginate() does not.
        return [
            'type'           => 'page',
            'current_page'   => $this->resource->currentPage(),
            'per_page'       => $this->resource->perPage(),
            'total'          => $this->resource instanceof LengthAwarePaginator ? $this->resource->total() : null,
            'last_page'      => $this->resource instanceof LengthAwarePaginator ? $this->resource->lastPage() : null,
            'has_more_pages' => $this->resource->hasMorePages(),
        ];
    }
}
