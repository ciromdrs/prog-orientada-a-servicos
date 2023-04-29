<?php
// Perceba que os valores em $argv são strings. Precisamos convertê-los.
$a = floatval($argv[1]);
$b = floatval($argv[2]);
$c = floatval($argv[3]);
$media = ($a + $b + $c) / 3;
var_dump($media);
