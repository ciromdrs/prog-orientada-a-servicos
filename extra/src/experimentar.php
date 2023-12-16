<?php

/**
 * Conjunto de experimentos para verificar consumo de tempo de operações de 
 * uma estrutura de dados.
 */


# É necessário passar a classe da estrutura de dados.
if ($argc != 2) {
    exit("Uso: php $argv[0] <EstruturaDeDados>\n");
}
$classe = $argv[1];


# Importa o arquivo da classe
require "$classe.php";


# Prepara os parâmetros dos experimentos
$incremento = 100000;
$ate = $incremento * 10;

# Executa os experimentos
#exp_inserir($classe, 0, $ate, $incremento, 10);
exp_buscar($classe, 0, $ate, $incremento, 10);


/**
 * Experimento de inserção.
 */
function exp_inserir($classe, int $de, int $ate, int $incremento, int $repeticoes)
{
    # Identificação do experimento
    echo "$classe.inserir\t$de\t$ate\t$incremento\t$repeticoes\n";

    # Incrementa o tamanho de $de até $ate
    for ($tam = $de; $tam <= $ate; $tam += $incremento) {
        # Repete cada experimento $repeticoes vezes
        for ($r = 0; $r < $repeticoes; $r++) {
            # Cria uma nova instância da estrutura que for passada
            $estrutura = new $classe();

            # Pega a hora inicial do experimento
            $inicio = agora();

            # Insere $tam elementos
            for ($i = 0; $i < $tam; $i++) {
                $estrutura->inserir($i+1);
            }

            # Subtrai a hora inicial da final para pegar o tempo decorrido
            $decorrido = agora() - $inicio;

            # Exibe os resultados do experimento
            $tempo = formatar_tempo($decorrido);
            echo "$tam\t$tempo\n";
        }
    }
}


/**
 * Experimento de busca.
 */
function exp_buscar($classe, int $de, int $ate, int $incremento, int $repeticoes)
{
    # Identificação do experimento
    echo "$classe.buscar\t$de\t$ate\t$incremento\t$repeticoes\n";

    # Incrementa o tamanho de $de até $ate
    for ($tam = $de; $tam <= $ate; $tam += $incremento) {
        # Repete cada experimento $repeticoes vezes
        for ($r = 0; $r < $repeticoes; $r++) {
            # Cria um array de valores aleatórios
            $dados = range(0, $tam); # Primeiro cria ordenado
            shuffle($dados); # Depois embaralha

            # Cria uma nova instância da estrutura que for passada fornecendo
            # o array embaralhado
            $estrutura = new $classe($dados);

            # Pega a hora inicial do experimento
            $inicio = agora();

            # Busca um elemento ausente (pior caso)
            $estrutura->inserir($tam + 1);

            # Subtrai a hora inicial da final para pegar o tempo decorrido
            $decorrido = agora() - $inicio;

            # Exibe os resultados do experimento
            $tempo = formatar_tempo($decorrido);
            echo "$tam\t$tempo\n";
        }
    }
}


/**
 * Função auxiliar que pega o tempo atual no formato esperado.
 */
function agora()
{
    return microtime(as_float: true);
}


/**
 * Função auxiliar que formata tempo no formato esperado.
 */
function formatar_tempo($tempo)
{
    return number_format(
        $tempo,
        decimals: 10,
        decimal_separator: ",",
        thousands_separator: ""
    );
}