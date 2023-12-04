'''Correção automática da avaliação A8 - Intro PHPUnit".
'''

import os, sys

# Verifica o comando
if len(sys.argv) > 2:
    print(
f'''Uso:
    python <nome-deste-script.py> <diretorio-respostas>
Exemplos:
    python {sys.argv[0]} ./a8-ciro-medeiros
''')
    exit(1)



# SUITE DE TESTES


dir = sys.argv[1]
respostas_certas = 0
respostas_erradas = 0


class Questao:
    def __init__(self, descricao: str, comando: str, testes: list):
        self.descricao = descricao
        self.comando = comando
        self.testes = testes

        # Converte os testes em objetos Teste
        self.testes = []
        for comando, func_expect, args_expect in testes:
            self.testes += [
                Teste(comando, func_expect, args_expect)]

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
    def __init__(self, comando: str, func_expect, args_expect: list):
        self.comando = comando
        self.func_expect = func_expect
        self.args_expect = args_expect


    def testar(self):
        # Verifica a resposta
        ok, erro = self.func_expect(*self.args_expect)
        if not ok:
            return self._formatar_erro(erro)
        return None

    def _formatar_erro(self, erro):
        return f'Comando: {self.comando}\n  {erro}'


def arquivo_contem(texto, arquivo, inicio=0):
    """
    Verifica se o `arquivo` contém o `texto` a partir da posição `inicio`.
    """
    encontrou, _, _,  = _buscar_no_arquivo(texto, f'{dir}{arquivo}')
    if encontrou:
        return True, ''
    return False, f'String {texto} não encontrada em {arquivo}.'

def arquivo_nao_contem(texto, arquivo):
    """
    Verifica se o `arquivo` não contém o `texto` a partir da posição `inicio`.
    """
    encontrou, linha, coluna = _buscar_no_arquivo(texto, f'{dir}{arquivo}')
    if encontrou:
        return False, f'String {texto} encontrada em {arquivo}:{linha}:{coluna}.'
    return True, ''


def _buscar_no_arquivo(texto, caminho: str) -> tuple[bool, int, int]:
    """
    Busca o `texto` no arquivo indicado pelo `caminho`.
    Retorna um booleano indicando se o texto foi encontrado e, se for, retorna
    a linha e coluna.
    Caso contrário, retorna False e -1 para linha e coluna.
    """
    linhas = None
    try:
        linhas = open(caminho, encoding='utf-8').readlines()
    except UnicodeDecodeError as e:
        linhas = open(caminho, encoding='iso-8859-1').readlines()
    for linha, string in enumerate(linhas):
        posicao = string.find(texto)
        if posicao >= 0:
            coluna = posicao
            return True, linha, coluna
    return False, -1, -1



# TESTES


questoes = [
    # GET
    Questao('Q1', '', [
        ('', arquivo_contem, ['getUsuario', 'Email.php']),
        ('', arquivo_contem, ['getDominio', 'Email.php']),
        ('', arquivo_contem, ['testGetUsuario', 'EmailTest.php']),
        ('', arquivo_contem, ['testGetDominio', 'EmailTest.php']),
    ]),

    Questao('Q2', '', [
        ('', arquivo_contem, ['enderecosInvalidosProvider', 'EmailTest.php']),
        ('', arquivo_contem, ['FILTER_VALIDATE_EMAIL', 'EmailTest.php']),
    ]),

    Questao('Q3', '', [
        ('', arquivo_nao_contem, ['->endereco', 'EmailTest.php']),
        ('', arquivo_nao_contem, ['->usuario', 'EmailTest.php']),
        ('', arquivo_nao_contem, ['->dominio', 'EmailTest.php']),
    ]),
]

for q in questoes:
    q.corrigir()


total_respostas = respostas_certas + respostas_erradas
print(f'Respostas certas: {respostas_certas} de {total_respostas}')
print(f'Respostas erradas: {respostas_erradas} de {total_respostas}')
