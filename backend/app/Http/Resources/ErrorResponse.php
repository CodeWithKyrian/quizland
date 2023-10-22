<?php

namespace App\Http\Resources;

use App\Enums\ErrorType;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;


class ErrorResponse implements Responsable
{

    /**
     * Create a new error response instance.
     */
    public function __construct(public ?string $message, public ErrorType $type = ErrorType::Unknown, public int $code = 400)
    {
    }


    public function toResponse($request): JsonResponse|Response
    {
        return response()->json([
            'type' => $this->type->value,
            'message' => $this->message ?? "Internal Server Error",
        ], $this->code);
    }
}


