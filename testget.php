<?php

$time = strtotime("2018-10-08 00:00:00");//(int)(microtime(true)*1000);
$time *= 1000;
$machineId = 1;
$seq = 0;
$id = $time << (64-42);
$id |= $machineId << (64-42-10);
$id |= $seq;

echo "$time\n";
echo "id: $id\n";
$bin = decbin($id);
echo "$bin\n";
$timePart = substr($bin, 0, 41);
echo "$timePart\n";

echo (bindec($timePart))."\n";
