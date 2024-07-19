<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Casts;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

/** @implements CastsAttributes<string,numeric> */
class AsSnowflake implements CastsAttributes
{
    /** @inheritDoc */
    public function get(Model $model, string $key, mixed $value, array $attributes): ?string
    {
        return is_numeric($value) ? (string) $value : null;
    }

    /** @inheritDoc */
    public function set(Model $model, string $key, mixed $value, array $attributes): ?int
    {
        return is_null($value) ? null : (int) $value;
    }
}
