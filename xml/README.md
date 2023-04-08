# XML
XML significa eXtensible Markup Language.
A maioria das linguagens de programação já vem com bibliotecas para tratar dados XML.

## Sintaxe resumida
Todo documento XML é uma árvore, que deve ter um elemento raiz.

Elementos XML são delimitados por tags de abertura e fechamento rotuladas.
  Ex.: `<minha_tag>conteúdo</minha_tag>`

*Atenção!* XML parece HTML, mas diferentemente de HTML:
* As tags XML são case-sensitive, isto é, diferenciam maiúsculas de minúsculas. Ex.:
`<pessoa>` é diferente de `<Pessoa>`.
* Espaços em branco são preservados. Ex.:
`<nome>Alice X.</nome>` é diferente de `<nome>Alice    X.</nome>`.

No conteúdo do elemento, podemos ter:
* Qualquer texto: `<nome>Alice</nome>`, `<idade>60</idade>`, `<pi>3.14</pi>`.
* Outros elementos:
```
<pessoa>
    <nome>Alice</nome>
    <idade>60</idade>
</pessoa>
```

Listas são representadas simplesmente pelo encadeamento de elementos:
```
<cores>
    <cor>azul</cor>
    <cor>vermelho</cor>
    <cor>amarelo</cor>
</cores>
```

Elementos também podem ter atributos, que aparecem dentro da tag de abertura e seguem a sintaxe `atributo="valor"`. Ex.:
```
<pessoa id="1">
    <nome>Alice</nome>
</pessoa>
```
Para descrever dados (ex.: o nome da pessoa), é preferível usar elementos aninhados.
Para descrever meta-dados (ex.: a ordem em que a pessoa aparece nesse arquivo XML), é preferível usar atributos.

Recomenda-se incluir o _prólogo_ no início do documento:
```
<?xml version="1.0" encoding="UTF-8"?>
```
O prólogo diz, entre outras coisas, a codificação dos caracteres do documento.

Um bom [Tutorial de XML](https://www.w3schools.com/xml/default.asp) (em inglês 🥲️).

## Exemplos
Esta pasta contém exemplos de arquivos XML.
Há também um script `ler_xml.php` que lê, interpreta o conteúdo e exibe o
valor de qualquer arquivo XML.

## Exercícios
1. Use o script `ler_xml.php` para ler o conteúdo de cada arquivo.

2. Altere o arquivo `ifrn.xml`:
    1. Adicione os dados do Campus Caicó.

    2. Abra a [página do IFRN](https://portal.ifrn.edu.br/), vá até a seção "IFRN em números" e copie esses valores para dentro do seu arquivo XML.
    Em qual parte do arquivo você colocará esses valores? Como os representará?

    3. Visto que esses dados mudarão no futuro, adicione onde achar adequado um elemento `data_consulta` contendo o timestamp do momento em que você consultou esses dados.
    É preferível usar o formato ISO 8601 para o timestamp.
    Você pode encontrar o timestamp atual nesse formato na internet.
