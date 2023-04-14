# JSON
JSON significa JavaScript Object Notation.
Porém JSON não tem nenhuma relação de exclusividade com JavaScript, a maioria das linguagens de programação já vem com bibliotecas para tratar dados JSON.

## Sintaxe resumida
* Valores simples por si só são elementos JSON:
    * O número `42`.
    * A string `"hello"`.
    * Os valores lógicos `true` e `false`.
    * O valor nulo `null`.
* Listas são elementos JSON:
    * Use `[` e `]` para definir uma lista de elementos separados por `,`.
    * Ex.: `[1, 2, "três"]`.
* Objetos são elementos JSON que associam chaves a valores:
    * Use `{` e `}` para definir um objeto e inclua pares `"chave" : valor` separados por `,`, onde `"chave"` é necessariamente uma string e `valor` pode ser qualquer elemento JSON.
    * Ex.: `{"nome" : "Alice", "idade" : 60}`.

Não existe sintaxe JSON para:
* Comentários: JSON é um formato de dados apenas. Podemos criar uma chave `"_comentario"`, por exemplo, e usar o valor dela como comentário.
Porém isso não é uma boa prática e devemos evitar transmitir esse valor.
* Timestamps (data e hora): usa-se uma string comum, e costuma-se seguir o padrão [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601).

Mais informações na [Documentação oficial](https://www.json.org/json-pt.html).

## Exemplos
Esta pasta contém exemplos de arquivos JSON.
Há também um script `ler_json.php` que lê, interpreta o conteúdo e exibe o
valor de qualquer arquivo JSON.
Perceba que cada arquivo exemplo é um documento JSON válido.

## Exercícios
1. Use o script `ler_json.php` para ler o conteúdo de cada arquivo.
Uso:
```
php ler_json.php arquivo.json
```
    1. Analise o conteúdo de cada arquivo abrindo no editor de texto e compare com a saída do script.
    O PHP exibiu o valor correto? Ele conseguiu inferir o tipo dos dados?

    2. Analise o script `ler_json.php`.
    Tente entender o que ele está fazendo.

2. Altere o arquivo `ifrn.json`:
    1. Adicione os dados do Campus Caicó (sem alterar os dados do Campus Natal).

    2. Abra a [página do IFRN](https://portal.ifrn.edu.br/), vá até a seção "IFRN em números" e copie esses valores para dentro do seu arquivo JSON.
    Em qual parte do arquivo você colocará esses valores? Como os representará?

    3. Visto que esses dados mudarão no futuro, adicione onde achar adequado uma chave `data_consulta` contendo a data e hora do momento em que você consultou as estatísticas do IFRN.
