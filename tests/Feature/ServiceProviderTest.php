<?php

declare(strict_types=1);

use Godruoyi\Snowflake\LaravelSequenceResolver;
use Godruoyi\Snowflake\RandomSequenceResolver;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake;
use Godruoyi\Snowflake\Sonyflake;
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

it('registers the AboutCommand entry', function () {
    test()->artisan('about')
        ->assertSuccessful()
        ->expectsOutputToContain('Laraflake');
});

it('supports Sonyflakes', function () {
    config()->set('laraflake.snowflake_type', Sonyflake::class);

    expect(app()->make(Snowflake::class))
        ->toBeInstanceOf(Sonyflake::class);

    test()->artisan('about')
        ->assertSuccessful()
        ->expectsOutputToContain('Sonyflake');
});

it('throws exception for invalid snowflake type', function () {
    config()->set('laraflake.snowflake_type', 'invalid');

    test()->artisan('about')
        ->assertSuccessful()
        ->doesntExpectOutputToContain('Sonyflake');

    expect(app()->make(Snowflake::class))
        ->toBeInstanceOf(Sonyflake::class);
})->throws(InvalidArgumentException::class);
