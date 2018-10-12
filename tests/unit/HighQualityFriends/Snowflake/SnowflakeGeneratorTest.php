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


class CallableMock
{
    public function __invoke()
    {
    }

    /**
     * @return callable | MockInterface
     */
    public static function create(): callable
    {
        return \Mockery::mock(self::class);
    }
}

class SnowflakeGeneratorTest extends TestCase
{
    use MockeryPHPUnitIntegration;

    /**
     * @var CallableMock | MockInterface
     */
    private $timeProvider;

    /**
     * @var  SnowflakeGeneratorSettings
     */
    private $settings;

    /**
     * @var int
     */
    private $startTime;

    protected function setUp()
    {
        $this->timeProvider = CallableMock::create();
        $this->settings = SnowflakeGeneratorSettings::newInstance();
        $this->startTime = strtotime("2018-10-08 00:00") * 1000;
    }

    public function testCreateWhenNodeIdIsGreaterThenMax()
    {
        $nodeId = $this->settings->getMaxNodeId() + 1;
        $this->settings->setNodeId($nodeId);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("nodeId can't be greater than " . $this->settings->getMaxNodeId() . " or less than zero: $nodeId");
        new SnowflakeGenerator($this->settings, $this->timeProvider);
    }

    public function testCreateWhenNodeIdIsLessThenZero()
    {
        $nodeId = -1;
        $this->settings->setNodeId($nodeId);
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("nodeId can't be greater than " . $this->settings->getMaxNodeId() . " or less than zero: $nodeId");
        new SnowflakeGenerator($this->settings, $this->timeProvider);
    }

    public function testGetNext()
    {
        $nodeId = 1;
        $this->settings->setNodeId($nodeId);
        $sequenceNumber = 2;
        $this->settings->setStartSequenceNumber($sequenceNumber);
        $generator = new SnowflakeGenerator($this->settings, $this->timeProvider);
        $this->timeProvider->allows("__invoke")->andReturn($this->startTime);

        $this->assertId($this->startTime, $nodeId, $sequenceNumber, $generator->getNext());
    }

    public function testGetNextWhenCalledNextTime()
    {
        $nodeId = 1;
        $this->settings->setNodeId($nodeId);
        $sequenceNumber = 2;
        $this->settings->setStartSequenceNumber($sequenceNumber);
        $generator = new SnowflakeGenerator($this->settings, $this->timeProvider);
        $this->timeProvider->allows("__invoke")->andReturn($this->startTime);
        $generator->getNext();

        $this->assertId($this->startTime, $nodeId, 3, $generator->getNext());
    }

    public function testGetNextWhenSequenceNumberRolloverNotInSameMillisecond()
    {
        $nodeId = 1;
        $this->settings->setNodeId($nodeId);
        $sequenceNumber = 4095;
        $this->settings->setStartSequenceNumber($sequenceNumber);
        $generator = new SnowflakeGenerator($this->settings, $this->timeProvider);
        $this->timeProvider->allows("__invoke")->andReturn($this->startTime, $this->startTime + 1);
        $generator->getNext();

        $this->assertId($this->startTime + 1, $nodeId, 0, $generator->getNext());
    }

    public function testGetNextWhenSequenceNumberRolloverInSameMillisecond()
    {
        $nodeId = 1;
        $this->settings->setNodeId($nodeId);
        $sequenceNumber = 4095;
        $this->settings->setStartSequenceNumber($sequenceNumber);
        $generator = new SnowflakeGenerator($this->settings, $this->timeProvider);
        $this->timeProvider->allows("__invoke")->andReturn($this->startTime, $this->startTime, $this->startTime + 1);
        $generator->getNext();

        $this->assertId($this->startTime + 1, $nodeId, 0, $generator->getNext());
    }

    private function assertId(int $expectedTime, int $expectedNodeId, int $expectedSequenceNumber, int $current)
    {
        $id = new SnowflakeId($current, $this->settings);
        $this->assertEquals($expectedTime, $id->getTime(), "time part");
        $this->assertEquals($expectedNodeId, $id->getnodeId(), "nodeId part");
        $this->assertEquals($expectedSequenceNumber, $id->getSequenceNumber(), "sequenceNumber part");
    }

}
