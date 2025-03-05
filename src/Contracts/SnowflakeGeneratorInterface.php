<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Contracts;

use Closure;
use Godruoyi\Snowflake\SequenceResolver;

interface SnowflakeGeneratorInterface
{
    /**
     * Get a unique identifier.
     */
    public function id(): string;

    /**
     * Parse the unique identifier and return its components.
     *
     * @param string $id The unique identifier to parse
     * @param bool $transform Whether to transform binary parts to decimal
     * @return array<string, int|string> The ID components. Keys may vary by implementation
     */
    public function parseId(string $id, bool $transform = false): array;

    /**
     * Set start timestamp.
     *
     * @param int $millisecond Timestamp in milliseconds
     */
    public function setStartTimeStamp(int $millisecond): SnowflakeGeneratorInterface;

    /**
     * Set sequence resolver.
     */
    public function setSequenceResolver(SequenceResolver|Closure $sequence): SnowflakeGeneratorInterface;
}
