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


@app.route(_url('balde'), methods=['GET', 'HEAD', 'PUT', 'DELETE'])
def balde(balde):
    db = get_db()

    # Validação comum
    comando = 'SELECT * FROM baldes WHERE nome=:nome'
    baldes = db_fetchall(comando, {'nome' : balde})
    if request.method in ['GET', 'HEAD', 'DELETE']:
        # Não encontrou
        if len(baldes) < 1:
            return Response(
                f'Requisição inválida: Balde {balde} não encontrado.',
                status = 404)

        # Encontrou mais de um
        if len(baldes) > 1:
            return Response(f'Erro no servidor: há mais de 1 balde {balde}.',
                status = 500)

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
        if len(baldes) > 0:
            resp = Response(status = 400)
            resp.data = f'Balde {balde} já existe.'
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
            resp.data = f'Impossível remover balde {balde}: ele não está vazio.'
            return resp

        # Balde vazio, apagar
        comando = 'DELETE FROM objetos WHERE balde=:balde;'
        db.execute(comando, {'balde' : balde})
        db.commit()
        return Response(status = 200)


@app.route(_url('objeto'), methods=['GET', 'PUT', 'DELETE'])
def objeto(balde, chave):
    if request.method == 'GET':
        # Acessar objeto
        comando = 'SELECT * FROM objetos \
                   WHERE balde=:balde AND chave=:chave;'
        cursor = get_db().execute(comando, [balde, chave])
        row = cursor.fetchone()
        cursor.close()
        if row == None:
            return Response(status = 404)
        else:
            return row

    elif request.method == 'PUT':
        # Criar objeto
        dados = request.json
        dados['chave'] = chave
        dados['balde'] = balde
        comando = 'INSERT OR REPLACE \
                   INTO objetos (chave, valor, balde, usuario) \
                   VALUES (:chave, :valor, :balde, :usuario);'
        db = get_db()
        db.execute(comando, dados)
        db.commit()

        resp = Response(status=200)
        return resp

    elif request.method == 'DELETE':
        # Apagar objeto
        comando = 'DELETE FROM objetos WHERE chave=:chave AND balde=:balde;'
        db = get_db()
        cursor = db.execute(comando, {'chave' : chave, 'balde' : balde})
        db.commit()
        return Response(status = 200 if cursor.rowcount > 0 else 404)


@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()
