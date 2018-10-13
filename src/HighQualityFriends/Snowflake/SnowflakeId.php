<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;


class SnowflakeId implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var SnowflakeGeneratorSettings
     */
    private $settings;

    public function __construct(int $id, SnowflakeGeneratorSettings $settings)
    {
        $this->id = $id;
        $this->settings = $settings;
    }

    public static function create(int $time, int $sequenceNumber, SnowflakeGeneratorSettings $settings): SnowflakeId
    {
        $id = self::createRaw($time, $sequenceNumber, $settings);
        return new self($id, $settings);
    }

    public static function createRaw(int $time, int $sequenceNumber, SnowflakeGeneratorSettings $settings): int
    {
        if ($sequenceNumber > $settings->getMaxSequenceNumber()) {
            throw new \InvalidArgumentException(
                sprintf("sequenceNumber is greater then %d: %d", $settings->getMaxSequenceNumber(), $sequenceNumber));
        }

        $id = $time << $settings->getTimeLeftShift();
        $id |= $settings->getNodeId() << $settings->getNodeIdLeftShift();
        $id |= $sequenceNumber;
        return $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return self::extractBits($this->id, $this->settings->getTimeLeftShift(), SnowflakeGeneratorSettings::TIME_BITS);
    }

    public function getNodeId(): int
    {
        return self::extractBits($this->id, $this->settings->getNodeIdLeftShift(), $this->settings->getNodeIdBits()
        );
    }

    public function getSequenceNumber(): int
    {
        return self::extractBits($this->id, 0, $this->settings->getSequenceNumberBits());
    }

    private static function extractBits(int $number, int $start, int $bits): int
    {
        return (((1 << $bits) - 1) & ($number >> $start));
    }

    public function jsonSerialize()
    {
        return $this->id;
    }

    public function __toString()
    {
        return (string)$this->getId();
    }
}