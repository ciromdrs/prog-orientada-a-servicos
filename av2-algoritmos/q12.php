<?php
// A função explode quebra uma string usando um caractere separador.
$nome_completo = $argv[1];
$quebrado = explode(' ', $nome_completo);
var_dump($quebrado);
