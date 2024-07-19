<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Mixins;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\ColumnDefinition;

/** @mixin \Illuminate\Database\Schema\Blueprint */
class BlueprintMixin
{
    /** @return Closure(string): ColumnDefinition */
    public function snowflake(): Closure
    {
        return function (string $column = 'id'): ColumnDefinition {
            return $this->unsignedBigInteger($column);
        };
    }

    /** @return Closure(string): ColumnDefinition */
    public function foreignSnowflake(): Closure
    {
        return function (string $column): ColumnDefinition {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            return $this->foreignId($column);
        };
    }

    /** @return Closure(class-string<Model>, string|null): ColumnDefinition */
    public function foreignSnowflakeFor(): Closure
    {
        return function (string $model, ?string $column = null): ColumnDefinition {
            /** @var \Illuminate\Database\Schema\Blueprint $this */
            /** @var class-string<\Illuminate\Database\Eloquent\Model> $model */
            return $this->foreignSnowflake($column ?? (new $model())->getForeignKey());
        };
    }
}
