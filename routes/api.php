<?php

use App\Http\Controllers\ResponseController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/test/success', [ResponseController::class, 'success']);
Route::get('/test/error', [ResponseController::class, 'error']);

Route::get('/users', [UserController::class, 'index']);
Route::post('/users', [UserController::class, 'store']);
Route::get('/users/{user}', [UserController::class, 'show']);
