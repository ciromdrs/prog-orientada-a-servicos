<?php

require 'vendor/autoload.php';
require 'cliente_microblog.php';



/* CONSTANTES */

const OP_SAIR = 'Sair';
const OP_CANCELAR = 'Cancelar';
const OP_ESCREVER = 'Escrever publicação';
const OP_EXCLUIR = 'Excluir publicação';
const OP_EXIBIR_USUARIO = 'Exibir meus dados pessoais';

const OP_INVALIDA = 'Operação inválida';



/* CLASSES */

/**
 * Interface CLI para o Microblog.
 */
class InterfaceMicroblog {
    private array $usuario = [];

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
        $this->menuLogin();

        do {
            $this->limparTela();

            $this->exibirTitulo();

            $this->exibirUsuario();
        
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
                    $resposta = $this->cliente_microblog->criarPublicacao($p);
                    $this->exibirErroNaResposta($resposta);
                    break;
                case OP_EXCLUIR:
                    $id = $this->menuExcluirPublicacao();
                    $resposta = $this->cliente_microblog->exluirPublicacao($id);
                    $this->exibirErroNaResposta($resposta);
                    break;
                case OP_EXIBIR_USUARIO:
                    $this->menuExibirUsuario();
                    break;
            }
        } while ($operacao != OP_SAIR);
        
        $this->tchau();
    }


    /**
     * Exibe o menu de login.
     */
    private function menuLogin() {
        $this->limparTela();
        
        $this->exibirTitulo();

        echo "Faça login para começar.\n\n";

        echo 'Matrícula: ';
        $usuario = readline();

        echo 'Senha: ';
        $senha = Seld\CliPrompt\CliPrompt::hiddenPrompt();

        $this->usuario = $this->cliente_microblog->login($usuario, $senha);
    }


    /**
     * Exibe os dados do usuário logado.
     */
    private function exibirUsuario() {
        echo "{$this->usuario['nome']} ({$this->usuario['matricula']})\n";
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
        \r";
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
            \r\"{$this->alinhar($p->texto, 80)}\"
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
            9 => OP_EXIBIR_USUARIO,
            0 => OP_SAIR
        ];
        foreach ($operacoes as $i => $op) {
            echo "[$i] $op\n";
        }

        $escolhida = (int) readline('O que você deseja fazer? ');

        if (!array_key_exists($escolhida, $operacoes)) {
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
        $p['autor'] = $this->usuario['nome'];
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
     * 
     */
    public function menuExibirUsuario() {
        echo "Nome: {$this->usuario['nome']}\n";
        echo "Matrícula: {$this->usuario['matricula']}\n";
        echo "Token SUAP: {$this->usuario['suap_token']}\n";
        readline("Aperte ENTER para voltar");
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


    /**
     * Quebra o $texto em várias linhas para caber na $largura.
     * 
     * @param string $texto 
     */
    public function alinhar($texto, $largura): string {
        if ($largura <= 0)
            throw new InvalidArgumentException(
                'A largura deve ser maior que 0.'
            );

        $alinhado = ''; # Array contendo o texto alinhado

        $ini = 0; # Início da próxima linha (começa em 0 na primeira linha)
        # Repete este laço até consumir todo o $texto
        do {
            # Pega no $texto uma linha de tamanho $largura começando em $ini
            $l = substr($texto, $ini, $largura);
            # Adiciona a linha ao texto alinhado junto com uma quebra de linha
            # TODO: Não adicionar quebra depois da última linha.
            $alinhado .= $l . "\n";
            # Incrementa $ini para o início da próxima linha
            $ini += $largura;
        } while ($ini < strlen($texto));

        return $alinhado;
    }
}



/* PROGRAMA PRINCIPAL */


$cliente_microblog = new ClienteMicroblog(
    'http://localhost:8000/api/',
    'https://suap.ifrn.edu.br/api/v2/'
);
$interface = new InterfaceMicroblog($cliente_microblog);
$interface->menuPrincipal();
