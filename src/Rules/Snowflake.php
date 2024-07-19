<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Str;

class Snowflake implements ValidationRule
{
    /** @inheritDoc */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! Str::isSnowflake($value)) {
            $fail('The :attribute must be a valid Snowflake.');
        }
    }
}
