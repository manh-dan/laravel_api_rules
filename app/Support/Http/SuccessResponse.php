<?php

namespace App\Support\Http;

/**
 * Represents a successful API response.
 *
 * Returns a JSON payload with a success flag, a message,
 * the response data, and auto-generated metadata.
 */
class SuccessResponse extends ApiResponse
{
    /**
     * Human-readable message describing the result.
     * Defaults to 'success'.
     */
    protected string $message = 'success';

    /**
     * The response payload. Can be any type (array, object, scalar, null).
     */
    protected mixed $data = null;

    /**
     * Create a new SuccessResponse instance.
     *
     * @param  string  $message  A short message describing the outcome.
     * @param  mixed  $data  The data to include in the response body.
     * @return static
     */
    public static function make(
        string $message = 'success',
        mixed $data = null
    ): static {

        $instance          = new static();
        $instance->message = $message;
        $instance->data    = $data;

        return $instance;
    }

    /**
     * Serialize the response to an array.
     *
     * The resulting structure is:
     * ```json
     * {
     *   "success": true,
     *   "message": "...",
     *   "data": ...,
     *   "meta": { "timestamp": ... }
     * }
     * ```
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => true,
            'message' => $this->message,
            'data'    => $this->data,
            'meta'    => $this->buildMeta(),
        ];
    }
}