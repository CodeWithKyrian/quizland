<?php

namespace App\Http\Controllers\API;

use App\Enums\ErrorType;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\LoginRequest;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\ErrorResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Client\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;

class AuthController extends Controller
{
    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();

        $user = User::create([
            'name' => $request->validated('name'),
            'email' => $request->validated('email'),
            'password' => Hash::make($request->validated('password')),
        ]);

        $response = Http::post(route('passport.token'), [
            'grant_type' => 'password',
            'client_id' => 1,
            'client_secret' => 'FBj2AOK0cwFSn99WOHa46qVsfXX8BzduNWC1jIYt',
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        DB::commit();

        if (!$response->successful())
            return $this->createErrorResponse($response);


        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
        ]);
    }

    public function login(LoginRequest $request)
    {
        $response = Http::post(route('passport.token'), [
            'grant_type' => 'password',
            'client_id' => 1,
            'client_secret' => 'FBj2AOK0cwFSn99WOHa46qVsfXX8BzduNWC1jIYt',
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        if (!$response->successful())
            return $this->createErrorResponse($response);

        $user = User::where('email', $request->validated('email'))->first();

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
        ]);
    }

    public function logout(Request $request)
    {
        $user = auth('api')->user();
        $user?->tokens()->where('client_id', 1)->delete();
        return response()->json(['message' => 'Tokens cleared successfully']);
    }

    private function createErrorResponse(Response $response): ErrorResponse
    {
        $errorType = match ($response->json('error')) {
            'unsupported_grant_type', 'invalid_grant', 'invalid_request' => ErrorType::InvalidRequestInput,
            'invalid_client' => ErrorType::InvalidClient,
            'access_denied' => ErrorType::NotAuthenticated,
            default => ErrorType::Unknown,
        };

        return new ErrorResponse(
            $response->json('message'),
            $errorType,
            $response->status()
        );
    }
}
