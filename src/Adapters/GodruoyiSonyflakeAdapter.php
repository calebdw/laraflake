<?php

declare(strict_types=1);

namespace CalebDW\Laraflake\Adapters;

use CalebDW\Laraflake\Contracts\SnowflakeGeneratorInterface;
use Closure;
use Godruoyi\Snowflake\SequenceResolver;
use Godruoyi\Snowflake\Sonyflake as BaseSonyflake;

class GodruoyiSonyflakeAdapter implements SnowflakeGeneratorInterface
{
    private readonly BaseSonyflake $sonyflake;

    public function __construct(int $machineId)
    {
        $this->sonyflake = new BaseSonyflake($machineId);
    }

    /**
     * Get a unique identifier.
     */
    public function id(): string
    {
        return (string) $this->sonyflake->id();
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
        return $this->sonyflake->parseId($id, $transform);
    }

    /**
     * Set start timestamp.
     *
     * @param int $millisecond Timestamp in milliseconds
     */
    public function setStartTimeStamp(int $millisecond): SnowflakeGeneratorInterface
    {
        $this->sonyflake->setStartTimeStamp($millisecond);

        return $this;
    }

    /**
     * Set sequence resolver.
     */
    public function setSequenceResolver(SequenceResolver|Closure $sequence): SnowflakeGeneratorInterface
    {
        $this->sonyflake->setSequenceResolver($sequence);

        return $this;
    }
}
