<?php

namespace App\Http\Controllers;

use App\Support\Http\ErrorResponse;
use App\Support\Http\SuccessResponse;

class ResponseController extends Controller
{
    public function success()
    {
        return SuccessResponse::make(
            data   : [
                'id' => 1,
                'name' => 'John Doe',
                'email' => 'john.doe@example.com'
            ],
            message: 'Data retrieved successfully.'
        )
        ->withStatus(201)
        ->withMeta(['request_id' => 'abc123'])
        ;
    }

    public function error()
    {
        return ErrorResponse::make(
            message      : 'The requested resource was not found.',
            error_code   : 'NOT_FOUND',
            error_details: [
                'customer_id' => [
                    'The customer id field is required.'
                ],
            ]
        )
        ->withStatus(404)
        ->withMeta(['request_id' => 'abc123'])
        ;
    }
}
