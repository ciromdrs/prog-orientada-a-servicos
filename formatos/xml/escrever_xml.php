
<?php

// Inicialização e configuração
$w = new XMLWriter(); // Objetos XMLWriter escrevem documentos XML
$w->openMemory(); // Inicializa a memória para escrever o XML
$w->setIndent(1); // Ativa a opção de indentação
$w->setIndentString('  '); // Define a string usada para indentar

// Escrita do documento
$w->startDocument('1.0', 'UTF-8'); // Inicia o documento com a versão do XML e
                                   // codificação

$w->startElement('pessoa'); // Cria um elemento pessoa

$w->startAttribute('atributo'); // Cria um atributo
$w->text('valor'); // Escreve o valor para o atributo
$w->endAttribute(); // Encerra o atributo

$w->startElement('id'); // Cria o elemento id
$w->text('123'); // Escreve o valor para o id
$w->endElement(); // Encerra o elemento nome
$w->startElement('nome'); // Faz o mesmo com o elemento nome
$w->text('Alice');
$w->endElement();

$w->endElement(); // Encerra o elemento pessoa

$w->endDocument(); // Finaliza o documento

$resultado = $w->outputMemory(); // Acessa o documento como uma string

echo($resultado);
