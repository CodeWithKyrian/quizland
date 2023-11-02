<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\LoginRequest;
use App\Http\Requests\API\RefreshRequest;
use App\Http\Requests\API\RegisterRequest;
use App\Http\Resources\ErrorResponse;
use App\Http\Resources\UserResource;
use App\Models\User;
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
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        DB::commit();

        if (!$response->successful())
            return ErrorResponse::fromResponse($response);


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
            'client_id' => config('passport.personal_access_client.id'),
            'client_secret' => config('passport.personal_access_client.secret'),
            'username' => $request->validated('email'),
            'password' => $request->validated('password'),
        ]);

        if (!$response->successful())
            return ErrorResponse::fromResponse($response);

        $user = User::where('email', $request->validated('email'))->first();

        return response()->json([
            'user' => new UserResource($user),
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
        ]);
    }

    public function refresh(RefreshRequest $request)
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
            'user' => new UserResource($user),
            'access_token' => $response->json('access_token'),
            'refresh_token' => $response->json('refresh_token'),
        ]);
    }

    public function logout()
    {
        $user = auth('api')->user();
        $user?->tokens()->where('client_id', config('passport.personal_access_client.id'))->delete();
        return response()->json(['message' => 'Logged out successfully']);
    }
}
