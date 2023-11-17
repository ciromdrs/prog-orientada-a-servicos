<?php

require 'vendor/autoload.php';
require 'cliente_microblog.php';
require 'interface.php';


/* Variáveis */
$api = new ClienteMicroblog('http://localhost:8000/api/');
$interface = new InterfaceMicroblog();

/* Programa principal */
do {
    $interface->exibirTitulo();

    $publicacoes = $api->GET_publicacoes();
    $interface->exibirPublicacoes($publicacoes);

    $opcao = $interface->menuOperacoes();
} while ($opcao != OP_CANCELAR);

$interface->tchau();