<?php

namespace App\Infrastructure\Rules;

use Illuminate\Contracts\Validation\InvokableRule;

class AlphaNumDashSpaceRule implements InvokableRule
{
    private const REGEX = '/^[\pL\pM\pN -_]*$/u';

    public function __invoke($attribute, $value, $fail)
    {
        if (! preg_match(self::REGEX, $value)) {
            $fail('validation.alpha_num_dash_space')->translate();
        }
    }
}
