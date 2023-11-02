<?php

namespace App\Http\Resources;

use App\Enums\ErrorType;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;

//use Symfony\Component\HttpFoundation\Response;


class ErrorResponse implements Responsable
{

    /**
     * Create a new error response instance.
     */
    public function __construct(public ?string $message, public ErrorType $type = ErrorType::Unknown, public int $code = 400)
    {
    }

    public static function fromResponse(Response $response): self
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

    public function toResponse($request): JsonResponse
    {
        return response()->json([
            'type' => $this->type->value,
            'message' => $this->message ?? "Internal Server Error",
        ], $this->code);
    }
}


