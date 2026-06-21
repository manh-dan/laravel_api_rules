<?php

namespace App\Support\Http;

use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;

/**
 * Abstract base class for API responses.
 *
 * Provides a common structure for building consistent JSON responses,
 * including an HTTP status code and optional metadata.
 */
abstract class ApiResponse implements Responsable
{
    /**
     * The HTTP status code for the response.
     * Defaults to 200 (OK).
     */
    protected int $status = JsonResponse::HTTP_OK;

    /**
     * Additional metadata to attach to the response.
     */
    protected array $meta = [];

    /**
     * Set the HTTP status code for the response.
     *
     * @param  int  $status  HTTP status code (e.g. 200, 201, 404, 422)
     * @return static
     */
    public function withStatus(int $status): static
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Merge additional metadata into the response.
     *
     * New entries are merged with any previously set metadata.
     *
     * @param  array  $meta  Metadata to add
     * @return static
     */
    public function withMeta(array $meta): static
    {
        $this->meta = array_merge($this->meta, $meta);

        return $this;
    }

    /**
     * Convert the response to an array.
     *
     * Subclasses must implement this method to define
     * the structure of the returned data.
     *
     * @return array
     */
    abstract public function toArray(): array;

    /**
     * Convert the response to a JsonResponse instance.
     *
     * Called automatically by Laravel when returning this object
     * from a controller action.
     *
     * @param  mixed  $request  The current HTTP request
     * @return JsonResponse
     */
    public function toResponse($request): JsonResponse
    {
        return response()->json(
            $this->toArray(),
            $this->status
        );
    }

    /**
     * Build the default metadata array for the response.
     *
     * Automatically includes the current timestamp and merges it
     * with any custom metadata set via withMeta().
     *
     * @return array
     */
    protected function buildMeta(): array
    {
        return array_merge([
            'timestamp' => now()->timestamp,
        ], $this->meta);
    }
}