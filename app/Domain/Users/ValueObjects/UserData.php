<?php

namespace App\Domain\Users\ValueObjects;

use App\Domain\Auth\Http\Requests\RegisterUserRequest;
use App\Domain\Users\Models\User;
use Hash;
use Spatie\LaravelData\Attributes\MapName;
use Spatie\LaravelData\Data;
use Spatie\LaravelData\Mappers\SnakeCaseMapper;
use Spatie\LaravelData\Optional;

#[MapName(SnakeCaseMapper::class)]
final class UserData extends Data
{
    public function __construct(
        public readonly string|Optional $id,
        public readonly string|Optional $firstName,
        public readonly string|Optional $lastName,
        public readonly string|Optional $email,
        public readonly string|Optional $password,
    ) {
    }

    public static function fromRegisterUserRequest(RegisterUserRequest $request): static
    {
        return new self(
            id: ksuid(User::class),
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            email: $request->input('email'),
            password: Hash::make($request->input('password')),
        );
    }
}
