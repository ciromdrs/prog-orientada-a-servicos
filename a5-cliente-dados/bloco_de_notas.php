<?php


// CONSTANTES

// Opções de menus
const OPCAO_SAIR = '0';
const OPCAO_CRIAR = '1';
const OPCAO_APAGAR = '2';


// FUNÇÕES

// Menus

function menu_principal(string $uri_servico_dados) {
    global $mensagem;

    // Acessa as notas cadastradas
    $notas = req_GET_notas($uri_servico_dados);

    // Exibe a interface
    echo "\n=============== BLOCO DE NOTAS ===============\n";
    if ($notas != NULL) {
        foreach($notas as $n){
            echo "Nota {$n->chave}: \"{$n->valor}\"\n";
        }
    }
    echo "----------------------------------------------\n";
    if (strlen($mensagem) > 0) {
        echo "$mensagem\n";
        echo "----------------------------------------------\n";
    }
    $mensagem = ''; // Apaga a mensagem para a próxima iteração
    echo OPCAO_CRIAR . " - Criar nota\n";
    echo OPCAO_APAGAR . " - Apagar nota\n";
    echo OPCAO_SAIR . " - Sair\n";
    echo "Digite sua opção: ";
    $opcao = readline();
    return $opcao;
}

function menu_criar(string $uri_servico_dados) {
    global $mensagem;

    // Recebe o texto da nota
    echo "Digite o texto da nota:\n";
    $texto = readline();

    // Envia a requisição ao serviço de dados
    $resp = req_POST_nota($texto, $uri_servico_dados);

    // Exibe possível mensagem de erro
    if ($resp['codigo'] != 201) {
        $mensagem = "Erro {$resp['codigo']} ao criar nota.\n" . $resp["corpo"];
    } else {
        var_dump($resp['corpo']);
    }
}

function menu_apagar(string $uri_servico_dados) {
    global $mensagem;

    // Recebe a chave da nota
    echo "Digite o número da nota que deseja apagar: ";
    $chave = readline();

    // Envia a requisição DELETE ao serviço de dados
    $resp = req_DELETE_nota($chave, $uri_servico_dados);

    // Exibe possível mensagem de erro
    if ($resp['codigo'] != 200) {
        $mensagem = "Erro {$resp['codigo']} ao apagar Nota $chave.\n" .
                    $resp["corpo"];
    }
}

// Requisições

/**
 * Função genérica para enviar requisições usando a extensão curl para PHP.
 * 
 * @return array Resposta contendo as chaves 'codigo', 'corpo', 'cabecalhos' e 'erro'.
 */
function enviar_requisicao(string $uri, string $metodo = 'GET', string $corpo = '',
    array $cabecalhos = [], array $curl_options = []): array  {
    // Cria um array para guardar as informações da resposta
    $resposta = [];

    // Inicializa o canal de comunicação
    $ch = curl_init($uri);

    // Esta opção configura o curl para retornar o valor da resposta, em vez de
    // apenas exibí-lo na tela.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    // Esta opção configura o curl para preservar os cabeçalhos na resposta, em
    // vez de descartá-los.
    curl_setopt($ch, CURLOPT_HEADER, 1);
    // Esta opção configura o método HTTP. Se ela não for atribuída, o método
    // GET é usado por padrão.
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $metodo);
    // Esta opção atribui os cabecalhos da requisição.
    curl_setopt($ch, CURLOPT_HTTPHEADER, $cabecalhos);
    // Esta opção atribui um corpo à requisição HTTP. Em geral, o corpo é usado
    // por requisições POST, PUT ou PATCH, porém talvez o mais comum seja POST
    // (por isso a opção se chama POSTFIELDS).
    curl_setopt($ch, CURLOPT_POSTFIELDS, $corpo);
    // Atribui as demais opções passadas como parâmetro.
    curl_setopt_array($ch, $curl_options);

    // Envia a requisição HTTP e retorna o corpo da resposta HTTP
    $str_resp = curl_exec($ch);
    // Acessa informações sobre a resposta
    $info = curl_getinfo($ch);
    // Extrai o código de estado HTTP
    $resposta['codigo'] = $info['http_code'];
    // Extrai os cabecalhos da resposta HTTP (sem a linha do codigo HTTP)
    $str_cabecalhos = substr($str_resp, 0, $info['header_size']);
    $linhas = explode('\n', $str_cabecalhos);
    $resposta['cabecalhos'] = array_slice($linhas, 1);

    // Extrai o corpo da resposta HTTP
    $resposta['corpo'] = substr($str_resp, $info['header_size']);
    // Extrai possível erro na requisição
    $resposta['erro'] = curl_error($ch);
    // Fecha o canal de comunicação
    curl_close($ch);

    // Retorna a resposta
    return $resposta;
}

