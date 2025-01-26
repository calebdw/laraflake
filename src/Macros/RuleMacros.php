<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Macros;

use CalebDW\Laraflake\Rules\Snowflake as SnowflakeRule;
use Illuminate\Validation\Rule;

class RuleMacros
{
    public static function boot(): void
    {
        Rule::macro('snowflake', fn () => new SnowflakeRule());
    }
}
