'''API REST contendo exemplos.'''

from flask import Flask

app = Flask(__name__)

@app.route('/hello-world')
def hello_world():
    return 'Hello, world!'

@app.route('/hello-json')
def hello_json():
    return '{"hello": "json"}'
