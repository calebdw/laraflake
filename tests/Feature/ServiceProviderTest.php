<?php

declare(strict_types=1);

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Illuminate\Contracts\Cache\Repository;

it('binds random sequence resolver when there is not a cache', function () {
    app()->offsetUnset('cache.store');
    app()->forgetInstance(SequenceResolver::class);

    expect(app()->make(SequenceResolver::class))
        ->toBeInstanceOf(RandomSequenceResolver::class);
});

it('binds laravel sequence resolver when there is a cache', function () {
    app()->instance('cache.store', mock(Repository::class));
    app()->forgetInstance(SequenceResolver::class);

    expect(app()->make(SequenceResolver::class))
        ->toBeInstanceOf(LaravelSequenceResolver::class);
});
