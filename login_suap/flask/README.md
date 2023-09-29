# Cliente SUAP Javascript com Servidor Flask

## Pré-Requisitos
1. Você criou uma aplicação OAuth2 no SUAP e tem em mãos o `client_id`.

1. Você tem uma cópia da pasta `flask` deste repositório.

## Como executar

1. Abra a pasta `flask` no terminal e crie uma cópia do arquivo de configurações exemplo `settings.sample.js`:
	```bash
	cd cliente_suap_javascript
	cp settings.sample.js settings.js
	```

1. Preencha os dados do arquivo `settings.js` que você acabou de criar de
acordo com o que você definiu na sua aplicação no SUAP.

1. Crie e ative um ambiente virtual Python em um diretório de sua preferência.
	O comando abaixo faz isto no diretório pai deste projeto.
	```bash
	cd ..
	python -m venv meuambiente
	source meuambiente/bin/activate
	```
	Você deve perceber o `(meuambiente)` exibido antes do usuário no terminal.
	Isto significa que o ambiente virtual está ativado e todas as bibliotecas
	Python que instalarmos ficarão apenas no ambiente virtual e não no
	sistema operacional.
	Isso evita a necessidade de saber a senha de administrador da máquina que
	estamos usando e a instalação indesejada de bibliotecas diretamente	no
	nosso sistema operacional.

1. Volte para o diretório deste projeto (adapte o comando se necessário):
	```bash
	cd cliente_suap_javascript
	```
	
1. Com o ambiente virtual ativado, instale as dependências listadas em
`requirements.txt`:
	```bash
	pip install requirements.txt
	```
1. Execute o app Flask:
	```bash
	flask --app app.py run --debug --port 8000
	```

Abra seu browser em http://localhost:8000/
