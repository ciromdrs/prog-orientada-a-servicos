<?php
// Laço for em PHP concatenando uma string.
$n = intval($argv[1]);
$saida = '';
for ($i=1; $i<=$n; $i++) {
    $saida .= "$i ";
}
var_dump($saida);
