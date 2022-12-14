<?php

namespace App\Domain\Auth\Providers;

use App\Infrastructure\Abstracts\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    protected bool $hasMigrations = true;

    protected array $providers = [
        RouteServiceProvider::class,
        PasswordServiceProvider::class,
    ];
}
