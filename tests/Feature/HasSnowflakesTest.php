<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Support\Facades\Route;
use Workbench\App\Models\Post;
use Workbench\App\Models\User;

beforeEach(function () {
    Route::middleware(SubstituteBindings::class)->group(function ($router): void {
        $router->get('/posts-id/{post}', fn (Post $post) => response()->json($post));
        $router->get('/posts-slug/{post:slug}', fn (Post $post) => response()->json($post));
        $router->get('/users-id/{user}', fn (User $user) => response()->json($user));
        $router->get('/users-name/{user:name}', fn (User $user) => response()->json($user));
    });

    test()->user = User::create(['name' => 'John Doe']);
    test()->post = Post::create([
        'title'   => 'My First Post',
        'user_id' => test()->user->getKey(),
    ]);
});

it('creates a user with snowflake primary key', function () {
    expect(test()->user)
        ->getKey()->not->toBeNull();
});

it('casts user primary key', function (int|string $id) {
    expect(test()->user->refresh())
        ->getKey()->toBeString();

    test()->user->id = $id;
    test()->user->save();

    expect(test()->user)
        ->getKey()->toBe((string) $id)
        ->getRawOriginal('id')->toBeInt();
})->with([
    '1234567890123456789',
    1234567890123456789,
]);

it('binds route model', function () {
    test()->get('/users-id/' . test()->user->getKey())
        ->assertOk()
        ->assertSee(test()->user->name);
    test()->get('/posts-slug/' . test()->post->slug)
        ->assertOk()
        ->assertSee(test()->post->title);
});

it('throws exception for invalid snowflake', function () {
    test()->withoutExceptionHandling();

    test()->get('/users-id/invalid');
})->throws(ModelNotFoundException::class);

it('throws exception for non-unique field', function () {
    test()->withoutExceptionHandling();

    test()->get('/users-name/' . test()->user->getKey());
})->throws(ModelNotFoundException::class);

it('returns the key type for snowflake columns', function () {
    expect(test()->user)
        ->getKeyType()->toBe('string');

    expect(test()->post)
        ->getKeyType()->toBe('int');
});
