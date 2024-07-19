<?php

declare(strict_types=1);

use CalebDW\Laraflake\Facades\Snowflake as SnowflakeFacade;
use Godruoyi\Snowflake\Snowflake;

it('has snowflake facade', function () {
    expect(SnowflakeFacade::getFacadeRoot())->toBeInstanceOf(Snowflake::class);
});
