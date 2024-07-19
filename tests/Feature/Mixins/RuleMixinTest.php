<?php

declare(strict_types=1);

use CalebDW\Laraflake\Rules\Snowflake;
use Illuminate\Validation\Rule;

it('has snowflake validation rule', function () {
    expect(Rule::snowflake())->toBeInstanceOf(Snowflake::class);
});
