<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use App\Application\Http\ApiResponseTrait;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;

class FormValidationException extends ValidationException
{
    public $validator;

    use ApiResponseTrait;

    public function __construct(Validator $validator, ?Response $response = null, string $errorBag = 'default')
    {
        parent::__construct($validator);

        $this->response = $response;
        $this->errorBag = $errorBag;
        $this->validator = $validator;
    }

    public function render(): JsonResponse
    {
        return $this->respondFailedValidation($this);
    }
}
