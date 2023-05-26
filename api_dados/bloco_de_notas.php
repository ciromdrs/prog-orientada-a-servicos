<?php


// Verificação do comando
if (sizeof($argv) != 2) {
    echo "Uso: php {$argv[0]} <endereco-servico-dados>\n";
    exit(1);
}



// CONSTANTES E VARIÁVEIS GLOBAIS


// Opções de menus
const OPCAO_SAIR = 0;
const OPCAO_CRIAR = 1;
const OPCAO_APAGAR = 2;

// URL do serviço de dados
$url_servico_dados = $argv[1];



// INICIALIZAÇÃO


// Obtém as URLs do serviço de dados
$urls = req_GET_urls($url_servico_dados);
if ($urls == NULL) {
    echo "Erro obtendo URLs.\n";
    exit(1);
}


// Verifica se o balde 'notas' existe, usando uma requisição HEAD
$resp = req_HEAD_balde_notas();
if ($resp['codigo'] == 404) {
    # Balde das notas não existe. É preciso criar.
    $resp = req_PUT_balde_notas();

    // Se não deu certo criar o balde, aborta
    if ($resp['codigo'] != 201) {
        echo "Erro ao criar balde de notas.\n";
        echo "Erro {$resp['codigo']}: {$resp['corpo']}\n";
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



// MENUS


function menu_principal() {
    global $urls;

    // Acessa as notas cadastradas
    $notas = req_GET_notas();

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
    // Recebe o texto da nota
    echo "Digite o texto da nota:\n";
    $texto = readline();

    // Envia a requisição ao serviço de dados
    $resp = req_POST_nota($texto);
     // TODO: Exibir possível mensagem de erro
}


function menu_apagar() {
    // Recebe a chave da nota
    echo "Digite o número da nota que deseja apagar: ";
    $chave = readline();

    // Envia a requisição DELETE ao serviço de dados
    $resp = req_DELETE_nota($chave);
    // TODO: Exibir possível mensagem de erro
}



// REQUISIÇÕES

/**
 * Função genérica para enviar requisições usando a extensão cURL para PHP.
 */
function enviar_requisicao($url, $curl_options = []) {
    // Cria array para guardar as informações da resposta
    $resposta = [];

    // Inicializa o canal de comunicação
    $ch = curl_init($url);
    // Esta opção configura o cURL para retornar o valor da resposta, em vez de
    // apenas exibí-lo na tela.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Atribui as demais opções passadas como parâmetro.
    foreach($curl_options as [$opt, $val]){
        curl_setopt($ch, $opt, $val);
    }

    // Envia a requisição HTTP e retorna o corpo da resposta HTTP
    $resposta['corpo'] = curl_exec($ch);
    // Extrai os cabecalhos da resposta HTTP
    $resposta['cabecalhos'] = 'Não implementado';
    // Acessa informações sobre a resposta
    $resposta['info'] = curl_getinfo($ch);
    // Extrai o código de estado HTTP
    $resposta['codigo'] = $resposta['info']['http_code'];
    // Acessa possível erro na requisição
    $resposta['erro'] = curl_error($ch);
    // Fecha o canal de comunicação
    curl_close($ch);

    // Retorna a resposta e as informações
    return $resposta;
}


function req_GET_notas() {
    global $urls;
    $resp = enviar_requisicao(
        _url('balde', [['{balde}', 'notas']])
    );
    // Se não deu certo criar o balde, aborta
    if ($resp['codigo'] != 200) {
        echo "Erro obtendo notas.\n";
        echo "Erro {$resp['codigo']}: {$resp['corpo']}";
        exit(1);
    }
    $notas = json_decode($resp['corpo']);
    return $notas;
}


function req_GET_urls($url_servico) {
    $resp = enviar_requisicao($url_servico);
    $urls = json_decode($resp['corpo'],
                        $associative = true, // Retorna um array em vez de um
                                             // objeto padrão.
                        flags : JSON_THROW_ON_ERROR); // Lança exceções em
                                                      // caso de erro.
    return $urls;
}


function req_DELETE_nota($chave) {
    return enviar_requisicao(
        _url('objeto', [['{balde}','notas'], ['{chave}', $chave]]),
        [[CURLOPT_CUSTOMREQUEST, 'DELETE']]
    );
}


function req_HEAD_balde_notas() {
    return enviar_requisicao(
        _url('balde', [['{balde}', 'notas']]),
        [[CURLOPT_CUSTOMREQUEST, 'HEAD']]
    );
}


function req_PUT_balde_notas() {
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


function req_POST_nota($texto) {
    return enviar_requisicao(
        _url('balde', [['{balde}', 'notas']]),
        [
            [CURLOPT_CUSTOMREQUEST, 'POST'],
            [CURLOPT_HTTPHEADER, array('Content-Type: application/json')],
            [CURLOPT_POSTFIELDS, json_encode([
                    'usuario' => 'bloco_de_notas',
                    'valor' => $texto
                ])
            ]
        ]
    );
}



// FUNÇÕES AUXILIARES


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
