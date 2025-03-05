<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Macros;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Schema\ColumnDefinition;

class BlueprintMacros
{
    public static function boot(): void
    {
        Blueprint::macro('snowflake', function (string $column = 'id'): ColumnDefinition {
            return $this->unsignedBigInteger($column);
        });

        Blueprint::macro('foreignSnowflake', function (string $column): ColumnDefinition {
            return $this->foreignId($column);
        });

        Blueprint::macro('foreignSnowflakeFor', function (string $model, ?string $column = null): ColumnDefinition {
            /** @var class-string<Model> $model */
            return $this->foreignSnowflake($column ?? (new $model())->getForeignKey());
        });

        Blueprint::macro('morphsSnowflake', function (string $name, ?string $indexName = null): void {
            $this->snowflake("{$name}_id");
            $this->text("{$name}_type");

            $this->index(
                ["{$name}_id", "{$name}_type"],
                $indexName,
            );
        });

        Blueprint::macro('nullableMorphsSnowflake', function (string $name, ?string $indexName = null): void {
            $this->snowflake("{$name}_id")->nullable();
            $this->text("{$name}_type")->nullable();

            $this->index(
                ["{$name}_id", "{$name}_type"],
                $indexName,
            );
        });
    }
}
