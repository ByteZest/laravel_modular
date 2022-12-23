<?php

namespace App\Domain\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;

class PasswordServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Password::defaults(function () {
            $rule = Password::min(3);

            return $this->app->isProduction()
                ? $rule->mixedCase()->numbers()->symbols()->uncompromised()
                : $rule;
        });
    }
}
