<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Concerns;

use CalebDW\Laraflake\Casts\AsSnowflake;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Str;

/** @mixin \Illuminate\Database\Eloquent\Model */
trait HasSnowflakes
{
    public function initializeHasSnowflakes(): void
    {
        $this->usesUniqueIds = true;

        $this->casts = [
            ...$this->casts,
            ...array_fill_keys($this->uniqueIds(), AsSnowflake::class),
        ];
    }

    /**
     * Get the columns that should receive a unique identifier.
     *
     * @return list<string>
     */
    public function uniqueIds(): array
    {
        return [$this->getKeyName()];
    }

    /** Generate a new Snowflake for the model. */
    public function newUniqueId(): string
    {
        return Str::snowflake();
    }

    /** @inheritDoc */
    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if (! is_numeric($value)) {
            return parent::resolveRouteBindingQuery($query, $value, $field);
        }

        $field ??= $this->getRouteKeyName();

        if (in_array($field, $this->uniqueIds(), true) && ! Str::isSnowflake($value)) {
            throw new ModelNotFoundException()->setModel($this::class, (string) $value);
        }

        return parent::resolveRouteBindingQuery($query, $value, $field);
    }

    /** @inheritDoc */
    public function getKeyType(): string
    {
        if (in_array($this->getKeyName(), $this->uniqueIds(), strict: true)) {
            return 'string';
        }

        return $this->keyType;
    }

    /** @inheritDoc */
    public function getIncrementing(): bool
    {
        if (in_array($this->getKeyName(), $this->uniqueIds(), strict: true)) {
            return false;
        }

        return $this->incrementing;
    }
}
