<?php

declare(strict_types=1);

use CalebDW\Laraflake\Rules\Snowflake;
use Illuminate\Support\Facades\Validator;

it('validates if value is a snowflake using validation rule', function (mixed $value, bool $expected) {
    $rule      = ['value' => [new Snowflake()]];
    $data      = ['value' => $value];
    $validator = Validator::make($data, $rule);

    expect($validator->passes())->toBe($expected);
})->with('snowflakes');
