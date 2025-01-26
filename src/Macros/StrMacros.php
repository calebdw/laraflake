<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Macros;

use CalebDW\Laraflake\Facades\Snowflake;
use Illuminate\Support\Str;

class StrMacros
{
    public static function boot(): void
    {
        Str::macro('snowflake', fn (): string => Snowflake::id());

        Str::macro('isSnowflake', function (mixed $value): bool {
            if (! is_numeric($value)) {
                return false;
            }

            $value = Str::of((string) $value)
                ->rtrim('.0')
                ->rtrim('.')
                ->value();

            if (Str::contains($value, '.')) {
                return false;
            }

            assert(is_numeric($value));

            $maxSnowflakeValue = '9223372036854775807';

            if (bccomp($value, '0') < 0 || bccomp($value, $maxSnowflakeValue, 1) > 0) {
                return false;
            }

            $parsed = Snowflake::parseId($value);

            return count(array_filter($parsed)) === count($parsed);
        });
    }
}
