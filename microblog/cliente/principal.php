<?php

require 'vendor/autoload.php';
require 'cliente_microblog.php';



/* CONSTANTES */

const OP_SAIR = 'Sair';
const OP_CANCELAR = 'Cancelar';
const OP_ESCREVER = 'Escrever publicação';
const OP_EXCLUIR = 'Excluir publicação';

const OP_INVALIDA = 'Operação inválida';

const LIMPA_TELA = "\033c";



/* CLASSES */

/**
 * Interface CLI para o Microblog.
 */
class InterfaceMicroblog {
    public function __construct(
        private ClienteMicroblog $cliente_microblog,
        private string $temp_msg = ''
    ) {
    }


    public function menuPrincipal() {
        do {
            echo LIMPA_TELA;
            $this->exibirTitulo();
        
            $publicacoes = $this->cliente_microblog->GET_publicacoes();
            $this->exibirPublicacoes($publicacoes);
        
            $this->exibirMensagemTemporaria();
            
            echo "\n";
            $operacao = $this->menuOperacoes();
        
            switch ($operacao) {
                case OP_SAIR:
                    # Não faz nada, apenas sai
                    break;
                case OP_ESCREVER:
                    $p = $this->menuEscreverPublicacao();
                    $this->cliente_microblog->POST_publicacoes($p);
                    break;
            }
        } while ($operacao != OP_SAIR);
        
        $this->tchau();
    }

    
    /**
     * Exibe o título da aplicação.
     */
    public function exibirTitulo() {
        echo
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
            1 => OP_ESCREVER,
            2 => OP_EXCLUIR,
            0 => OP_SAIR
        ];
        foreach ($operacoes as $i => $op) {
            echo "[$i] $op\n";
        }

        $escolhida = (int) readline('O que você deseja fazer? ');

        if ($escolhida >= count($operacoes) || $escolhida < 0) {
            $this->temp_msg = 'Operação inválida';
            return OP_INVALIDA;
        }
        
        return $operacoes[$escolhida];
    }


    public function menuEscreverPublicacao() {
        $p = [];
        $p['autor'] = readline('Escreva seu nome: ');
        echo "Escreva o texto da publicação:\n";
        $p['texto'] = readline();
        return $p;
    }


    public function exibirMensagemTemporaria() {
        if ($this->temp_msg != '') {
            echo "\n$this->temp_msg\n";
            $this->temp_msg = '';
        }
    }


    public function tchau() {
        echo "Obrigado por usar o Microblog :)\n";
    }
}



/* PROGRAMA PRINCIPAL */


$cliente_microblog = new ClienteMicroblog('http://localhost:8000/api/');
$interface = new InterfaceMicroblog($cliente_microblog);
$interface->menuPrincipal();
