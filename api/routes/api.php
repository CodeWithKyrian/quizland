<?php

declare(strict_types=1);

use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\ProgramController;
use App\Http\Controllers\API\QuestionController;
use App\Http\Controllers\API\QuizController;
use App\Http\Controllers\API\UserController;
use Illuminate\Support\Facades\Route;

Route::get('', fn() => response()->json("You have reached the API of QuizLand!", 201));

Route::post('login', [AuthController::class, 'login']);
Route::post('register', [AuthController::class, 'register']);
Route::post('refresh', [AuthController::class, 'refresh']);
Route::post('logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->group(function () {

    Route::get('user', [UserController::class, 'show']);
    Route::put('user', [UserController::class, 'update']);
    Route::put('user/password', [UserController::class, 'updatePassword']);
    Route::get('user/notifications', [UserController::class, 'notifications']);
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{user}', [UserController::class, 'show']);
    Route::put('users/{user}', [UserController::class, 'update']);
    Route::delete('users/{user}', [UserController::class, 'destroy']);
    Route::get('users/{user}/notifications', [UserController::class, 'notifications']);

    Route::get('programs', [ProgramController::class, 'index']);
    Route::post('programs', [ProgramController::class, 'store']);
    Route::get('programs/{program}', [ProgramController::class, 'show']);
    Route::put('programs/{program}', [ProgramController::class, 'update']);
    Route::delete('programs/{program}', [ProgramController::class, 'destroy']);
    Route::post('programs/{program}/invite', [ProgramController::class, 'invite']);
    Route::post('programs/{program}/enroll', [ProgramController::class, 'enroll']);
    Route::post('programs/{program}/publish', [ProgramController::class, 'publish']);
    Route::post('programs/{program}/export', [ProgramController::class, 'export']);

    Route::get('quizzes', [QuizController::class, 'index']);
    Route::post('quizzes', [QuizController::class, 'store']);
    Route::get('quizzes/{quiz}', [QuizController::class, 'show']);
    Route::put('quizzes/{quiz}', [QuizController::class, 'update']);
    Route::delete('quizzes/{quiz}', [QuizController::class, 'destroy']);
    Route::post('quizzes/{quiz}/start', [QuizController::class, 'start']);
    Route::post('quizzes/{quiz}/submit', [QuizController::class, 'submit']);
    Route::get('quizzes/{quiz}/results', [QuizController::class, 'results']);


    Route::apiResource('questions', QuestionController::class);
});
