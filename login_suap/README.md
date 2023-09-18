# Cliente SUAP Javascript

*Aviso:* esse código é uma adaptação de https://github.com/ifrn-oficial/cliente_suap_javascript.

## Sobre

O **Cliente SUAP Javascript** implementa a integração com o SUAP, tendo 2 principais funcionalidades:

- Logar com SUAP via OAuth2
- Consumir API (via OAuth2) obtendo recursos em nome do usuário

## QuickStart

### Crie sua Aplicação no SUAP

Crie sua aplicação em https://suap.ifrn.edu.br/api/ com as seguintes informações:

- **Client Type:** Public
- **Authorization Grant Type:** Implicit
- **Redicert URIs**:

	http://localhost:8000/

	http://localhost:8000/index

	http://localhost:8000/dashboard

### Instalando, Configurando e Rodando o Cliente SUAP Javascript

Considerando que você já tenha clonado o repositório **cliente_suap_javascript**. abra o terminal:

	cd cliente_suap_javascript
	cp settings.sample.js settings.js

Faça os ajustes necessários, definindo a variável **CLIENT_ID**.

É necessário rodar a aplicação cliente num servidor local. Você pode usar o Flask para isso:
1. Crie e ative um ambiente virtual Python em um diretório de sua preferência.
	```bash
	python -m venv meuambiente
	source meuambiente/bin/activate
	```

1. Vá para o diretório deste projeto (adapte o comando se necessário).
	```bash
	cd cliente_suap_javascript
	```
	
1. Instale as dependências em `requirements.txt`
	```bash
	pip install requirements.txt
	```
1. Execute o app Flask:
	```bash
	flask --app app.py run --debug --port 8000
	```

Abra seu browser em http://localhost:8000/
