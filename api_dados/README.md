# Serviço de Dados
## Descrição
Serviço genérico para armazenamento de dados no formato chave-valor que imita
uma simplificação do AWS S3:

1. Os dados são chamados de _objetos_, e são armazenados dentro de _baldes_.

2. Cada balde tem um nome único em todo o banco de dados.

3. Cada dado pertence a apenas um balde, e tem uma chave única dentro dele.

## Endpoints
- `api/`:
  - `GET`: Retorna os padrões de URL da API.


- `api/baldes`:
  - `GET`: Retorna a lista de baldes.


- `api/baldes/{balde}`:
  - Variáveis de caminho:
    - `balde`: o nome único do balde.
  - `GET`: Retorna todos os objetos dentro do balde `balde`.
  - `PUT`: Cria um balde de nome `balde`.
    - Parâmetros:
      - `usuario`: o dono do balde.
  - `POST`: Cria um objeto de chave inteira gerada automaticamente no balde
    `balde`.
    - Parâmetros:
      - `usuario`: o dono do objeto.
      - `valor`: o dado em si.
      Pode ser codificado, por exemplo, em JSON ou XML.
  - `DELETE`: Apaga o balde de nome `balde` se ele estiver vazio.


- `api/baldes/{balde}/{chave}`
  - Variáveis de caminho:
    - `balde`: o nome único do balde.
    - `chave`: a chave única do objeto dentro do balde `balde`.
  - `GET`: Retorna o objeto de chave `chave` armazenado no balde `balde`.
  - `PUT`: Cria ou altera o objeto de chave `chave` no balde `balde`.
    - Parâmetros:
      - `usuario`: o dono do objeto.
      - `valor`: o dado a ser armazenado sob a chave `chave`.
  - `DELETE`: Apaga o objeto de chave `chave` do balde `balde`.


## Como executar
Instale as dependências:
```
composer install
```

Execute as migrações (responda `y` para criar o banco):
```
php artisan migrate
```

Execute o servidor:
```
php artisan serve
```
