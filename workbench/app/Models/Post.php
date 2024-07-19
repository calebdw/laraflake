<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use CalebDW\Laraflake\Casts\AsSnowflake;
use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Post extends Model
{
    use HasSnowflakes;

    protected $casts = [
        'user_id' => AsSnowflake::class,
    ];

    protected $guarded = [];

    /** @return list<string> */
    public function uniqueIds(): array
    {
        return ['slug'];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
