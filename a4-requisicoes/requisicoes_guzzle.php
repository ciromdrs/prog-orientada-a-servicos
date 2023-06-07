<?php

$url_api = 'localhost:8000/api';

/**
 * Função genérica para enviar requisições usando a extensão cURL para PHP.
 */
function enviar_requisicao($url, $metodo = 'GET', $corpo = '',
    $cabecalhos = []) {
    // TODO: implemente esta função utilizando Guzzle.

    // TODO: Crie um array para guardar as informações da resposta
    $resposta = [];
    $resposta['codigo'] = ??
    $resposta['cabecalhos'] = ??
    $resposta['corpo'] = ??
    $resposta['erro'] = ""; // Deixe o erro em branco

    // Retorna a resposta
    return $resposta;
}
