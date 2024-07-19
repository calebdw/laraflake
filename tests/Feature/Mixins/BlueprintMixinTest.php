<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Support\Facades\DB;
use Workbench\App\Models\User;

beforeEach(function () {
    test()->blueprint = new Blueprint('snowflake');
});

it('adds snowflake column', function () {
    test()->blueprint->snowflake();
    test()->blueprint->snowflake('foo');

    $sql = test()->blueprint->toSql(DB::connection(), new SQLiteGrammar());

    expect($sql)->toBe([
        'alter table "snowflake" add column "id" integer not null',
        'alter table "snowflake" add column "foo" integer not null',
    ]);
});

it('adds foreign snowflake column', function () {
    test()->blueprint->foreignSnowflake('user_id');

    $sql = test()->blueprint->toSql(DB::connection(), new SQLiteGrammar());

    expect($sql)->toBe([
        'alter table "snowflake" add column "user_id" integer not null',
    ]);
});

it('adds foreign snowflake for a model', function () {
    test()->blueprint->foreignSnowflakeFor(User::class);

    $sql = test()->blueprint->toSql(DB::connection(), new SQLiteGrammar());

    expect($sql)->toBe([
        'alter table "snowflake" add column "user_id" integer not null',
    ]);
});
