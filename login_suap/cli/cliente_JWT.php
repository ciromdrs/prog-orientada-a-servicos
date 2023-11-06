<?php

require 'credenciais.php';
require 'vendor/autoload.php';


use GuzzleHttp\Client;



# VARIÁVEIS E CONSTANTES


const URL_SUAP_API = 'https://suap.ifrn.edu.br/api/v2';

$cliente_http = new Client(['cookies' => true]);

# PROGRAMA PRINCIPAL


$token = login_SUAP($usuario, $senha, $cliente_http);

$dados = acessar_dados($token, $cliente_http);
echo "Usuário Logado
--------------
Nome: {$dados['nome_usual']}
Matrícula: {$dados['matricula']}
Vínculo: {$dados['tipo_vinculo']}
";



# FUNÇÕES


function login_SUAP($usuario, $senha, $cliente_http): string {
    // Prepara os dados da requisição
    $url = URL_SUAP_API . '/autenticacao/token/';
    $params = [
        'form_params' => [
            'username' => $usuario,
            'password' => $senha
        ]
    ];
    // Envia a requisição usando o cliente Guzzle
    $res = $cliente_http->post($url, $params);
    // Decodifica os dados da resposta JSON
    $dados = json_decode($res->getBody());
    // Pega o token de acesso
    $token = $dados->access;

    return $token;
}


function acessar_dados($token, $cliente_http): array {
    $res = $cliente_http->get(
        URL_SUAP_API . '/minhas-informacoes/meus-dados/',
        [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]
    );
    $dados = json_decode($res->getBody(), associative: true);
    return $dados;
}