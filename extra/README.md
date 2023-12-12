# Introdução a Estruturas de Dados
Tópico extra apresentado na disciplina de Programação Orientada a Serviços.

## Referências
CORMEN, T. et. al. 2009. Introduction to Algorithms. 3.ed. McGraw-Hill.

## Cronograma
Semana 1 – Vetores ou Arrays (ordenados ou não); Como Medir a Eficiência de Algoritmos (Iterativos); Recursividade
Tarefa pra casa: Criar uma classe Vetor(Ordenado ou NaoOrdenado) e implementar os métodos inserir(valor), excluir(index) e buscar(valor).

Semana 2 – Árvores, Árvores AVL
Tarefa pra casa: Criar uma classe Arvore(Comum ou AVL) e implementar os métodos inserir(valor), excluir(index) e buscar(valor).

Semana 3 – Tabelas Hash
Tarefa pra casa: Criar uma classe TabelaHash e implementar os métodos inserir(valor), excluir(index) e buscar(valor), incluindo o método rehash() próprio da estrutura.

Semana 4 – Encerramento

# Conteúdo
## Vetores
Vetores, também conhecidos como arranjos (arrays), são estruturas de dados que armazenam os elementos em posições sequenciais de memória.
Cada elemento tem um índice associado a ele.

índice   | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 |
|-|-|-|-|-|-|-|-|-|
elemento | 14 | 2 | 11 | 8 | 55 | 7 | 9 | 0 |

### Operações
#### Acessar Elemento - O(1)
Dado um índice `i`, o acesso ao elemento correspondente ocorre em tempo constante, ou seja O(1).
Para saber em qual região de memória está o elemento de índice `i`, basta multiplicar `i * s`, onde `s` é o tamanho de cada elemento.

Ex.: Assumindo que um inteiro é representado com 4 bytes de memória (32 bits), e que a variável `vetor` _aponta para_ o endereço de memória `100`, `vetor[3]` corresponde a:

```
elemento = posicao do vetor + i * tamanho de um int
         = 100 + 3 * 4
         = 112
```

Considerando uma máquina moderna comum, que possui memória RAM de acesso instantâneo a qualquer posição, basta ler o valor da posição `112` da memória e obteremos o valor `55`.

#### Buscar elemento - O(n)
A busca em vetores é feita sequencialmente.
Verifica-se primeiro o elemento de índice `0`, depois o de índice `1, 2, 3, ...`.
Isso resulta em uma complexidade de busca linear com relação ao tamanho do vetor, ou seja O(n).

#### Inserir elemento - O(n)
Por ser uma estrutura que armazena elementos na memória de maneira sequencial, a inserção em vetores pode ser custosa, dependendo do caso.

##### Caso 1
Dependendo da quantidade de memória alocada para o vetor, pode haver espaço para inserir mais um elemento.
Nesse caso, a inserção é instantânea.

Ex (considere que os `x` representam espaços vazios no vetor): dado o vetor abaixo, se quisermos inserir o elemento `7`, basta acessar instantaneamente a primeira posição vazia (`5`) e escrever `7` lá.

índice   | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 |
|-|-|-|-|-|-|-|-|-|
elemento | 14 | 2 | 11 | 8 | 55 | x | x | x |

se torna

índice   | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 |
|-|-|-|-|-|-|-|-|-|
elemento | 14 | 2 | 11 | 8 | 55 | **7** | x | x |

##### Caso 2
Porém, se não houver mais posições vazias, é necessário alocar espaço na memória para um vetor maior, copiar todos os elementos do vetor antigo e inserir o novo elemento lá.

Ex: dado o vetor abaixo, se quisermos inserir o elemento `28`, teremos:

Vetor original:
índice   | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 |
|-|-|-|-|-|-|-|-|-|
elemento | 14 | 2 | 11 | 8 | 55 | 7 | 9 | 0 |


Novo vetor vazio:
índice   | 0 | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 10 | 11 | 12 | 13 | 14 | 15 |
|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|
elemento | x | x | x | x | x | x | x | x | x | x | x | x | x | x | x | x |

Novo vetor com valores do antigo e adicionado o novo elemento.
índice   | 0  | 1 | 2  | 3 | 4  | 5 | 6 | 7 | 8  | 9 | 10 | 11 | 12 | 13 | 14 | 15 |
|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|-|
elemento | 14 | 2 | 11 | 8 | 55 | 7 | 9 | 0 | 28 | x  | x | x  | x  | x  | x  | x  |

Depois disso, o vetor original pode ser apagado.

Na prática, quando precisamos aumentar o tamanho de um vetor, dobramos o seu tamanho.
Isto implica realizarmos pelo menos `2*n` operações, onde `n` é o tamanho do vetor.
Usar um número menor que esse implica ter que realocar memória com mais frequência.
Usar um número maior que esse implica ter um vetor que cresce muito rápido.
Ambos os casos podem ser necessários, mas dobrar o tamanho costuma funcionar bem.
E em todos os casos precisamos fazer uma quantidade de operações proporcional ao tamanho do vetor, então temos complexidade linear com relação ao tamanho do vetor, ou seja O(n).
 

#### Excluir elemento - O(n)


### Implementação
Ver arquivo [`Vetor.php`](Vetor.php).


## Árvores


## Tabelas de Dispersão (ou Hash Tables, Dicionários)

