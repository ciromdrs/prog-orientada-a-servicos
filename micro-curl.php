/* Problema proposto na aula de 04/04.

Escreva um script PHP (ou na linguagem de sua preferência) que envie uma
requisição HTTP para uma URL e interprete a resposta como JSON.
Passos do programa (tudo isto pode ser feito em PHP):
1 - Receber a URL como parâmetro (pode começar usando a URL
https://www.codever.dev/api/version apenas).
2 - Gerar uma requisição HTTP para a URL recebida.
3 - Obter o conteúdo da mensagem.
4 - Interpretar o conteúdo da mensagem (em PHP, use json_decode())
5 - Exibir o resultado (em PHP, seria apenas exibir o array de resposta).

Resolução abaixo.
TODO: receber URL como parâmetro do script.
*/

<?php
$fp = fopen("https://www.codever.dev/api/version", 'r');
$str = stream_get_contents($fp);
$meu_obj = json_decode($str);
echo $meu_obj->version;
echo "\n";
echo $meu_obj->gitSha1;
echo "\n";
