<?php

$url_api = 'localhost:8000/api';

/**
 * Função genérica para enviar requisições usando a extensão cURL para PHP.
 */
function enviar_requisicao($url, $metodo = 'GET', $corpo = '',
    $cabecalhos = [], $curl_options = []) {
    // TODO: implemente esta função utilizando Guzzle.

    // TODO: Crie um array para guardar as informações da resposta
    $resposta = [];
    $resposta['codigo'] = ??
    $resposta['cabecalhos'] = ??
    $resposta['corpo'] = ??
    $resposta['erro'] = ""; // Deixe isto em branco

    // Retorna a resposta
    return $resposta;
}
