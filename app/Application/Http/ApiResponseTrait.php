<?php

namespace App\Application\Http;

use App\Application\Exceptions\FormValidationException;
use Exception;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Http\JsonResponse;
use JsonSerializable;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;
use Throwable;

trait ApiResponseTrait
{
    private ?array $_api_helpers_defaultSuccessData = ['success' => true];

    public function respondNotFound(string|Exception $message, ?string $key = 'error'): JsonResponse
    {
        return $this->apiResponse(
            [$key => $this->morphMessage($message)],
            SymfonyResponse::HTTP_NOT_FOUND
        );
    }

    public function respondWithSuccess(array|Arrayable|JsonSerializable|null $contents = null): JsonResponse
    {
        $contents = $this->morphToArray($contents) ?? [];

        $data = [] === $contents
            ? $this->_api_helpers_defaultSuccessData
            : $contents;

        return $this->apiResponse($data);
    }

    public function setDefaultSuccessResponse(?array $content = null): self
    {
        $this->_api_helpers_defaultSuccessData = $content ?? [];

        return $this;
    }

    public function respondOk(string $message): JsonResponse
    {
        return $this->respondWithSuccess(['success' => $message]);
    }

    public function respondUnauthenticated(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Unauthenticated'],
            SymfonyResponse::HTTP_UNAUTHORIZED
        );
    }

    public function respondForbidden(?string $message = null): JsonResponse
    {
        return $this->apiResponse(
            ['error' => $message ?? 'Forbidden'],
            SymfonyResponse::HTTP_FORBIDDEN
        );
    }

    public function respondError(
        ?string $message = null,
        Throwable $throwable = null,
        int $statusCode = SymfonyResponse::HTTP_BAD_REQUEST): JsonResponse
    {
        $payload = [
            'error' => $message ?? 'Error',
        ];
        if ($throwable && app()->environment('develop', 'local')) {
            $payload['exception'] = [
                'message' => $throwable->getMessage(),
                'file' => $throwable->getFile(),
                'line' => $throwable->getLine(),
                'trace' => $throwable->getTrace(),
            ];
        }

        return $this->apiResponse($payload, $statusCode);
    }

    public function respondCreated(array|Arrayable|JsonSerializable|null $data = null): JsonResponse
    {
        $data ??= [];

        return $this->apiResponse(
            $this->morphToArray($data),
            SymfonyResponse::HTTP_CREATED
        );
    }

    public function respondFailedValidation(FormValidationException $exception): JsonResponse
    {
        $data = [
            'message' => __('The given data was invalid.'),
            'errors' => $exception->validator->errors()->messages(),
        ];

        return $this->apiResponse(
            $data,
            SymfonyResponse::HTTP_UNPROCESSABLE_ENTITY
        );
    }

    public function respondNoContent(): JsonResponse
    {
        return new JsonResponse(data: null, status: SymfonyResponse::HTTP_NO_CONTENT);
    }

    private function apiResponse(array $data, int $code = 200): JsonResponse
    {
        $responseArr = [
            'data' => $data,
        ];

        $responseArr['meta']['timestamp'] = intdiv((int) now()->format('Uu'), 1000);

        return response()->json($responseArr, $code);
    }

    private function morphToArray(array|Arrayable|JsonSerializable|null $data): ?array
    {
        if ($data instanceof Arrayable) {
            return $data->toArray();
        }

        if ($data instanceof JsonSerializable) {
            return $data->jsonSerialize();
        }

        return $data;
    }

    private function morphMessage(Exception|string $message): string
    {
        return $message instanceof Exception ? $message->getMessage() : $message;
    }
}
