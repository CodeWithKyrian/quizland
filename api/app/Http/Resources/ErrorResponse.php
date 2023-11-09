<?php

namespace App\Http\Resources;

use App\Enums\ErrorType;
use Illuminate\Contracts\Support\Responsable;
use Symfony\Component\HttpFoundation\Response;
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
        $responseJson = json_decode($response->getContent(), true);

        $errorType = match ($responseJson['error']) {
            'unsupported_grant_type', 'invalid_grant', 'invalid_request' => ErrorType::InvalidRequestInput,
            'invalid_client' => ErrorType::InvalidClient,
            'access_denied' => ErrorType::NotAuthenticated,
            default => ErrorType::Unknown,
        };

        return new ErrorResponse(
            $responseJson['message'],
            $errorType,
            $response->getStatusCode()
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


