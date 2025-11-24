<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Macros;

use CalebDW\Laraflake\Rules\Snowflake as SnowflakeRule;
use Illuminate\Validation\Rule;

class RuleMacros
{
    public static function boot(): void
    {
        /** @phpstan-ignore argument.staticClosure (only accessed statically) */
        Rule::macro('snowflake', static fn () => new SnowflakeRule());
    }
}
