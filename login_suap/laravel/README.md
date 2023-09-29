# Cliente SUAP Javascript com Servidor Laravel

## Pré-Requisitos
1. Você criou uma aplicação OAuth2 no SUAP e tem em mãos o `client_id`.

1. Você tem uma cópia da pasta `laravel` deste repositório.

## Como executar

1. Abra a pasta `laravel` no terminal e crie uma cópia do arquivo de configurações exemplo `settings.sample.js` via interface gráfica ou terminal:
	```bash
	cd public
	cp settings.sample.js settings.js
	cd ..
	```

1. Preencha os dados do arquivo `settings.js` que você acabou de criar de
acordo com o que você definiu na sua aplicação no SUAP.

1. Instale as dependências via composer:
	```bash
	composer install
	```

1. Crie o arquivo `.env` copiando o `.env.example` via interface gráfica ou terminal:
	```bash
	cp .env.example .env
	```

1. Gere a APP_KEY:
	```bash
	php artisan key:generate
	```

1. Execute o servidor Laravel:
	```bash
	php artisan serve
	```

Abra seu browser em http://localhost:8000/