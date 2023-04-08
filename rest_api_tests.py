'''Testes automáticos para APIs REST desenvolvidas na disciplina "Programação
Orientada a Serviços".

Requer o módulo `requests` instalado.

Uso: python <nome-deste-script.py> <endereco-do-servidor>
Exemplo: python rest_api_tests.py "http://localhost:80"'''

import sys
import requests

# Funções de teste
passed_tests = 0
failed_tests = 0

def test(description: str, req_method, req_args: list, expectations: list):
    global passed_tests, failed_tests
    errors = []
    response = req_method(*req_args)
    for (expectation, args) in expectations:
        ok, errmsg = expectation(response, *args)
        if ok:
            passed_tests += 1
        else:
            failed_tests += 1
            errors += [errmsg]

    if len(errors) > 0:
        print('Erros no teste', description)
        for errmsg in errors:
            print('-', errmsg)

def expect_status_code(response: requests.Response, code: int) -> (bool, str):
    if response.status_code != code:
        errmsg = f"Esperava status code {code}, recebeu {response.status_code}"
        return False, errmsg
    return True, ''

def expect_body(response: requests.Response, text: str) -> (bool, str):
    if response.text != text:
        errmsg = f"Esperava o corpo \"{text}\", recebeu \"{response.text}\""
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
    [(expect_status_code, [200]),
    (expect_body, ['Hello, world!'])
    ])

test('GET /hello-json', requests.get, [url('/hello-json')],
    [(expect_status_code, [200]),
    (expect_body, ['{"hello": "json"}'])
    ])

print(f'Sucessos: {passed_tests}')
print(f'Falhas: {failed_tests}')
