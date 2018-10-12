<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;


class SnowflakeGenerator
{
    /**
     * @var callable
     */
    private $timeProvider;

    /**
     * @var SnowflakeGeneratorSettings
     */
    private $settings;

    /**
     * @var int
     */
    private $currentSequenceNumber;

    /**
     * @var int
     */
    private $lastTime = -1;


    /**
     * @param SnowflakeGeneratorSettings $settings
     * @param callable $timeProvider Function must return time with milliseconds precision.
     */
    public function __construct(SnowflakeGeneratorSettings $settings, ?callable $timeProvider = null)
    {
        $this->timeProvider = $timeProvider ?? self::defaultTimeProvider();
        $this->settings = $settings;
        $this->currentSequenceNumber = $this->settings->getStartSequenceNumber();
        $this->settings->check();
    }

    public static function defaultTimeProvider(): callable
    {
        return function () {
            return (int)(microtime(true) * 1000);
        };
    }

    public function getNext(): int
    {
        $this->checkSequenceNumberRollover();
        $time = $this->getNextTime();

        $id = SnowflakeId::createRaw($time, $this->currentSequenceNumber, $this->settings);
        $this->currentSequenceNumber++;
        $this->lastTime = $time;
        return $id;
    }

    private function checkSequenceNumberRollover(): void
    {
        if ($this->currentSequenceNumber > $this->settings->getMaxSequenceNumber()) {
            $this->currentSequenceNumber = 0;
        }
    }

    private function getNextTime(): int
    {
        $time = ($this->timeProvider)();
        if ($this->currentSequenceNumber === 0 && $this->lastTime === $time) {
            while ($time === $this->lastTime) {
                $time = ($this->timeProvider)();
            }
        }
        return $time;
    }
}