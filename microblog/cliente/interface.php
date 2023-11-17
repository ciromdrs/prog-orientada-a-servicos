<?php
/**
 * Biblioteca de funções para fazer interface com o usuário.
 */

require 'vendor/autoload.php';

/* Constantes */
const OP_CANCELAR = 0;
const LIMPA_TELA = "\033c";


class InterfaceMicroblog {
    public function __construct() {
        $this->temp_msg = '';
    }

    
    /**
     * Exibe o título da aplicação.
     */
    public function exibirTitulo() {
        echo LIMPA_TELA . 
        "\r---------------------------------------------------------------------
        \r                            MICROBLOG
        \r---------------------------------------------------------------------
        ";
    }


    public function exibirPublicacoes($publicacoes) {
        foreach ($publicacoes as $p) {
            echo "
            \r$p->autor em $p->created_at escreveu:
            \r\"$p->texto\"
            \r";
        }
    }
    
    
    public function menuOperacoes(): string {
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

    public function tchau() {
        echo "Obrigado por usar o Microblog :)\n";
    }
}

