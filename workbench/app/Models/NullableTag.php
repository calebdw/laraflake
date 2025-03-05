<?php

declare(strict_types=1);

namespace Workbench\App\Models;

use CalebDW\Laraflake\Concerns\HasSnowflakes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class NullableTag extends Model
{
    use HasSnowflakes;

    protected $guarded = [];

    /**
     * Get the taggable model that the tag belongs to.
     *
     * @return MorphTo<Model, $this>
     */
    public function taggable(): MorphTo
    {
        return $this->morphTo();
    }
}
