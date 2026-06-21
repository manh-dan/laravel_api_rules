<?php

namespace App\Support\Http;

use Illuminate\Http\JsonResponse;

/**
 * Represents a failed API response.
 *
 * Returns a JSON payload with a failure flag, a human-readable message,
 * a structured error object, and auto-generated metadata.
 */
class ErrorResponse extends ApiResponse
{
    /**
     * HTTP status code for error responses. Defaults to 400 (Bad Request).
     */
    protected int $status = JsonResponse::HTTP_BAD_REQUEST;

    /**
     * Human-readable message describing what went wrong.
     */
    protected string $message;

    /**
     * Application-level error code (e.g. 'VALIDATION_ERROR', 'NOT_FOUND').
     * Null when no specific code is applicable.
     */
    protected ?string $error_code = null;

    /**
     * Additional details about the error (e.g. validation messages, stack info).
     * Can be any type — array, string, or null.
     */
    protected mixed $error_details = null;

    /**
     * Create a new ErrorResponse instance.
     *
     * @param  string       $message      Human-readable error message.
     * @param  string|null  $error_code   Optional application-level error code.
     * @param  mixed        $error_details Optional extra details about the error.
     * @return static
     */
    public static function make(
        string $message,
        ?string $error_code = null,
        mixed $error_details = null
    ): static {

        $instance                = new static();
        $instance->message       = $message;
        $instance->error_code    = $error_code;
        $instance->error_details = $error_details;

        return $instance;
    }

    /**
     * Serialize the response to an array.
     *
     * The resulting structure is:
     * ```json
     * {
     *   "success": false,
     *   "message": "...",
     *   "error": {
     *     "code": "...",
     *     "details": ...
     *   },
     *   "meta": { "timestamp": ... }
     * }
     * ```
     *
     * @return array
     */
    public function toArray(): array
    {
        return [
            'success' => false,
            'message' => $this->message,
            'error'   => [
                'code'    => $this->error_code,
                'details' => $this->error_details,
            ],
            'meta' => $this->buildMeta(),
        ];
    }
}