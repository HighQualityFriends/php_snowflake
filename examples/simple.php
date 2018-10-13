<?php

use HighQualityFriends\Snowflake\SnowflakeGenerator;
use HighQualityFriends\Snowflake\SnowflakeGeneratorSettings;


require dirname(__DIR__) . "/vendor/autoload.php";

function createGenerator(int $nodeId) {
    $settings = SnowflakeGeneratorSettings::newInstance()->setNodeId($nodeId);
    return new SnowflakeGenerator($settings);
}

function println(string $line = "") {
    echo "$line\n";
};

function generateAndPrintNext(SnowflakeGenerator $generator) {
    $rawId = $generator->getNext();
    $id = $generator->wrapInObject($rawId);
    println("Generated new id:");
    println(" raw: $id");
    println(" time: ".$id->getTime());
    println(" nodeId: ".$id->getNodeId());
    println(" sequenceNumber: ".$id->getSequenceNumber());
    println();
}

$generator1 = createGenerator(0);
$generator2 = createGenerator(1);

generateAndPrintNext($generator1);
generateAndPrintNext($generator2);

generateAndPrintNext($generator2);
generateAndPrintNext($generator1);

