<?php
/**
 * This Source Code Form is subject to the terms of the Mozilla Public
 *  License, v. 2.0. If a copy of the MPL was not distributed with this
 *  file, You can obtain one at http://mozilla.org/MPL/2.0/.
 */

namespace HighQualityFriends\Snowflake;

use PHPUnit\Framework\TestCase;

class SnowflakeGeneratorSettingsTest extends TestCase
{
    public function testGetSequenceNumberBitsWhenDefaultNodeIdBits()
    {
        $settings = SnowflakeGeneratorSettings::newInstance();

        $this->assertEquals(12, $settings->getSequenceNumberBits(), "totalBits(64) - timeBits(42) - nodeIdBits(10)");
    }

    public function testGetSequenceNumberBitsWhenCustomNodeIdBitsSets()
    {
        $settings = SnowflakeGeneratorSettings::newInstance()->setNodeIdBits(5);

        $this->assertEquals(17, $settings->getSequenceNumberBits(), "totalBits(64) - timeBits(42) - nodeIdBits(5)");
    }

    public function testGetMaxSequenceNumber()
    {
        $settings = SnowflakeGeneratorSettings::newInstance();

        $this->assertEquals(4095, $settings->getMaxSequenceNumber(), "max value on sequenceNumberBits(12)");
    }

    public function testGetMaxNodeId()
    {
        $settings = SnowflakeGeneratorSettings::newInstance();

        $this->assertEquals(1023, $settings->getMaxNodeId(), "max value on nodeIdBits(10)");
    }

    public function testGetNodeIdLeftShift()
    {
        $settings = SnowflakeGeneratorSettings::newInstance();

        $this->assertEquals(12, $settings->getNodeIdLeftShift(), "sequenceNumberBits(12)");
    }

    public function testGetTimeLeftShift()
    {
        $settings = SnowflakeGeneratorSettings::newInstance();

        $this->assertEquals(22, $settings->getTimeLeftShift(), "sequenceNumberBits(12) + nodeIdBits(10)");
    }

}
