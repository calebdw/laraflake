<?php

declare(strict_types=1);

use CalebDW\Laraflake\Adapters\GodruoyiSnowflakeAdapter;
use CalebDW\Laraflake\Adapters\GodruoyiSonyflakeAdapter;
use CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface;
use Godruoyi\Snowflake\RandomSequenceResolver;

it('creates a snowflake adapter and generates IDs', function () {
    $adapter = new GodruoyiSnowflakeAdapter(1, 1);

    expect($adapter)->toBeInstanceOf(SnowflakeGeneratorInterface::class);
    expect($adapter->id())->toBeString();
});

it('parses IDs with the snowflake adapter', function () {
    $adapter = new GodruoyiSnowflakeAdapter(1, 1);
    $id      = $adapter->id();

    $parsed = $adapter->parseId($id);
    expect($parsed)->toBeArray();
    expect($parsed)->toHaveKeys(['timestamp', 'datacenter', 'workerid', 'sequence']);

    $parsedTransformed = $adapter->parseId($id, true);
    expect($parsedTransformed)->toBeArray();
});

it('sets sequence resolver on snowflake adapter', function () {
    $adapter  = new GodruoyiSnowflakeAdapter(1, 1);
    $resolver = new RandomSequenceResolver();

    $result = $adapter->setSequenceResolver($resolver);

    expect($result)->toBe($adapter);
});

it('sets sequence resolver with closure on snowflake adapter', function () {
    $adapter = new GodruoyiSnowflakeAdapter(1, 1);

    $resolver = function ($currentTime) {
        return mt_rand(0, 4095);
    };

    $result = $adapter->setSequenceResolver($resolver);

    expect($result)->toBe($adapter);
});

it('creates a sonyflake adapter and generates IDs', function () {
    $adapter = new GodruoyiSonyflakeAdapter(1);

    expect($adapter)->toBeInstanceOf(SnowflakeGeneratorInterface::class);
    expect($adapter->id())->toBeString();
});

it('parses IDs with the sonyflake adapter', function () {
    $adapter = new GodruoyiSonyflakeAdapter(1);
    $id      = $adapter->id();

    $parsed = $adapter->parseId($id);
    expect($parsed)->toBeArray();
    expect($parsed)->toHaveKeys(['timestamp', 'sequence', 'machineid']);

    $parsedTransformed = $adapter->parseId($id, true);
    expect($parsedTransformed)->toBeArray();
});

it('sets sequence resolver on sonyflake adapter', function () {
    $adapter  = new GodruoyiSonyflakeAdapter(1);
    $resolver = new RandomSequenceResolver();

    $result = $adapter->setSequenceResolver($resolver);

    expect($result)->toBe($adapter);
});

it('sets sequence resolver with closure on sonyflake adapter', function () {
    $adapter = new GodruoyiSonyflakeAdapter(1);

    $resolver = function ($currentTime) {
        return mt_rand(0, 4095);
    };

    $result = $adapter->setSequenceResolver($resolver);

    expect($result)->toBe($adapter);
});
