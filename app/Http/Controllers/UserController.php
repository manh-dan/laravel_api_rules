<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Models\User;

class UserController extends Controller
{
    public function index()
    {
        return (new UserCollection(User::all()))
            ->withMessage('Users retrieved successfully.')
            ->additional([
                'meta' => [
                    'request_id' => request()->header('X-Request-ID', 'N/A'),
                ],
            ]);

        return (new UserCollection(User::paginate(2)))
            ->withMessage('Users retrieved successfully.')
            ->additional([
                'meta' => [
                    'request_id' => request()->header('X-Request-ID', 'N/A'),
                ],
            ]);

        return (new UserCollection(User::simplePaginate(2)))
            ->withMessage('Users retrieved successfully.')
            ->additional([
                'meta' => [
                    'request_id' => request()->header('X-Request-ID', 'N/A'),
                ],
            ]);

        return (new UserCollection(User::cursorPaginate(2)))
            ->withMessage('Users retrieved successfully.')
            ->additional([
                'meta' => [
                    'request_id' => request()->header('X-Request-ID', 'N/A'),
                ],
            ]);
    }

    public function show(User $user)
    {
        return (new UserResource($user))
            ->withMessage('User retrieved successfully.')
            ->additional([
                'meta' => [
                    'request_id' => request()->header('X-Request-ID', 'N/A'),
                ],
            ]);
    }

    public function store(StoreUserRequest $request)
    {

    }
}
