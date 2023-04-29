# A2 - Lista de Programação
Arquivos referentes à Avaliação 2 - Lista de Programação.

## Pré-requisitos
Para responder esta avaliação, é necessário instalar PHP e Python3.

## Como responder esta avaliação
1. Esta avaliação deve ser respondida em PHP.
2. Não escreva entrada de dados se isto não for pedido explicitamente na
questão.
Escreva programas que recebem argumentos da linha de comando (use `$argv`) e
apenas exibem o resultado usando a função `var_dump`.
    - Ex.: Escreva um programa que recebe 3 números e calcula a média.
        - Errado:
        ```
        <?php
        echo "Digite o valor de a: ";
        $a = (int)readline();
        echo "Digite o valor de b: ";
        $b = (int)readline();
        echo "Digite o valor de c: ";
        $c = (int)readline();
        $media = ($a + $b + $c) / 3;
        echo "A média é: $media";
        ```

        - Certo (arquivo `q3.php`):
        ```
        <?php
        $a = intval($argv[1]);
        $b = intval($argv[2]);
        $c = intval($argv[3]);
        $media = ($a + $b + $c) / 3;
        var_dump($media);
        ```
    - Para executar o programa PHP acima, use o comando:
    ```
    php q3.php 5 3 7
    ```
    Perceba que o vetor `$argv` corresponde aos argumentos separados por espaço
    passados para o comando `php` acima:
        - `$argv[0] = "q3.php"`
        - `$argv[1] = "5"`
        - `$argv[2] = "3"`
        - `$argv[3] = "7"`

    Se você usar aspas, o conteúdo entre as aspas é considerado como um único argumento. Ex.:
    ```
    php meu_programa.php Sem aspas "Com aspas"
    ```
    Resulta em:
    - `$argv[0] = "meu_programa.php"`
    - `$argv[1] = "Sem"`
    - `$argv[2] = "aspas"`
    - `$argv[3] = "Com aspas"`

- Envie um único arquivo RAR contendo todas as suas respostas.

## Como conferir suas respostas
Você pode conferir suas respostas executando o script `av2_testes.py`.
Uso:
```
python <nome-deste-script.py> [diretório das respostas]
```
- Exemplos:
```
python av2_testes.py
```
```
python av2_testes.py "caminho/para/dir-respostas"
```
- O script testará cada resposta e informará quais estão erradas e por quê.

- Arquivos exemplo incluídos neste diretório:
    - Arquivos como `q1.php`, `q2.php` e `q3.php` são respostas exemplo.
    Ao chegar à questão correspondente ao arquivo, analise o seu conteúdo para
    entender como responder esta avaliação e aprender mais sobre PHP.
    - O arquivo `arq_exemplo.txt` é apenas um arquivo de texto exemplo para ser
    usado na questão Q18.
