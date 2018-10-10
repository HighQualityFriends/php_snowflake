<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;

use Mockery\Adapter\Phpunit\MockeryPHPUnitIntegration;
use Mockery\MockInterface;
use PHPUnit\Framework\TestCase;
use SAREhub\Commons\Time\StaticTimeProvider;
use SAREhub\Commons\Time\TimeProvider;

class SnowflakeGeneratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var TimeProvider | MockInterface
     */
    private $timeProvider;

    protected function setUp()
    {
        $this->timeProvider = \Mockery::mock(TimeProvider::class);
    }

    public function testGetNext()
    {
        $machineId = 1;
        $generator = new SnowflakeGenerator($this->timeProvider, $machineId);

        $time = strtotime("2018-10-08 00:00")*1000;
        $this->timeProvider->expects("getInMilliseconds")->andReturn($time);
        $id = new SnowflakeIdWrapper($generator->getNext());
        $this->assertEquals($time, $id->getTime(), "time part");
        $this->assertEquals($machineId, $id->getMachineId(), "machine id part");
        $this->assertEquals(0, $id->getSequenceNumber(), "sequence number part");
    }
}
