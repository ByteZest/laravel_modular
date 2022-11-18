<?php

declare(strict_types=1);

use Illuminate\Support\Str;
use Tuupola\Ksuid;
use Tuupola\KsuidFactory;

if (! function_exists('freshUuid')) {
    function freshUuid(): string
    {
        return Str::uuid()->toString();
    }
}

if (! function_exists('ksuid')) {
    /**
     * Generates fresh KSUID
     *
     * @param  string  $fullyQualifiedClassName  Fully Qualified Class Name
     * @return string
     * @see https://github.com/tuupola/ksuid
     */
    function ksuid(string $fullyQualifiedClassName): string
    {
        $ksUid = KsuidFactory::create();

        $prefix = config('ids.prefixes.'.$fullyQualifiedClassName);

        if (is_null($prefix)) {
            throw new InvalidArgumentException('Unable to determine the KSUID prefix for: '.$fullyQualifiedClassName);
        }

        return $prefix.$ksUid;
    }
}

if (! function_exists('validateKsuid')) {
    /**
     * Validates KSUID
     *
     * @param  string  $prefixedKsuid
     * @param  string|null  $model
     * @return bool
     */
    function validateKsuid(string $prefixedKsuid, ?string $model = null): bool
    {
        if (! str_contains($prefixedKsuid, '_')) {
            return false;
        }

        [$prefix, $ksuid] = explode('_', $prefixedKsuid);

        $prefixes = config('ids.prefixes');

        $prefixModel = array_flip($prefixes)[$prefix] ?? false;

        if (! is_null($model) && $prefixModel === $model) {
            return false;
        }

        if (! in_array($prefix.'_', $prefixes)) {
            return false;
        }

        if (! KsuidFactory::fromString($ksuid) instanceof Ksuid) {
            return false;
        }

        return true;
    }
}
