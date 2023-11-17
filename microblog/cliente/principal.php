<?php

require 'vendor/autoload.php';
require 'cliente_microblog.php';


/* Constantes */
const LIMPA_TELA = "\033c"; # Imprimir esse caractere com echo limpa a tela
const OPCAO_SAIR = 0;

/* Variáveis */
$api = new ClienteMicroblog('http://localhost:8000/api/');


/* Programa principal */
do {
    echo LIMPA_TELA . 
    "\r---------------------------------------------------------------------
    \r                            MICROBLOG
    \r---------------------------------------------------------------------
    ";

    $publicacoes = $api->GET_publicacoes();
    exibir_publicacoes($publicacoes);

    $opcao = menu_operacoes();
} while ($opcao != OPCAO_SAIR);


/* Funções */
function exibir_publicacoes($publicacoes) {
    foreach ($publicacoes as $p) {
        echo "
        \r$p->autor em $p->created_at escreveu:
        \r\"$p->texto\"
        \r";
    }
}


function menu_operacoes() {
    echo "Operações:\n";
    $operacoes = [
        1 => 'Escrever publicação',
        2 => 'Excluir publicação',
        0 => 'Sair'
    ];
    foreach ($operacoes as $i => $op) {
        echo "[$i] $op\n";
    }
    return readline('O que você deseja fazer? ');
}