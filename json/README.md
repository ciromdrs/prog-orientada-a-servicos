# JSON
Sintaxe resumida do JSON:
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

Não existe um formato específico para timestamps (data e hora) em JSON, mas costuma-se seguir o padrão [ISO 8601](https://en.wikipedia.org/wiki/ISO_8601).
Documentação oficial: https://www.json.org/json-pt.html.

## Exemplos
Esta pasta contém exemplos de arquivos JSON.
Há também um script `ler_json.php` que lê, interpreta e o conteúdo e exibe o
valor de qualquer arquivo JSON.
Perceba que cada arquivo exemplo é um documento JSON válido.

## Exercícios
1 - Use o script `ler_json.php` para ler o conteúdo de cada arquivo.

2 - Altere o arquivo `ifrn.json` adicionando os dados do Campus Caicó.
