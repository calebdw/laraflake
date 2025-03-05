<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Tag extends Model
{
    use HasSnowflakes;

    protected $guarded = [];

    /** @return MorphTo<Model, $this> */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
