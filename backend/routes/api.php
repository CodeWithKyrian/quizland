<?php

declare(strict_types=1);

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\TestController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn() => response()->json("You have reached the API"));

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('api')->group(function () {
    Route::get('user', fn() => new UserResource(auth('api')->user()));

    Route::get('tests', [TestController::class, 'index']);
    Route::post('tests/{test}/start', [TestController::class, 'start']);
    Route::post('tests/{test}/submit', [TestController::class, 'submit']);
    Route::get('tests/{test}/results', [TestController::class, 'results']);

    Route::apiResource('questions', QuestionController::class);
});
