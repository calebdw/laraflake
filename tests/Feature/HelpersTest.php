<?php

declare(strict_types=1);

use Godruoyi\Snowflake\Snowflake;

it('has snowflake helper', function () {
    expect(snowflake())->toBeInstanceOf(Snowflake::class);
});
