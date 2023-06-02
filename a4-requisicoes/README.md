# A4 - Requisições
Arquivos referentes à Avaliação 4 - Requisições.

## Pré-requisitos
Para responder esta avaliação, é necessário instalar PHP e Python3.

## Como responder esta avaliação
1. Esta avaliação deve ser respondida em PHP.
2. Crie neste mesmo diretório (`a4-requisicoes`) um arquivo para cada resposta com o nome qX.php, onde X corresponde ao número da questão.
3. Não escreva entrada de dados via teclado.
Escreva programas que recebem argumentos da linha de comando (use `$argv`) e
apenas exibem o resultado usando a função `var_dump`.
4. Importe o script `requisicoes.php` em todas as suas respostas.
Esse script contém uma função muito útil para enviar requisições HTTP.
Há comentários explicando como a função funciona: *leia-os com atenção*.
5. Envie um único arquivo RAR contendo todas as suas respostas.


## Como conferir suas respostas
Execute (apenas uma vez) o servidor Laravel que está na pasta `a4-servidor` usando `php artisan serve`.

Com o servidor rodando, execute o script de testes usando `python3 a4_testes.py` quantas vezes forem necessárias.
O script testará cada resposta e informará quais estão erradas e por quê.
Também é possível especificar uma resposta a testar. Ex.: `python3 a4_testes.py Q4`.

*ATENÇÃO:* Pelo fato de o script de testes enviar muitas requisições ao servidor, pode acontecer de o Laravel passar a rejeitá-las.
Nesse caso, basta parar o servidor e reiniciá-lo.
