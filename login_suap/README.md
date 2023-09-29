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

### Execute o Cliente SUAP Javascript
Escolha um dos servidores nos subdiretórios (atualmente `flask/` ou `laravel/`).
Lá você encontrará informações específicas de como executá-lo.

