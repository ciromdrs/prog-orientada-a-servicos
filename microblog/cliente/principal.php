<?php

require 'vendor/autoload.php';
require 'cliente_microblog.php';



/* CONSTANTES */

const OP_SAIR = 'Sair';
const OP_CANCELAR = 'Cancelar';
const OP_ESCREVER = 'Escrever publicação';
const OP_EXCLUIR = 'Excluir publicação';

const OP_INVALIDA = 'Operação inválida';



/* CLASSES */

/**
 * Interface CLI para o Microblog.
 */
class InterfaceMicroblog {

    /**
     * @param ClienteMicroblog cliente_microblog - Cliente da API do Microblog.
     * @param string temp_msg - Uma mensagem temporária a ser exibida uma vez.
     */
    public function __construct(
        private ClienteMicroblog $cliente_microblog,
        private string $temp_msg = ''
    ) {}


    /**
     * Exibe o menu principal em loop.
     */
    public function menuPrincipal() {
        do {
            $this->limparTela();

            $this->exibirTitulo();
        
            $publicacoes = $this->cliente_microblog->getPublicacoes();
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
                    $this->cliente_microblog->criarPublicacao($p);
                    break;
                case OP_EXCLUIR:
                    $id = $this->menuExcluirPublicacao();
                    $resposta = $this->cliente_microblog->exluirPublicacao($id);
                    $this->exibirErroNaResposta($resposta);
                    break;
            }
        } while ($operacao != OP_SAIR);
        
        $this->tchau();
    }


    /**
     * Limpa a tela do terminal.
     */
    private function limparTela() {
        echo "\033c";
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


    /**
     * Exibe a lista de publicações.
     * 
     * @param array publicacoes - lista de publicações.
     */
    public function exibirPublicacoes($publicacoes) {
        foreach ($publicacoes as $p) {
            echo "
            \r#$p->id $p->autor em $p->created_at escreveu:
            \r\"$p->texto\"
            \r";
        }
    }
    
    
    /**
     * Exibe a lista de operações disponíveis e retorna a que o usuário
     * escolher.
     */
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


    /**
     * Exibe menu para ler os dados de uma publicação a ser criada.
     */
    public function menuEscreverPublicacao() {
        $p = [];
        $p['autor'] = readline('Escreva seu nome: ');
        echo "Escreva o texto da publicação:\n";
        $p['texto'] = readline();
        return $p;
    }


    /**
     * Exibe menu para ler o id de uma publicação a ser excluída.
     */
    public function menuExcluirPublicacao() {
        $id = readline('Digite o # da publicação que você deseja excluir: ');
        return $id;
    }


    /**
     * Exibe uma mensagem temporária, que é apagada em seguida.
     */
    public function exibirMensagemTemporaria() {
        if ($this->temp_msg != '') {
            echo "\n$this->temp_msg\n";
            $this->temp_msg = '';
        }
    }


    /**
     * Exibe uma possível a mensagem de reposta de erro.
     * Se não houver erro, nada é exibido.
     */
    public function exibirErroNaResposta($resposta) {
        if ($resposta->getStatusCode() != 200) {
            $msg = json_decode($resposta->getBody());
            $this->temp_msg = "[$msg->tipo] $msg->conteudo";
        }
    }


    /**
     * Exibe uma mensagem de despedida.
     */
    public function tchau() {
        echo "\nObrigado por usar o Microblog :)\n\n";
    }
}



/* PROGRAMA PRINCIPAL */


$cliente_microblog = new ClienteMicroblog('http://localhost:8000/api/');
$interface = new InterfaceMicroblog($cliente_microblog);
$interface->menuPrincipal();
