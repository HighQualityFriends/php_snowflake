<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;


use SAREhub\Commons\Time\TimeProvider;

class SnowflakeGenerator
{
    const TOTAL_BITS = 64;
    const TIME_BITS = 42;
    const MACHINE_ID_BITS = 10;
    const SEQUENCE_NUMBER_BITS = 12;

    const TIME_LEFT_SHIFT = self::TOTAL_BITS - self::TIME_BITS;
    const MACHINE_ID_LEFT_SHIFT = self:: TOTAL_BITS - self::TIME_BITS - self::MACHINE_ID_BITS;

    /**
     * @var TimeProvider
     */
    private $timeProvider;

    /**
     * @var int
     */
    private $machineId;

    /**
     * @var Sequencer
     */
    private $sequencer;

    public function __construct(TimeProvider $timeProvider, int $machineId, Sequencer $sequencer)
    {
        $this->timeProvider = $timeProvider;
        $this->machineId = $machineId;
        $this->sequencer = $sequencer;
    }

    public function getNext(): int
    {
        $id = $this->timeProvider->getInMilliseconds() << self::TIME_LEFT_SHIFT;
        $id |= $this->machineId << self::MACHINE_ID_LEFT_SHIFT;
        return $id;
    }
}