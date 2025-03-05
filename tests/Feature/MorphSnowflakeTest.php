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

it('creates tags table with morphsSnowflake columns', function () {
    expect(Schema::hasColumn('tags', 'taggable_id'))->toBeTrue();
    expect(Schema::hasColumn('tags', 'taggable_type'))->toBeTrue();

    $columns = DB::getSchemaBuilder()->getColumnListing('tags');

    $userTag = new Tag(['name' => 'User Tag']);
    test()->user->tags()->save($userTag);

    $postTag = new Tag(['name' => 'Post Tag']);
    test()->post->tags()->save($postTag);

    expect($userTag->refresh()->taggable)->toBeInstanceOf(User::class);
    expect($postTag->refresh()->taggable)->toBeInstanceOf(Post::class);

    $tags = test()->user->tags;
    expect($tags)->toHaveCount(1);
    expect($tags->first()->name)->toBe('User Tag');

    $tags = test()->post->tags;
    expect($tags)->toHaveCount(1);
    expect($tags->first()->name)->toBe('Post Tag');
});

it('creates nullable_tags table with nullableMorphsSnowflake columns', function () {
    expect(Schema::hasColumn('nullable_tags', 'taggable_id'))->toBeTrue();
    expect(Schema::hasColumn('nullable_tags', 'taggable_type'))->toBeTrue();

    DB::table('nullable_tags')->insert([
        'id'            => snowflake()->id(),
        'name'          => 'Null Tag',
        'taggable_id'   => null,
        'taggable_type' => null,
        'created_at'    => now(),
        'updated_at'    => now(),
    ]);

    $nullTag = DB::table('nullable_tags')->where('name', 'Null Tag')->first();
    expect($nullTag)->not->toBeNull();
    expect($nullTag->taggable_id)->toBeNull();
    expect($nullTag->taggable_type)->toBeNull();
});