/**
 * Acessa as notas salvas.
 * 
 * @return array A lista de notas salvas.
 */
function req_GET_notas(string $uri_servico_dados): array {
    $resp = enviar_requisicao("$uri_servico_dados/bancos/notas");
    // Se não deu certo criar o banco, aborta
    if ($resp['codigo'] != 200) {
        echo "Erro obtendo notas.\n";
        echo "Erro {$resp['codigo']}: {$resp['corpo']}";
        exit(1);
    }
    $notas = json_decode($resp['corpo']);
    return $notas;
}


/**
 * Apaga uma nota no banco.
 * 
 * @param chave A chave da nota a apagar.
 * 
 * @return array Resposta da função `enviar_requisicao`.
 */
function req_DELETE_nota(string $chave, string $uri_servico_dados): array {
    return enviar_requisicao("$uri_servico_dados/bancos/notas/$chave",
        metodo: 'DELETE'
    );
}

/**
 * Envia uma requisição HEAD para o URI do banco `'notas'`.
 * 
 * É usada para verificar se o banco existe.
 * 
 * @return array Resposta da função `enviar_requisicao`.
 */
function req_HEAD_banco_notas(string $uri_servico_dados): array {
    return enviar_requisicao(
        "$uri_servico_dados/bancos/notas",
        metodo: 'HEAD'
    );
}

/**
 * Envia uma requisição PUT para criar o banco `'notas'`.
 * 
 * @return array Resposta da função `enviar_requisicao`.
 */
function req_PUT_banco_notas(string $uri_servico_dados): array {
    return enviar_requisicao(
        "$uri_servico_dados/bancos/notas",
        metodo: 'PUT',
        cabecalhos: ['Content-Type: application/json'],
        corpo: json_encode([
            'usuario' => 'bloco_de_notas',
            'nome' => 'notas'
        ])
    );
}

/**
 * Envia uma requisição POST para apagar uma nota.
 * 
 * @param texto O texto da nota.
 * 
 * @return array Resposta da função `enviar_requisicao`.
 */
function req_POST_nota(string $texto, string $uri_servico_dados): array {
    return enviar_requisicao(
        "$uri_servico_dados/bancos/notas",
        metodo: 'POST',
        cabecalhos: ['Content-Type: application/json'],
        corpo: json_encode([
            'usuario' => 'bloco_de_notas',
            'valor' => $texto
        ])
    );
}


// PROGRAMA PRINCIPAL

// Verificação do comando
if (sizeof($argv) != 2) {
    echo "Uso: php {$argv[0]} <endereco-servico-dados>\n";
    exit(1);
}

// Inicialização

// URI do serviço de dados
$uri_servico_dados = $argv[1];
// Mensagem para o usuário
$mensagem = '';

// Verifica se o banco 'notas' existe, usando uma requisição HEAD
$resp = req_HEAD_banco_notas($uri_servico_dados);
if ($resp['codigo'] == 404) {
    # Banco das notas não existe. É preciso criar.
    $resp = req_PUT_banco_notas($uri_servico_dados);

    // Se não deu certo criar o banco, aborta
    if ($resp['codigo'] != 201) {
        echo "Erro ao criar banco de notas.\n";
        echo "Erro {$resp['codigo']}: {$resp['corpo']}\n";
        exit(1);
    }
}

// Laço principal do programa
do {
    $opcao = menu_principal($uri_servico_dados);
    switch ($opcao) {
        case OPCAO_CRIAR:
            menu_criar($uri_servico_dados);
            break;
        case OPCAO_APAGAR:
            menu_apagar($uri_servico_dados);
            break;
    }
}
while($opcao != OPCAO_SAIR);
