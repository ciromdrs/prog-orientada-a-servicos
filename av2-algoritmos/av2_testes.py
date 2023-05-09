'''Testes automáticos para a Avaliação 2".

Script para correção automática das respostas.
'''

import os, sys

# Verifica o comando
if len(sys.argv) > 3:
    print(
'''Uso:
    python <nome-deste-script.py> [<diretório das respostas>] [<--Q<Número da questão>>]
Exemplos:
    python av2_testes.py
    python av2_testes.py "caminho/para/dir-respostas"
    python av2_testes.py . Q10
''')
    exit(1)

# Código para testar questões
dir = sys.argv[1] if len(sys.argv) == 2 else '.'
respostas_certas = 0
respostas_erradas = 0


def questao(descricao: str, script: str, subtestes: list):
    # Converte os subtestes em objetos Teste
    testes = []
    for args_script, func_expect, args_expect in subtestes:
        testes += [Teste(script, args_script, func_expect, args_expect)]

    # Executa os testes, conta acertos e exibe erros
    global respostas_certas, respostas_erradas
    for t in testes:
        erro = t.testar()
        if erro:
            respostas_erradas += 1
            print(f'{descricao} errada:')
            print('-', erro)
            return
    print(f'{descricao} certa.')
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
        elif codigo == 65280: # PHP Syntax Errror
            return self._formatar_erro(f'Erro de sintaxe.' +
            f' Execute o arquivo {self.script} para mais detalhes.')
        else:
            return self._formatar_erro(f"Erro {codigo}: {resposta}")

    def _formatar_erro(self, erro):
        return f'Comando: {self.comando}\n  {erro}'


def testar_igual(resultado: str, esperado: str, strip="\n ") -> bool | tuple[bool, str]:
    resultado = resultado.strip(strip)
    esperado = esperado.strip(strip)
    if resultado != esperado:
        erro = f"Esperava '{esperado}', recebeu '{resultado}'"
        return False, erro
    return True, ''

# Testes


