'''API REST simples para armazenamento de pares chave-valor.'''

import json, sqlite3

from flask import Flask, g, request, Response



# BANCO DE DADOS


DATABASE = 'dados.sqlite3'

def get_db():
    db = getattr(g, '_database', None)
    if db is None:
        db = g._database = sqlite3.connect(DATABASE)
        db.row_factory = dict_factory
    return db


def dict_factory(cursor, row):
    return dict((cursor.description[idx][0], value)
                for idx, value in enumerate(row))


def db_fetchall(comando, args):
    cursor = get_db().execute(comando, args)
    rows = cursor.fetchall()
    cursor.close()
    return rows


def db_fetchone(comando, args):
    cursor = get_db().execute(comando, args)
    row = cursor.fetchone()
    cursor.close()
    return row


def db_execute(comando, args):
    db = get_db()
    cursor = db.execute(comando, args)
    db.commit()
    return cursor


# FUNÇÕES AUXILIARES


def _url(key):
    '''Get an URL written in Flask's pattern syntax.'''
    global urls
    res = urls[key]
    res = res.replace('{', '<')
    res = res.replace('}', '>')
    return res



# FLASK


app = Flask(__name__)


# Padrões de URL
urls = {}
urls['root'] = '/'
urls['baldes'] = urls['root'] + 'baldes'
urls['balde'] = urls['baldes'] + '/{balde}'
urls['objeto'] = urls['balde'] + '/{chave}'


# Rotas

@app.route(_url('root'), methods=['GET'])
def root():
    resp = Response(status = 200)
    resp.data = json.dumps(urls)
    resp.mimetype = 'application/json'
    return urls


@app.route(_url('baldes'), methods=['GET'])
def baldes():
    db = get_db()

    # Listar baldes
    comando = 'SELECT * FROM baldes;'
    rows = db_fetchall(comando, [])
    resp = Response(status = 200)
    resp.data = json.dumps(rows)
    resp.mimetype = 'application/json'
    return resp


@app.route(_url('balde'), methods=['GET', 'HEAD', 'PUT', 'DELETE', 'POST'])
def balde(balde):
    db = get_db()

    # Acessa o balde
    comando = 'SELECT * FROM baldes WHERE nome=:nome'
    baldes = db_fetchall(comando, {'nome' : balde})
    balde_existe = len(baldes) == 1

    # Validação comum a todos os métodos HTTP
    if len(baldes) > 1:
        return Response(f'Erro no servidor: há mais de 1 balde {balde}.',
            status = 500)
    # Validações específicas
    if request.method in ['GET', 'HEAD', 'DELETE', 'POST']:
        # Não encontrou
        if not balde_existe:
            return Response(
                f'Requisição inválida: Balde {balde} não encontrado.',
                status = 404)

    # Tratamento da requisição
    if request.method in ['GET', 'HEAD']:
        resp = Response(status = 200)
        if request.method == 'GET':
            # Listar dados
            comando = 'SELECT * FROM objetos WHERE balde=:balde'
            objetos = db_fetchall(comando, {'balde' : balde})
            resp.data = json.dumps(objetos)
            resp.mimetype = 'application/json'
        return resp

    elif request.method == 'PUT':
        if balde_existe:
            resp = Response(status = 400)
            resp.data = f'Requisiçao inválida: o balde {balde} já existe.'
            return resp

        # Criar balde
        dados = request.json
        dados['balde'] = balde
        comando = 'INSERT INTO baldes (nome, usuario) VALUES (:balde, :usuario);'
        db = get_db()
        db.execute(comando, dados)
        db.commit()
        resp = Response(status = 201)
        return resp

    elif request.method == 'DELETE':
        # Se o balde não estiver vazio, retorna erro
        comando = 'SELECT FROM objetos WHERE balde=:balde;'
        cursor = db.execute(comando, {'balde' : balde})
        if len(cursor.fetchall()) > 0:
            resp = Response(status = 409)
            resp.data = f'Conflito: Impossível remover balde {balde}.' + \
                         ' Ele não está vazio.'
            return resp

        # Balde vazio, apagar
        comando = 'DELETE FROM objetos WHERE balde=:balde;'
        db.execute(comando, {'balde' : balde})
        db.commit()
        return Response(status = 200)

    elif request.method == 'POST':
        # Uma requisição POST a um balde cria um objeto com uma chave gerada
        dados = request.json
        dados['balde'] = balde
        cmd_insert = '' # Comando SQL para inserir ou alterar dados, dependendo
                        # da requisição.

        # O cliente não envia a chave, então precisamos pegar a próxima
        cmd_chave = 'SELECT seq FROM sqlite_sequence WHERE name="objetos"'
        prox_chave = db_fetchall(cmd_chave, [])[0]['seq']
        dados['chave'] = prox_chave
        cmd_insert = 'INSERT INTO objetos (chave, valor, balde, usuario) \
                      VALUES (:chave, :valor, :balde, :usuario);'
        db.execute(cmd_insert, dados)
        db.commit()
        resp = Response(status = 201)
        return resp


@app.route(_url('objeto'), methods=['GET', 'HEAD', 'PUT', 'DELETE'])
def objeto(balde, chave):
    # Verificar se a chave existe
    comando = 'SELECT * FROM objetos WHERE balde=:balde AND chave=:chave;'
    objeto = db_fetchone(comando, [balde, chave])

    # Validação comum a vários métodos
    if request.method in ['GET', 'HEAD', 'DELETE']:
        if objeto == None:
            resp = Response(status = 404)
            resp.data = f'Chave {chave} não encontrada no balde {balde}.'
            return resp

    if request.method == 'GET':
        return objeto

    elif request.method == 'PUT':
        # Criar objeto
        insert_replace = 'INSERT'
        resp = Response(status = 200)
        if objeto != None:
            # Alterar objeto
            insert_replace = 'REPLACE'
            resp.status = 201
        comando = insert_replace + \
                  ' INTO objetos (chave, valor, balde, usuario) \
                    VALUES (:chave, :valor, :balde, :usuario);'
        dados = request.json
        dados['chave'] = chave
        dados['balde'] = balde
        db_execute(comando, dados)
        return resp

    elif request.method == 'DELETE':
        # Apagar objeto
        comando = 'DELETE FROM objetos WHERE chave=:chave AND balde=:balde;'
        db_execute(comando, {'chave' : chave, 'balde' : balde})
        return Response(status = 200)


@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()
