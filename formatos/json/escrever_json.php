
<?php

$p = [
    'pessoa' => [
        'id' => 123,
        'nome' => 'Alice',
    ]
];

$resultado = json_encode($p,
    JSON_UNESCAPED_SLASHES | # Não coloca `\` antes de `/`
    JSON_PRETTY_PRINT # Indenta
);

echo($resultado);