questions = {
    'Q1': lambda: questao('Q1', 'q1.php', [('', testar_igual, ['string(13) "Hello, world!"'])]),
    'Q2': lambda: questao('Q2', 'q2.php', [('Ciro', testar_igual, ['string(12) "Hello, Ciro!"'])]),
    'Q3': lambda: questao('Q3', 'q3.php', [('10.5 14.3 35.2', testar_igual, ['float(20)'])]),
    'Q4': lambda: questao('Q4', 'q4.php', [
        ('525', testar_igual, ['string(7) "0h8m45s"']),
        ('3679', testar_igual, ['string(7) "1h1m19s"'])]),
    'Q5': lambda: questao('Q5', 'q5.php', [('3 5 6 1', testar_igual, ['float(5)'])]),
    'Q6': lambda: questao('Q6', 'q6.php', [
        ('17', testar_igual, ['string(5) "Menor"']),
        ('18', testar_igual, ['string(6) "Adulto"']),
        ('60', testar_igual, ['string(5) "Idoso"'])
    ]),
    'Q7': lambda: questao('Q7', 'q7.php', [
        ('1 2 3', testar_igual, ['int(3)']),
        ('3 2 1', testar_igual, ['int(3)']),
        ('1 3 2', testar_igual, ['int(3)']),
        ('1 3 3', testar_igual, ['int(3)']),
        ('3 3 2', testar_igual, ['int(3)']),
        ('3 3 3', testar_igual, ['int(3)'])
    ]),
    'Q8': lambda: questao('Q8', 'q8.php', [
        ('7 8 9 10', testar_igual, ['bool(true)']),
        ('9 10 7 8', testar_igual, ['bool(true)']),
        ('7 8 8 9', testar_igual, ['bool(true)']),
        ('8 9 7 8', testar_igual, ['bool(true)']),
        ('7 10 8 11', testar_igual, ['bool(false)']),
        ('8 11 7 10', testar_igual, ['bool(false)']),
        ('8 10 7 11', testar_igual, ['bool(false)']),
        ('7 11 8 10', testar_igual, ['bool(false)']),
    ]),
    'Q9': lambda: questao('Q9', 'q9.php', [
        ('5', testar_igual, ['string(10) "1 2 3 4 5 "'])
    ]),
    'Q10': lambda: questao('Q10', 'q10.php', [
        ('3', testar_igual, ['int(6)']),
        ('5', testar_igual, ['int(120)'])
    ]),
    'Q11': lambda: questao('Q11', 'q11.php', [
        ('1', testar_igual, ['int(1)']),
        ('2', testar_igual, ['int(1)']),
        ('3', testar_igual, ['int(2)']),
        ('5', testar_igual, ['int(5)']),
        ('10', testar_igual, ['int(55)'])
    ]),
    'Q12': lambda: questao('Q12', 'q12.php', [
        ('\"Alice Bezerra Costa\"', testar_igual, [
            'array(3) {\n  [0]=>\n  string(5) "Alice"\n  [1]=>\n  string(7) ' +
            '"Bezerra"\n  [2]=>\n  string(5) "Costa"\n}'])
    ]),
    'Q13': lambda: questao('Q13', 'q13.php', [
        ('"1 2 3 4 5"', testar_igual, [
            'array(5) {\n  [0]=>\n  int(1)\n  [1]=>\n  int(2)\n  [2]=>\n  ' +
            'int(3)\n  [3]=>\n  int(4)\n  [4]=>\n  int(5)\n}'])
    ]),
    'Q14': lambda: questao('Q14', 'q14.php', [
        ('"15.7 14.3 30.0 20"', testar_igual, ['float(20)'])
    ]),
    'Q15': lambda: questao('Q15', 'q15.php', [
        ('15.7 14.3 30.0 20', testar_igual, ['float(20)'])
    ]),
    'Q16': lambda: questao('Q16', 'q16.php', [
        ('POS Alice Bob Claire',
            testar_igual, [
                'string(3) "POS"\narray(3) {\n  [0]=>\n  string(5) ' +
                '"Alice"\n  [1]=>\n  string(3) "Bob"\n  [2]=>\n  string(6) ' +
                '"Claire"\n}\n'])
    ]),
    'Q17': lambda: questao('Q17', 'q17.php', [
        ('5 3 2 2 6', testar_igual, [
            'array(5) {\n  [0]=>\n  string(1) "2"\n  [1]=>\n  string(1) "2"\n  ' +
            '[2]=>\n  string(1) "3"\n  [3]=>\n  string(1) "5"\n  [4]=>\n  ' +
            'string(1) "6"\n}'])
    ]),
    'Q18': lambda: questao('Q18', 'q18.php', [
        ('arq_exemplo.txt', testar_igual, [
            'string(33) "Eu sou apenas um arquivo exemplo."'])
    ]),
    'Q19': lambda: questao('Q19', 'q19.php', [
        ('Alice 45 Bob Claire', testar_igual, [
            'object(Pessoa)#1 (3) {\n  ["nome"]=>\n  string(5) "Alice"\n  ' +
            '["idade"]=>\n  int(45)\n  ["dependentes"]=>\n  array(2) {\n    ' +
            '[0]=>\n    string(3) "Bob"\n    [1]=>\n    string(6) "Claire"\n  ' +
            '}\n}\n'])
    ]),
    'Q20': lambda: questao('Q20', 'q20.php', [
        ('Alice 45 Bob 20 Claire 18', testar_igual, [
            'object(Pessoa)#3 (3) {\n  ["nome"]=>\n  string(5) "Alice"\n  ' +
            '["idade"]=>\n  int(45)\n  ["dependentes"]=>\n  array(2) {\n    ' +
            '[0]=>\n    object(Pessoa)#1 (3) {\n      ["nome"]=>\n      ' +
            'string(3) "Bob"\n      ["idade"]=>\n      int(20)\n      ' +
            '["dependentes"]=>\n      array(0) {\n      }\n    }\n    ' +
            '[1]=>\n    object(Pessoa)#2 (3) {\n      ["nome"]=>\n      ' +
            'string(6) "Claire"\n      ["idade"]=>\n      int(18)\n      ' +
            '["dependentes"]=>\n      array(0) {\n      }\n    }\n  }\n}'])
    ]),
}

if len(sys.argv) > 2:
    questions[sys.argv[2]]()
else:
    for question in questions.values():
        question()


total_respostas = respostas_certas + respostas_erradas
print(f'Respostas certas: {respostas_certas} de {total_respostas}')
print(f'Respostas erradas: {respostas_erradas} de {total_respostas}')
