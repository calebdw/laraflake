<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Facades;

use CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface;
use Illuminate\Support\Facades\Facade;

/** @mixin \CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface */
class Snowflake extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SnowflakeGeneratorInterface::class;
    }
}
