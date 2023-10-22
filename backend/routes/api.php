<?php

declare(strict_types=1);

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuizController;
use App\Http\Resources\UserResource;
use Illuminate\Support\Facades\Route;

Route::get('ping', fn() => response()->json("You have reached the API"));

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('api')->group(function () {
    Route::get('user', fn() => new UserResource(auth('api')->user()));

    Route::get('tests', [QuizController::class, 'index']);
    Route::post('tests/{test}/start', [QuizController::class, 'start']);
    Route::post('tests/{test}/submit', [QuizController::class, 'submit']);
    Route::get('tests/{test}/results', [QuizController::class, 'results']);

    Route::apiResource('questions', QuestionController::class);
});
