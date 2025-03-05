<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Adapters;

use CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface;
use Closure;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Snowflake as BaseSnowflake;

class GodruoyiSnowflakeAdapter implements SnowflakeGeneratorInterface
{
    private readonly BaseSnowflake $snowflake;

    public function __construct(int $datacenterId, int $workerId)
    {
        $this->snowflake = new BaseSnowflake($datacenterId, $workerId);
    }

    /**
     * Get a unique identifier.
     */
    public function id(): string
    {
        return (string) $this->snowflake->id();
    }

    /**
     * Parse the unique identifier and return its components.
     *
     * @param string $id The unique identifier to parse
     * @param bool $transform Whether to transform binary parts to decimal
     * @return array<string, float|int|string> The ID components. Keys may vary by implementation
     */
    public function parseId(string $id, bool $transform = false): array
    {
        return $this->snowflake->parseId($id, $transform);
    }

    /**
     * Set start timestamp.
     *
     * @param int $millisecond Timestamp in milliseconds
     */
    public function setStartTimeStamp(int $millisecond): SnowflakeGeneratorInterface
    {
        $this->snowflake->setStartTimeStamp($millisecond);

        return $this;
    }

    /**
     * Set sequence resolver.
     */
    public function setSequenceResolver(SequenceResolver|Closure $sequence): SnowflakeGeneratorInterface
    {
        $this->snowflake->setSequenceResolver($sequence);

        return $this;
    }
}
