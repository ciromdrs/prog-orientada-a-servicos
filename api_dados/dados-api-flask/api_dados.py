'''API REST simples para armazenamento de pares chave-valor.'''

import json, sqlite3

from flask import Flask, g, request, Response



# Database


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



# Auxiliary functions


def _furl(key):
    '''Get an URL written in Flask's pattern syntax.'''
    global urls
    res = urls[key]
    res = res.replace('{', '<')
    res = res.replace('}', '>')
    return res




# Flask


app = Flask(__name__)

urls = {}
urls['root'] = '/'
urls['baldes'] = urls['root'] + 'baldes'
urls['balde'] = urls['baldes'] + '/{balde}'
urls['objeto'] = urls['balde'] + '/{chave}'


@app.route(_furl('root'), methods=['GET'])
def root():
    resp = Response(status = 200)
    resp.data = json.dumps(urls)
    resp.mimetype = 'application/json'
    return urls


@app.route(_furl('baldes'), methods=['GET'])
def baldes():
    db = get_db()

    # Listar baldes
    comando = 'SELECT * FROM baldes;'
    rows = db_fetchall(comando, [])
    resp = Response(status = 200)
    resp.data = json.dumps(rows)
    resp.mimetype = 'application/json'
    return resp


@app.route(_furl('balde'), methods=['GET', 'HEAD', 'PUT', 'DELETE'])
def balde(balde):
    db = get_db()

    if request.method in ['GET', 'HEAD']:
        # Verificar se o balde existe
        comando = 'SELECT * FROM baldes WHERE nome=:nome'
        rows = db_fetchall(comando, {'nome' : balde})

        # Não encontrou
        if len(rows) < 1:
            return Response(status = 404)

        # Encontrou mais de um
        if len(rows) > 1:
            return Response(status = 500)

        # Encontrou apenas um
        resp = Response(status = 200)
        if request.method == 'GET':
            # Listar dados
            comando = 'SELECT * FROM chave_valor WHERE balde=:balde'
            rows = db_fetchall(comando, {'balde' : balde})
            resp.data = json.dumps(rows)
            resp.mimetype = 'application/json'
        return resp

    elif request.method == 'PUT':
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
        comando = 'SELECT FROM chave_valor WHERE balde=:balde;'
        cursor = db.execute(comando, {'balde' : balde})
        if len(cursor.fetchall()) > 0:
            return Response(status = 409)

        # Balde vazio, apagar
        comando = 'DELETE FROM chave_valor WHERE balde=:balde;'
        cursor = db.execute(comando, {'balde' : balde})
        db.commit()
        return Response(status = 200)


@app.route(_furl('objeto'), methods=['GET', 'PUT', 'DELETE'])
def objeto(balde, chave):
    if request.method == 'GET':
        # Acessar objeto
        comando = 'SELECT * FROM chave_valor \
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
                   INTO chave_valor (chave, valor, balde, usuario) \
                   VALUES (:chave, :valor, :balde, :usuario);'
        db = get_db()
        db.execute(comando, dados)
        db.commit()

        resp = Response(status=200)
        return resp

    elif request.method == 'DELETE':
        # Apagar objeto
        comando = 'DELETE FROM chave_valor WHERE chave=:chave AND balde=:balde;'
        db = get_db()
        cursor = db.execute(comando, {'chave' : chave, 'balde' : balde})
        db.commit()
        return Response(status = 200 if cursor.rowcount > 0 else 404)


@app.teardown_appcontext
def close_connection(exception):
    db = getattr(g, '_database', None)
    if db is not None:
        db.close()
