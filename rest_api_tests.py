'''Testes automáticos para APIs REST desenvolvidas na disciplina "Programação
Orientada a Serviços".

Requer o módulo `requests` instalado.

Uso: python <nome-deste-script.py> <endereco-do-servidor>
Exemplo: python rest_api_tests.property "http://localhost:80"'''

import sys
import requests

# Funções de teste
def test(description: str, req_method, req_args: list, expectation,
        exp_args:list):
    res, errmsg = expectation(req_method(*req_args), *exp_args)
    if not res:
        print('Erro no teste', description)
        print('-',errmsg)

def expect_status_code(response: requests.Response, code:int) -> (bool, str):
    if response.status_code != code:
        errmsg = f"Esperava status code {code}, recebeu {response.status_code}"
        return False, errmsg
    return True, ''

def url(path: str) -> str:
    '''Adiciona o endereço do servidor como prefixo a um caminho para gerar uma
    URL.'''
    server_address = sys.argv[1]
    return server_address + path


# Testes
# GET /hello-world
test('GET /hello-world', requests.get, [url('/hello-world')],
    expect_status_code, [200])
