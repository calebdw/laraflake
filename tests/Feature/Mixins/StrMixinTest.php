<?php

declare(strict_types=1);

use Illuminate\Support\Str;

it('creates a snowflake', function () {
    expect(Str::snowflake())->toBeString();
});

it('checks if value is a snowflake', function (mixed $value, bool $expected) {
    expect(Str::isSnowflake($value))->toBe($expected);
})->with('snowflakes');
