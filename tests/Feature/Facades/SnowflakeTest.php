<?php

declare(strict_types=1);

use CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface;
use CalebDW\Laraflake\Facades\Snowflake as SnowflakeFacade;

it('has snowflake facade', function () {
    expect(SnowflakeFacade::getFacadeRoot())->toBeInstanceOf(SnowflakeGeneratorInterface::class);
});
