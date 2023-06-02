'''Correção automática da avaliação A4 - Requisições".
'''

import os, sys

# Verifica o comando
if len(sys.argv) > 2:
    print(
f'''Uso:
    python <nome-deste-script.py> [Q<X>]
Exemplos:
    python {sys.argv[0]}
    python {sys.argv[0]} Q4
''')
    exit(1)



# SUITE DE TESTES


dir = '.' # TODO: remover esta variável
respostas_certas = 0
respostas_erradas = 0


class Questao:
    def __init__(self, descricao: str, script: str, testes: list):
        self.descricao = descricao
        self.script = script
        self.testes = testes

        # Converte os testes em objetos Teste
        self.testes = []
        for args_script, func_expect, args_expect in testes:
            self.testes += [
                Teste(script, args_script, func_expect, args_expect)]

    def corrigir(self):
        # Executa os testes, conta acertos e exibe erros
        global respostas_certas, respostas_erradas
        for t in self.testes:
            erro = t.testar()
            if erro:
                respostas_erradas += 1
                print(f'{self.descricao} errada:')
                print('-', erro)
                return
        print(f'{self.descricao} certa.')
        respostas_certas += 1


class Teste():
    def __init__(self, script: str, args: str, func_expect, args_expect: list):
        self.script = f'{dir}/{script}' if dir != '.' else script
        self.args = args
        self.func_expect = func_expect
        self.args_expect = args_expect

    @property
    def comando(self):
        return f'php {self.script} {self.args}'

    def testar(self):
        processo = os.popen(self.comando)
        resposta = processo.read()
        codigo = processo.close()
        codigo = 0 if codigo == None else codigo

        if codigo == 0: # O script funcionou
            # Verifica a resposta
            ok, erro = self.func_expect(resposta, *self.args_expect)
            if not ok:
                return self._formatar_erro(erro)
            return None
        elif codigo == 256: # File not found
            return self._formatar_erro(f'Arquivo {self.script} não encontrado.')
        elif codigo == 65280: # PHP Errror
            return self._formatar_erro(f'Erro do PHP.' +
            f' Execute o arquivo {self.script} para mais detalhes.')
        else:
            return self._formatar_erro(f"Erro {codigo}: {resposta}")

    def _formatar_erro(self, erro):
        return f'Comando: {self.comando}\n  {erro}'


def testar_igual(resultado: str, esperado: str,
    modificadores = [lambda x: x.strip("\n\r\t ")]) -> tuple[bool, str]:
    for m in modificadores + [lambda x: ignorar_cabecalhos(x)]:
        resultado = m(resultado)
        esperado = m(esperado)
    if resultado != esperado:
        erro = f"Esperava '{esperado}', recebeu '{resultado}'"
        return False, erro
    return True, ''


def ignorar_cabecalhos(resp_http):
    linhas = resp_http.split('\n')
    saida = ''
    for l in linhas:
        add = True
        for c in [
            'Date:',
            'X-',
            'Access-Control-',
            'Connection',
            'Host',
            'Cache-']:
            if l.lower().startswith(c.lower()):
                add = False
        if add:
            saida += l + '\n'
    return saida


# TESTES


questoes = [
    # GET
    Questao('Q1', 'q1.php', [
        ('', testar_igual, ['int(200)\nstring(13) "Hello, world!"']),
    ]),
    Questao('Q2', 'q2.php', [
        ('Alice', testar_igual, ['int(200)\nstring(13) "Hello, Alice!"']),
        ('Bruno', testar_igual, ['int(200)\nstring(13) "Hello, Bruno!"']),
    ]),
    Questao('Q3', 'q3.php', [
        ('Alice', testar_igual, ['int(200)\nstring(13) "Hello, Alice!"']),
        ('Bruno', testar_igual, ['int(200)\nstring(13) "Hello, Bruno!"']),
    ]),
    Questao('Q4', 'q4.php', [
        ('nome Alice idade 39', testar_igual, ['int(200)\nstring(20) "nome=Alice\nidade=39\n"\n']),
        ('nome Bruno qtd_filhos 3', testar_igual, ['int(200)\nstring(24) "nome=Bruno\nqtd_filhos=3\n"\n']),
    ]),

    # HEAD
    Questao('Q5', 'q5.php', [
        ('', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q6', 'q6.php', [
        ('Alice', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q7', 'q7.php', [
        ('Alice', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q8', 'q8.php', [
        ('nome Alice idade 39', testar_igual, ['int(200)\nstring(0) ""']),
    ]),

    # POST
    Questao('Q9', 'q9.php', [
        ('', testar_igual, ['int(201)\nstring(0) ""']),
    ]),
    Questao('Q10', 'q10.php', [
        ('Alice', testar_igual, ['int(201)\nstring(0) ""']),
    ]),
    Questao('Q11', 'q11.php', [
        ('nome Alice idade 39', testar_igual, ['int(201)\nstring(0) ""']),
    ]),
    Questao('Q12', 'q12.php', [
        ('nome Alice idade 39', testar_igual, ['int(201)\nstring(0) ""']),
    ]),

    # PUT
    Questao('Q13', 'q13.php', [
        ('', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q14', 'q14.php', [
        ('nome Alice idade 39', testar_igual, ['int(200)\nstring(0) ""']),
    ]),

    # DELETE
    Questao('Q15', 'q15.php', [
        ('', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q16', 'q16.php', [
        ('Alice', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q17', 'q17.php', [
        ('Alice', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
    Questao('Q18', 'q18.php', [
        ('nome Alice idade 39', testar_igual, ['int(200)\nstring(0) ""']),
    ]),
]


if len(sys.argv) == 2:
    questoes = [q for q in questoes if q.descricao == sys.argv[1]]

for q in questoes:
    q.corrigir()


total_respostas = respostas_certas + respostas_erradas
print(f'Respostas certas: {respostas_certas} de {total_respostas}')
print(f'Respostas erradas: {respostas_erradas} de {total_respostas}')
