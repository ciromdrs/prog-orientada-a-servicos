<?php
/* Problema proposto na aula de 04/04.

Escreva um script (PHP ou na linguagem de sua preferência) que envie uma
requisição HTTP para uma URL e interprete a resposta como JSON.

Passos do programa (tudo isto pode ser feito em PHP):
1 - Enviar uma requisição HTTP para a URL https://www.codever.dev/api/version.
    Esta URL vai nos retornar a versão da API do web service do Codever e um
    código SHA de verificação (ignorem o significado deste código, apenas exibam
    ele).
2 - Obter o conteúdo da resposta (uma string contendo dados no formato JSON).
3 - Converter o conteúdo da mensagem e representá-lo na memória (em PHP, use
    json_decode()).
4 - Exibir o resultado (em PHP, seria apenas exibir os campos version e gitSha1
    do objeto convertido).

Resolução abaixo.
*/

/* A função fopen cria um stream para receber os dados do servidor. Streams são
recursos que podem ser lidos ou escritos de maneira sequencial, como arquivos,
mensagens de rede (HTTP, por exemplo), e outros.
A opção "r" indica que estamos apenas lendo o stream (recebendo dados do
servidor), não vamos escrever (enviar dados ao servidor) nada.
Esta forma de trabalhar com requisições HTTP é muito simples e limitada.
Veremos outras formas ligeiramente mais complexas, porém muito mais poderosas.
*/
$url = "https://www.codever.dev/api/version";
$fp = fopen($url, 'r');

/* Uma vez que criamos o stream, podemos receber o conteúdo dele usando a função
stream_get_contents, que recebe um stream e retorna uma string correspondente ao
conteúdo desse stream.
*/
$str = stream_get_contents($fp);

/* A URL que usamos neste exemplo retorna uma string contendo a versão da API e
um código de verificação. Esses dados estão codificados no formato JSON. Para
acessarmos facilmente os valores (e também a estrutura destes dados), usamos a
função json_decode do PHP.
Essa função recebe uma string codificada no formato JSON e devolve um objeto PHP
contendo atributos correspondentes aos valores do json. */
$meu_obj = json_decode($str);

// Agora podemos usar esse objeto normalmente.
echo "Dados recebidos de " . $url . "\n";
echo "Versão da API: " . $meu_obj->version . "\n";
echo "SHA: " . $meu_obj->gitSha1 . "\n";
