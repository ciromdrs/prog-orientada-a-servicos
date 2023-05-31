<?php

/**
 * Função genérica para enviar requisições usando a extensão cURL para PHP.
 */
function enviar_requisicao($url, $metodo = 'GET', $corpo = '',
    $curl_options = []) {
    // Cria array para guardar as informações da resposta
    $resposta = [];

    // Inicializa o canal de comunicação
    $ch = curl_init($url);

    // Esta opção configura o cURL para retornar o valor da resposta, em vez de
    // apenas exibí-lo na tela.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Esta opção configura o cURL para preservar os cabeçalhos na resposta, em
    // vez de descartá-los.
    curl_setopt($ch, CURLOPT_HEADER, 1);
    // Esta opção configura o método HTTP. Se ela não for atribuída, o método
    // GET é usado por padrão.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    // Esta opção atribui um corpo à requisição HTTP. Em geral, o corpo é usado
    // por requisições POST, PUT ou PATCH, porém talvez o mais comum seja POST
    // (por isso a opção se chama POSTFIELDS).
    curl_setopt($ch, CURLOPT_POSTFIELDS, $corpo);
    // Atribui as demais opções passadas como parâmetro.
    foreach($curl_options as [$opt, $val]){
        curl_setopt($ch, $opt, $val);
    }

    // Envia a requisição HTTP e retorna o corpo da resposta HTTP
    $str_resp = curl_exec($ch);
    // Acessa informações sobre a resposta
    $info = curl_getinfo($ch);
    // Extrai o código de estado HTTP
    $resposta['codigo'] = $info['http_code'];
    // Extrai os cabecalhos da resposta HTTP
    $resposta['cabecalhos'] = substr($str_resp, 0, $info['header_size']);
    // Extrai o corpo da resposta HTTP
    $resposta['corpo'] = substr($str_resp, $info['header_size']);
    // Extrai possível erro na requisição
    $resposta['erro'] = curl_error($ch);
    // Fecha o canal de comunicação
    curl_close($ch);

    // Retorna a resposta e as informações
    return $resposta;
}
