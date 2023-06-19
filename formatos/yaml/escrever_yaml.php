<?php
/* Lê arquivos YAML representando-os como arrays e exibe o resultado.
Em caso de erros no arquivo YAML, você pode usar um validador online como
https://www.yamllint.com/ para corrigí-los.
*/

require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

$p = [
    'pessoa' => [
        'id' => 123,
        'nome' => 'Alice',
    ]
];

$resultado = Yaml::dump($p);

echo($resultado);
