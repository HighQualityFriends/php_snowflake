<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;


class SnowflakeGeneratorSettings
{
    public const TOTAL_BITS = 64;
    public const TIME_BITS = 42;

    public const DEFAULT_MACHINE_ID_BITS = 10;

    /**
     * @var int
     */
    private $nodeIdBits;

    /**
     * @var int
     */
    private $nodeId;

    /**
     * @var int
     */
    private $startSequenceNumber;

    /**
     * @var int
     */
    private $calculatedTimeLeftShift;

    /**
     * @var int
     */
    private $calculatedMaxSequenceNumber;

    /**
     * @var int
     */
    private $calculatedSequenceNumberBits;


    public function __construct()
    {
        $this->nodeIdBits = self::DEFAULT_MACHINE_ID_BITS;
        $this->nodeId = -1;
        $this->startSequenceNumber = 0;

        $this->recalculate();
    }

    public static function newInstance(): SnowflakeGeneratorSettings
    {
        return new self();
    }

    public function getNodeId(): int
    {
        return $this->nodeId;
    }

    public function setNodeId(int $nodeId): SnowflakeGeneratorSettings
    {
        $this->nodeId = $nodeId;
        return $this;
    }

    public function getNodeIdBits(): int
    {
        return $this->nodeIdBits;
    }

    public function setNodeIdBits(int $nodeIdBits): SnowflakeGeneratorSettings
    {
        $this->nodeIdBits = $nodeIdBits;
        $this->recalculate();
        return $this;
    }

    public function getStartSequenceNumber(): int
    {
        return $this->startSequenceNumber;
    }

    public function setStartSequenceNumber(int $startSequenceNumber): SnowflakeGeneratorSettings
    {
        $this->startSequenceNumber = $startSequenceNumber;
        return $this;
    }

    public function getSequenceNumberBits(): int
    {
        return $this->calculatedSequenceNumberBits;
    }

    public function getMaxSequenceNumber(): int
    {
        return $this->calculatedMaxSequenceNumber;
    }

    public function getMaxNodeId(): int
    {
        return -1 ^ (-1 << $this->getNodeIdBits());
    }

    public function getNodeIdLeftShift(): int
    {
        return $this->getSequenceNumberBits();
    }

    public function getTimeLeftShift(): int
    {
        return $this->calculatedTimeLeftShift;
    }

    private function recalculate() {
        $this->calculatedSequenceNumberBits = self::TOTAL_BITS - (self::TIME_BITS + $this->getNodeIdBits());
        $this->calculatedMaxSequenceNumber = -1 ^ (-1 << $this->getSequenceNumberBits());
        $this->calculatedTimeLeftShift = $this->getNodeIdLeftShift() + $this->getNodeIdBits();
    }

    public function check(): void
    {
        if ($this->nodeId > $this->getMaxNodeId() || $this->nodeId < 0) {
            throw new \InvalidArgumentException("nodeId can't be greater than " . $this->getMaxNodeId() . " or less than zero: $this->nodeId");
        }
    }
}