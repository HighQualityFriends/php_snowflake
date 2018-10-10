<?php


namespace HighQualityFriends\Snowflake;


class SnowflakeIdWrapper implements \JsonSerializable
{
    /**
     * @var int
     */
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTime(): int
    {
        return $this->extractBits($this->id, SnowflakeGenerator::TIME_BITS, SnowflakeGenerator::TIME_LEFT_SHIFT);
    }

    public function getMachineId(): int
    {
        return $this->extractBits($this->id, SnowflakeGenerator::MACHINE_ID_BITS,
            SnowflakeGenerator::MACHINE_ID_LEFT_SHIFT);
    }

    public function getSequenceNumber(): int
    {
        return $this->extractBits($this->id, SnowflakeGenerator::SEQUENCE_NUMBER_BITS, 0);
    }

    private function extractBits(int $number, int $bits, int $start)
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