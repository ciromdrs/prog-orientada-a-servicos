<?php
/* Lê arquivos YAML representando-os como arrays e exibe o resultado.
Em caso de erros no arquivo YAML, você pode usar um validador online como
https://www.yamllint.com/ para corrigí-los.
*/

require 'vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

if (sizeof($argv) != 2) {
    echo "Uso: php ler_yaml.php arquivo.yaml\n";
    exit(1);
}

$conteudo = file_get_contents($argv[1]);
$meu_array =  Yaml::parse($conteudo);
var_dump($meu_array); // var_dump exibe qualquer variável.
