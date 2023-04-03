'''Testes automáticos para APIs REST desenvolvidas na disciplina "Programação
Orientada a Serviços".'''

import requests


# Funções de teste
def test(description, req_method, req_args, expectation, exp_args):
    res, errmsg = expectation(req_method(*req_args), *exp_args)
    if not res:
        print('Erro no teste', description)
        print('-',errmsg)

def expect_status_code(response: requests.Response, code:int) -> (bool, str):
    if response.status_code != code:
        return False, f"Esperava status code {code}, recebeu {response.status_code}"
    return True, ''

