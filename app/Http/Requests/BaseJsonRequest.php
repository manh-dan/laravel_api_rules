<?php

namespace App\Http\Requests;

use App\Support\Http\ErrorResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

class BaseJsonRequest extends FormRequest
{
    /**
     * Handle a failed validation attempt.
     *
     * Throws an HttpResponseException with a structured ErrorResponse body
     * instead of Laravel's default redirect/JSON behavior.
     */
    #[\Override]
    public function failedValidation(Validator $validator): never
    {
        $errors = (new ValidationException($validator))->errors();

        throw new HttpResponseException(
            ErrorResponse::make(
                message      : 'The given data was invalid.',
                error_code   : 'VALIDATION_ERROR',
                error_details: $errors,
            )
            ->withStatus(JsonResponse::HTTP_UNPROCESSABLE_ENTITY)
            ->toResponse($this)
        );
    }
}
