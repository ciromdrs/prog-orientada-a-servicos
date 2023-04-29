<?php
// Estrutura if, else-if, else em PHP
$idade = intval($argv[1]);

if ($idade <= 17) {
    var_dump("Menor");
} else if ($idade <= 59) {
    var_dump("Adulto");
} else {
    var_dump("Idoso");
}
