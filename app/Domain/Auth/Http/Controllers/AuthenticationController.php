<?php

namespace App\Domain\Auth\Http\Controllers;

use App\Application\Exceptions\FormValidationException;
use App\Application\Http\Controller;
use App\Domain\Auth\Http\Requests\RegisterUserRequest;
use App\Domain\Users\Actions\CreateOrUpdateUserAction;
use App\Domain\Users\Http\Resources\UserResource;
use App\Domain\Users\ValueObjects\UserData;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Throwable;

class AuthenticationController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'remember_me' => ['boolean'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember_me'))) {
            throw FormValidationException::withMessages([
                'email' => ['Provided credentials are incorrect.'],
            ]);
        }

        $request->session()->regenerate();

        return $this->respondNoContent();
    }

    public function register(RegisterUserRequest $registerUserRequest): JsonResponse
    {
        try {
            $data = UserData::fromRegisterUserRequest($registerUserRequest);
            $user = app(CreateOrUpdateUserAction::class)->execute($data);

            Auth::login($user);
            $registerUserRequest->session()->regenerate();

            return $this->respondCreated([
                'id' => $user->id,
            ]);
        } catch (Throwable $throwable) {
            report($throwable);

            return $this->respondError(
                throwable: $throwable,
                statusCode: Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }

    public function logout(Request $request): JsonResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return $this->respondNoContent();
    }

    public function me()
    {
        return $this->respondWithSuccess(UserResource::make(Auth::user()));
    }
}
