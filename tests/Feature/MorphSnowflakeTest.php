<?php

declare(strict_types=1);

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Workbench\App\Models\Post;
use Workbench\App\Models\Tag;
use Workbench\App\Models\User;

beforeEach(function () {
    test()->user = User::create(['name' => 'John Doe']);
    test()->post = Post::create([
        'title'   => 'My First Post',
        'user_id' => test()->user->getKey(),
    ]);
});

it('creates tags table with snowflakeMorphs columns', function () {
    expect(Schema::hasColumn('tags', 'taggable_id'))->toBeTrue()
        ->and(Schema::hasColumn('tags', 'taggable_type'))->toBeTrue();

    $columns = DB::getSchemaBuilder()->getColumnListing('tags');

    $userTag = new Tag(['name' => 'User Tag']);
    test()->user->tags()->save($userTag);

    $postTag = new Tag(['name' => 'Post Tag']);
    test()->post->tags()->save($postTag);

    expect($userTag->refresh()->taggable)->toBeInstanceOf(User::class)
        ->and($postTag->refresh()->taggable)->toBeInstanceOf(Post::class);

    expect(test()->user->tags)
        ->toHaveCount(1)
        ->first()->name->toBe('User Tag');

    expect(test()->post->tags)
        ->toHaveCount(1)
        ->first()->name->toBe('Post Tag');
});

it('creates nullable_tags table with nullableSnowflakeMorphs columns', function () {
    expect(Schema::hasColumn('nullable_tags', 'taggable_id'))->toBeTrue()
        ->and(Schema::hasColumn('nullable_tags', 'taggable_type'))->toBeTrue();

    DB::table('nullable_tags')->insert([
        'id'            => snowflake()->id(),
        'name'          => 'Null Tag',
        'taggable_id'   => null,
        'taggable_type' => null,
        'created_at'    => now(),
        'updated_at'    => now(),
    ]);

    $nullTag = DB::table('nullable_tags')->where('name', 'Null Tag')->first();
    expect($nullTag)
        ->not->toBeNull()
        ->and($nullTag->taggable_id)->toBeNull()
        ->and($nullTag->taggable_type)->toBeNull();
});
