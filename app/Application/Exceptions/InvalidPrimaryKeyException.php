<?php

declare(strict_types=1);

namespace App\Application\Exceptions;

use RuntimeException;

final class InvalidPrimaryKeyException extends RuntimeException
{
    public static function throw(string $id, string $forModel)
    {
        $forModel = class_basename($forModel);

        $message = "$id is invalid primary key for $forModel";

        throw new self(message: $message);
    }
}
