<?php
// Opções de menus
const OPCAO_SAIR = 0;
const OPCAO_CRIAR = 1;
const OPCAO_APAGAR = 2;


// Inicialização
$notas = [];


// Laço principal
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
function menu_principal(): int {
    global $notas;

    echo "\n--------------- BLOCO DE NOTAS ---------------\n";
    for($i = 0; $i < count($notas);  $i++){
        echo "Nota {$i}: \"{$notas[$i]}\"\n";
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
    global $notas;

    // Recebe o texto da nota
    echo "Digite o texto da nota abaixo:\n";
    $texto = readline();
    $notas[] = $texto;
}


function menu_apagar() {
    global $notas;

    // Recebe o índice da nota
    echo "Digite o número da nota que deseja apagar: ";
    $i = (int)readline();
    array_splice($notas, $i, 1);
}
