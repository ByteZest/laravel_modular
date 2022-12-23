<?php

namespace App\Domain\Users\Actions;

use App\Domain\Users\Models\User;
use App\Domain\Users\ValueObjects\UserData;

class CreateOrUpdateUserAction
{
    public function execute(UserData $data): User
    {
        return User::updateOrCreate(
            ['id' => $data->id],
            $data->toArray()
        );
    }
}
