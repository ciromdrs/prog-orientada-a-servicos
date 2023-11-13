# Login com SUAP via CLI
Exemplo de cliente de linha de comando para fazer login no SUAP usando [Javascript Web Tokens (JWT)](https://jwt.io/).




## Como Usar
1. Instale as dependências via Composer:
```bash
composer install
```

1. Crie um arquivo `credenciais.php` com o seguinte conteúdo:
```php
$usuario = 'sua-matrícula';
$senha = 'sua-senha-do-SUAP';
```

1. Execute o script `cliente_JWT.php`:
```bash
php cliente_JWT.php
```