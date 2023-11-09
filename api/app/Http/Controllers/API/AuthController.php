<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RefreshRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Resources\ErrorResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\Client;

class AuthController extends Controller
{
    public function register(RegisterRequest $request): ErrorResponse|JsonResponse
    {
        DB::beginTransaction();

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $tokenRequest = Request::create(route('passport.token'), 'POST', [
            'grant_type' => 'password',
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        $response = app()->handle($tokenRequest);

        DB::commit();

        if (!$response->isOk())
            return ErrorResponse::fromResponse($response);

        $responseJson = json_decode($response->getContent(), true);


        return response()->json([
            'access_token' => $responseJson['access_token'],
            'refresh_token' => $responseJson['refresh_token'],
        ]);
    }

    public function login(LoginRequest $request): ErrorResponse|JsonResponse
    {
        $tokenRequest = Request::create(route('passport.token'), 'POST', [
            'grant_type' => 'password',
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        $response = app()->handle($tokenRequest);

        DB::commit();

        if (!$response->isOk())
            return ErrorResponse::fromResponse($response);

        $responseJson = json_decode($response->getContent(), true);


        return response()->json([
            'access_token' => $responseJson['access_token'],
            'refresh_token' => $responseJson['refresh_token'],
        ]);
    }

    public function refresh(RefreshRequest $request): ErrorResponse|JsonResponse
    {
        $response = Http::post(route('passport.token'), [
            'grant_type' => 'refresh_token',
            'refresh_token' => $request->validated('refresh_token'),
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
        ]);

        if (!$response->successful())
            return ErrorResponse::fromResponse($response);

        $user = auth('api')->user();

        return response()->json([
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
        ]);
    }

    public function logout(): JsonResponse
    {
        $user = auth('api')->user();
        $user?->tokens()->where('client_id', config('passport.personal_access_client.id'))->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
