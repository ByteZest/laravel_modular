<?php

declare(strict_types=1);

namespace App\Domain\Users\Providers;

use App\Infrastructure\Abstracts\ServiceProvider;

class DomainServiceProvider extends ServiceProvider
{
    protected bool $hasMigrations = true;
}
