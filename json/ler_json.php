<?php
/* Lê arquivos JSON representando-os como arrays e exibe o resultado.
Em caso de erros no arquivo JSON, você pode usar um validador online como
https://jsonlint.com/ para corrigí-los.
*/

if (sizeof($argv) != 2) {
    echo "Uso: php ler_json.php arquivo.json\n";
    exit(1);
}

$conteudo = file_get_contents($argv[1]);
$meu_array = json_decode($conteudo,
                         $associative = true, // Retorna um array em vez de um
                                              // objeto padrão.
                         $depth = 512, // Tenho que passar esse parâmetro pra
                                       // poder passar o próximo parâmetro
                                       // (flags). O valor default já é 512,
                                       // então apenas repeti.
                                       // Se você souber outra forma de fazer
                                       // isso, me avise :)
                         $flags = JSON_THROW_ON_ERROR); // Lança exceções em
                                                        // caso de erro.
var_dump($meu_array); // var_dump exibe qualquer variável.
