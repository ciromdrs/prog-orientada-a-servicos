<?php
$fp = fopen("https://www.codever.dev/api/version", 'r');
$str = stream_get_contents($fp);
$meu_obj = json_decode($str);
echo $meu_obj->version;
echo "\n";
echo $meu_obj->gitSha1;
echo "\n";

