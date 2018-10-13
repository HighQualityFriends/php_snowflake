<?php

use HighQualityFriends\Snowflake\SnowflakeGenerator;
use HighQualityFriends\Snowflake\SnowflakeGeneratorSettings;
use PhpBench\Benchmark\Metadata\Annotations\BeforeMethods;
use PhpBench\Benchmark\Metadata\Annotations\Iterations;
use PhpBench\Benchmark\Metadata\Annotations\Revs;

/**
 * @BeforeMethods({"init"})
 */
class SnowflakeGeneratorBench
{
    /**
     * @var SnowflakeGenerator
     */
    private $generator;

    /**
     * @var SnowflakeGeneratorSettings
     */
    private $settings;

    public function init()
    {
        $this->settings = SnowflakeGeneratorSettings::newInstance()->setNodeId(0);
        $this->generator = new SnowflakeGenerator($this->settings);
    }


    /**
     * @Revs(10000)
     * @Iterations(5)
     */
    public function benchGetNext()
    {
        $this->generator->getNext();
    }
}