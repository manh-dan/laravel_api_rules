<?php

namespace App\Http\Resources;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BaseResource extends JsonResource
{
    /**
     * Default response message used when no custom message is provided.
     */
    protected string $message = 'success';

    /**
     * Default HTTP status code for successful resource responses.
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
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
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
        // Build the default response envelope shared by all resources.
        $with = [
            'success' => true,
            'message' => $this->message,
            'meta'    => [
                'timestamp' => now()->timestamp,
            ],
        ];

        // Merge additional data last so callers can override default keys.
        if (! empty($this->additional)) {
            $with = array_replace_recursive($with, $this->additional);
            $this->additional = [];
        }

        return $with;
    }

    /**
     * Customize the response to change the HTTP status code.
     */
    public function withResponse(Request $request, JsonResponse $response): void
    {
        $response->setStatusCode($this->statusCode);
    }
}
