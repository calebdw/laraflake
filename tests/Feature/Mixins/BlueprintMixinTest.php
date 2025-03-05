<?php

declare(strict_types=1);

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\Grammars\SQLiteGrammar;
use Illuminate\Support\Facades\DB;
use Workbench\App\Models\User;

use function Orchestra\Testbench\laravel_version_compare;

beforeEach(function () {
    test()->blueprint = resolve(Blueprint::class, ['table' => 'snowflake']);

    test()->sql = function () {
        if (laravel_version_compare('12.0', '>=')) {
            return test()->blueprint->toSql();
        }

        return test()->blueprint->toSql(DB::connection(), new SQLiteGrammar());
    };
});

it('adds snowflake column', function () {
    test()->blueprint->snowflake();
    test()->blueprint->snowflake('foo');

    expect((test()->sql)())->toBe([
        'alter table "snowflake" add column "id" integer not null',
        'alter table "snowflake" add column "foo" integer not null',
    ]);
});

it('adds foreign snowflake column', function () {
    test()->blueprint->foreignSnowflake('user_id');

    expect((test()->sql)())->toBe([
        'alter table "snowflake" add column "user_id" integer not null',
    ]);
});

it('adds foreign snowflake for a model', function () {
    test()->blueprint->foreignSnowflakeFor(User::class);

    expect((test()->sql)())->toBe([
        'alter table "snowflake" add column "user_id" integer not null',
    ]);
});

it('adds morph snowflake columns', function () {
    test()->blueprint->snowflakeMorphs('taggable');

    expect((test()->sql)())->toBe([
        'alter table "snowflake" add column "taggable_id" integer not null',
        'alter table "snowflake" add column "taggable_type" text not null',
        'create index "snowflake_taggable_id_taggable_type_index" on "snowflake" ("taggable_id", "taggable_type")',
    ]);
});

it('adds nullable morph snowflake columns', function () {
    test()->blueprint->nullableSnowflakeMorphs('taggable');

    expect((test()->sql)())->toBe([
        'alter table "snowflake" add column "taggable_id" integer',
        'alter table "snowflake" add column "taggable_type" text',
        'create index "snowflake_taggable_id_taggable_type_index" on "snowflake" ("taggable_id", "taggable_type")',
    ]);
});
