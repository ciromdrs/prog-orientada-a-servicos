<?php


// Verificação do comando
if (sizeof($argv) != 2) {
    echo "Uso: php {$argv[0]} <endereco-servico-dados>\n";
    exit(1);
}



// Constantes e Variáveis Globais


// Opções de menus
const OPCAO_SAIR = 0;
const OPCAO_CRIAR = 1;
const OPCAO_APAGAR = 2;

// URL do serviço de dados
$url_servico_dados = $argv[1];

// Próxima chave de notas
$prox_chave = 0;



// Inicialização


// Obtém as URLs do serviço de dados
$urls = req_get_urls($url_servico_dados);
if ($urls == NULL) {
    echo "Erro obtendo URLs.\n";
    exit(1);
}


// Verifica se o balde 'notas' existe, usando uma requisição HEAD
[$resp, $info] = req_head_balde_notas();
if ($info['http_code'] == 404) {
    # Balde das notas não existe. É preciso criar.
    [$resp, $info] = req_put_balde_notas();

    // Se não deu certo criar o balde, aborta
    if ($info['http_code'] != 201) {
        echo "Erro ao criar balde de notas.\n";
        echo "Erro {$info['http_code']}: $resp";
        exit(1);
    }
}


// Laço principal do programa
do {
    $opcao = menu_principal();
    switch ($opcao) {
        case OPCAO_CRIAR:
            menu_criar();
            break;
        case OPCAO_APAGAR:
            menu_apagar();
            break;
    }
}
while($opcao != OPCAO_SAIR);



// Menus


function menu_principal() {
    global $urls, $prox_chave;

    // Acessa as notas cadastradas
    $notas = req_get_notas();

    // Encontra a próxima chave de notas
    foreach ($notas as $nota)
        if ($nota->chave >= $prox_chave)
            $prox_chave = $nota->chave + 1;

    // Exibe a interface
    echo "\n--------------- BLOCO DE NOTAS ---------------\n";
    if ($notas != NULL) {
        foreach($notas as $n){
            echo "Nota {$n->chave}: \"{$n->valor}\"\n";
        }
    }
    echo "----------------------------------------------\n";
    echo OPCAO_CRIAR . " - Criar nota\n";
    echo OPCAO_APAGAR . " - Apagar nota\n";
    echo OPCAO_SAIR . " - Sair\n";
    echo "Digite sua opção: ";
    $opcao = (int)readline();
    return $opcao;
}


function menu_criar() {
    global $prox_chave;

    // Recebe o texto da nota
    echo "Digite o texto da nota:\n";
    $texto = readline();

    $chave = $prox_chave; // Pega a chave da próxima nota
    $prox_chave++; // Incrementa o contador de notas

    // Envia a requisição PUT ao serviço de dados
    [$_, $_] = req_put_nota($chave, $texto);
}


function menu_apagar() {
    // Recebe a chave da nota
    echo "Digite o número da nota que deseja apagar: ";
    $chave = readline();

    // Envia a requisição DELETE ao serviço de dados
    [$resp, $_] = req_delete_nota($chave);
}



// Requisições


function enviar_requisicao($url, $curl_options = []) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    foreach($curl_options as [$opt, $val]){
        curl_setopt($ch, $opt, $val);
    }
    $resposta = curl_exec($ch);
    $info = curl_getinfo($ch);
    $erro = curl_error($ch);
    if ($erro != '') {
        echo "Erro do cURL: $erro\n";
        var_dump($info['http_code'], $resposta);
    }

    curl_close($ch);
    return [$resposta, $info];
}


function req_get_notas() {
    global $urls;
    [$resp, $_] = enviar_requisicao(_url('balde', [['{balde}', 'notas']]));
    $notas = json_decode($resp);
    return $notas;
}


function req_get_urls($url_servico) {
    [$resp, $info] = enviar_requisicao($url_servico);
    $urls = json_decode($resp,
                        $associative = true, // Retorna um array em vez de um
                                             // objeto padrão.
                        flags : JSON_THROW_ON_ERROR); // Lança exceções em
                                                      // caso de erro.
    return $urls;
}


function req_delete_nota($chave) {
    return enviar_requisicao(
        _url('objeto', [['{balde}','notas'], ['{chave}', $chave]]),
        [[CURLOPT_CUSTOMREQUEST, 'DELETE']]
    );
}


function req_head_balde_notas() {
    return enviar_requisicao(
        _url('balde', [['{balde}', 'notas']]),
        [[CURLOPT_CUSTOMREQUEST, 'HEAD']]
    );
}


function req_put_balde_notas() {
    return enviar_requisicao(
        _url('balde', [['{balde}', 'notas']]),
        [
            [CURLOPT_CUSTOMREQUEST, 'PUT'],
            [CURLOPT_HTTPHEADER, array('Content-Type: application/json')],
            [CURLOPT_POSTFIELDS, json_encode([
                    'usuario' => 'bloco_de_notas',
                    'nome' => 'notas'
                ])
            ],
        ]
    );
}


function req_put_nota($chave, $texto) {
    return enviar_requisicao(
        _url(
            'objeto',
            [['{balde}', 'notas'], ['{chave}', $chave]]
        ),
        [
            [CURLOPT_CUSTOMREQUEST, 'PUT'],
            [CURLOPT_HTTPHEADER, array('Content-Type: application/json')],
            [CURLOPT_POSTFIELDS, json_encode([
                    'usuario' => 'bloco_de_notas',
                    'valor' => $texto
                ])
            ]
        ]
    );
}



// Funções auxiliares


/**
 * Retorna um URL após aplicar as substituições dadas.
 */
function _url(string $url_key, array $substituicoes = []) {
    global $url_servico_dados, $urls;
    $url = $url_servico_dados;
    $url .= $urls[$url_key];
    foreach ($substituicoes as [$var, $val]) {
        $url = str_replace($var, $val, $url);
    }
    return $url;
}
