<?php

declare(strict_types=1);

use Godruoyi\Snowflake\Snowflake;

if (! function_exists('snowflake')) {
    /**
     * Get the Snowflake instance.
     */
    function snowflake(): Snowflake
    {
        return resolve(Snowflake::class);
    }
}
