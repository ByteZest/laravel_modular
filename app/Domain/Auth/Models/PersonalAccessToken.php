<?php

namespace App\Domain\Auth\Models;

use App\Infrastructure\Traits\HasKsuids;
use Laravel\Sanctum\PersonalAccessToken as PersonalAccessTokenSanctum;

class PersonalAccessToken extends PersonalAccessTokenSanctum
{
    use HasKsuids;
}
