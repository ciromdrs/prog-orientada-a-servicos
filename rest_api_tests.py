'''Testes automáticos para APIs REST desenvolvidas na disciplina "Programação
Orientada a Serviços".

Requer o módulo `requests` instalado.'''

import requests


# Funções de teste
def test(description: str, req_method: function, req_args: list,
        expectation: function, exp_args:list):
    res, errmsg = expectation(req_method(*req_args), *exp_args)
    if not res:
        print('Erro no teste', description)
        print('-',errmsg)

def expect_status_code(response: requests.Response, code:int) -> (bool, str):
    if response.status_code != code:
        errmsg = f"Esperava status code {code}, recebeu {response.status_code}"
        return False, errmsg
    return True, ''


# Testes
# GET /
test('GET /', requests.get, ['http://localhost'], expect_status_code, [200])
