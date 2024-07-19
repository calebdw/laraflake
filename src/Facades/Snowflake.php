<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Facades;

use Godruoyi\Snowflake\Snowflake as GodruoyiSnowflake;
use Illuminate\Support\Facades\Facade;

/** @mixin \Godruoyi\Snowflake\Snowflake */
class Snowflake extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return GodruoyiSnowflake::class;
    }
}
