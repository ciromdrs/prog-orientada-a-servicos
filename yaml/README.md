# YAML
(Adaptado da [Wikipédia](https://pt.wikipedia.org/wiki/YAML))

YAML é um acrônimo recursivo para "YAML Ain’t Markup Language".
YAML é uma linguagem de serialização de dados (assim como XML ou YAML), mas vai
além disso.
YAML foi construída com base na crença em que toda estrutura de dados pode ser
representada adequadamente como uma combinação de listas, hashes (mapas) e dados
escalares (valores simples).


## Sintaxe Resumida

A sintaxe é relativamente simples, porém mais complexa que YAML:
1. Um documento YAML é estruturado por identação com espaços em branco
   (caracteres de tabulação não são permitidos).
   Por convenção, *utilizaremos sempre dois espaços*.
2. *Valores escalares* são a forma mais simples:
    - Tipos básicos: `123`, `3.14`, `true`, `null`.
    - Strings não precisam de aspas, mas podem ser usadas tando duplas `"`
    como simples `'`.
3. Membros de listas começam com traço _e um espaço_ `- `, e ocupam uma linha
individual.
  Alternativamente, podem-se usar colchetes `[ ]` para represenar listas, sendo
  os membros separados por vígula _e espaço_ `, `.
4. Arrays associativos (ou mapas, hashes, dicionários, etc.) são representados
na forma `chave: valor` (repare novamente no espaço depois dos dois pontos `:`).
  Alternativamente, podem-se usar chaves `{ }`, como em YAML.
5. Comentários de final de linha são definidos por `#`.

Observação: YAML exige que vírgulas e pontos sejam seguidos por um espaço em
listas e arrays, para que valores escalares que contenham sinais de pontuação
(como 5,280 ou http://www.wikipedia.org) possam ser representados sem a
necessidade de aspas.


## Exemplos
Esta pasta contém exemplos de arquivos YAML.
Há também um script `ler_yaml.php` que lê, interpreta o conteúdo e exibe o
valor de qualquer arquivo YAML.
Perceba que cada arquivo exemplo é um documento YAML válido.


## Exercícios
1. Use o script `ler_yaml.php` para ler o conteúdo de cada arquivo. Para
executá-lo, é necessário instalar as dependências via `composer install`.

    Uso:
    ```
    php ler_yaml.php arquivo.yaml
    ```

    Analise o conteúdo de cada arquivo exemplo abrindo no editor de texto e
    compare com a saída do script.
    O PHP exibiu o valor correto? Ele conseguiu inferir o tipo dos dados?

2. Altere o arquivo `ifrn.yaml`:
    1. Adicione os dados do Campus Caicó (sem alterar os dados do Campus Natal).

    2. Abra a [página do IFRN](https://portal.ifrn.edu.br/), vá até a seção
    "IFRN em números" e copie esses valores para dentro do seu arquivo YAML.
    Em qual parte do arquivo você colocará esses valores? Como os representará?

    3. Visto que esses dados mudarão no futuro, adicione onde achar adequado uma
    chave `data_consulta` contendo a data e hora do momento em que você
    consultou as estatísticas do IFRN.

3. Analise o script `ler_yaml.php`.
Tente entender o que ele está fazendo.
