<?php
/* Lê arquivos XML representando-os como arrays e exibe o resultado.
Em caso de erros no arquivo XML, você pode usar um validador online como
https://www.xmlvalidation.com/ para corrigí-los.
*/

// Verifica os argumentos.
if (sizeof($argv) != 2) {
    echo "Uso: php ler_xml.php arquivo.xml\n";
    exit(1);
}

// Lê o conteúdo do arquivo XML.
$conteudo = file_get_contents($argv[1]);
// Transforma num objeto SimpleXMLElement, que representa um documento XML.
$obj_xml = new SimpleXMLElement($conteudo);

var_dump($obj_xml); // var_dump exibe qualquer variável.
