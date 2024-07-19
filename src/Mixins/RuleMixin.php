<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Mixins;

use CalebDW\Laraflake\Rules\Snowflake as SnowflakeRule;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

/** @mixin \Illuminate\Validation\Rule */
class RuleMixin
{
    /** @return Closure(): ValidationRule */
    public function snowflake(): Closure
    {
        return fn (): ValidationRule => new SnowflakeRule();
    }
}
