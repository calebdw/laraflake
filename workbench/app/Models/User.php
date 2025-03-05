<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class User extends Model
{
    use HasSnowflakes;

    protected $guarded = [];

    /** @return HasMany<Post, $this> */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /** @return MorphMany<Tag, $this> */
    public function tags(): MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }
}
