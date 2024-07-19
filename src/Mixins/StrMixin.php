<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Mixins;

use CalebDW\Laraflake\Facades\Snowflake;
use Closure;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Support\Str */
class StrMixin
{
    /** @return Closure(): string */
    public function snowflake(): Closure
    {
        return fn (): string => Snowflake::id();
    }

    /** @return Closure(mixed): bool */
    public function isSnowflake(): Closure
    {
        return function (mixed $value): bool {
            if (! is_numeric($value)) {
                return false;
            }

            $value = Str::of((string) $value)
                ->rtrim('.0')
                ->rtrim('.')
                ->toString();

            if (Str::contains($value, '.')) {
                return false;
            }

            $maxSnowflakeValue = '9223372036854775807';

            if (bccomp($value, '0') < 0 || bccomp($value, $maxSnowflakeValue, 1) > 0) {
                return false;
            }

            $parsed = Snowflake::parseId($value);

            return count(array_filter($parsed)) === count($parsed);
        };
    }
}
