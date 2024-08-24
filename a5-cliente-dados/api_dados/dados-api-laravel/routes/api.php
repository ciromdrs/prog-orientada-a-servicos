<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use \App\Models\Banco;
use \App\Models\Objeto;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::name('bancos')->prefix('/bancos')->group(function(){
    Route::get('/', function () {
        $bancos = Banco::all();
        return response()->json($bancos);
    });

    Route::name('.banco')->prefix('/{banco}')->group(function() {
        Route::get('/', function (string $banco) {
            if (Banco::where('nome', '=', $banco)->first() == NULL) {
                return new Response("Banco $banco não encontrado.", 404);
            }
            // Ao definir uma rota com GET, o Laravel define automaticamente a mesma
            // rota com o método HEAD. Por isso, podemos assumir que é um HEAD e deixar
            // o conteúdo da resposta vazio. Depois, verificamos se é um GET e enviamos
            // o conteúdo da resposta se for o caso.
            // TODO: O método HTTP da request() está chegando vazio aqui. Descobrir por
            // quê.
            $objetos = Objeto::where('banco', '=', $banco)->get();
            $resp = response()->json($objetos);
            return $resp;
        });

        Route::put('/', function (Request $request, string $banco) {
            if (Banco::where('nome', '=', $banco)->first() != NULL)
                return new Response(
                    "Requisiçao inválida: o banco $banco já existe.",
                    400
                );
            $reg = new Banco;
            $reg->nome = $banco;
            $reg->usuario = $request->input('usuario');
            $reg->save();
            return new Response('', 201);
        });

        Route::delete('/', function (string $banco) {
            $reg = Banco::where('nome', '=', $banco)->first();
            if ($reg == NULL)
                return new Response("Banco $banco não encontrado.", 404);
            // Se o banco não estiver vazio, retorna erro
            $objetos = Objeto::where('banco', '=', $banco)->get();
            if (count($objetos) > 0)
                return new Response(
                    "Conflito: Impossível remover banco $banco. Ele não está vazio.",
                    409
                );
            // Banco vazio, pode apagar
            $reg->delete();
            return new Response('', 200);
        });

        Route::post('/', function (string $banco) {
            if (Banco::where('nome', '=', $banco)->first() == NULL)
                return new Response("Banco $banco não encontrado.", 404);
            # Como o cliente enviou um POST, o serviço deve tratar de criar uma
            # chave para o objeto e consequentemente o URI desse recurso.
            # A nova chave será, portanto, o próximo id da tabela 'objetos'.
            $seq_obj = DB::table('sqlite_sequence')
                ->select('seq')
                ->where('name', '=', 'objetos')
                ->first();
            # Declara a próxima chave como sendo 1 inicialmente
            $prox_chave = 1;
            # Se a sequência já foi criada, atualiza a chave para o próximo id
            if ($seq_obj != NULL)
                $prox_chave = $seq_obj->seq + 1;
            # Cria o objeto que será armazenado no banco
            $reg = new Objeto;
            $reg->chave = $prox_chave;
            $reg->usuario = request()->input('usuario');
            $reg->banco = $banco;
            $reg->valor = request()->input('valor');
            $reg->save();
            # Coloca o URI do novo recurso no corpo da resposta e nos 
            # cabeçalhos HTTP. Chamei a variável e chave do JSON de Location
            # porque esse é o nome do cabeçalho HTTP que serve para este fim.
            $location = route('bancos.banco.objeto',
                ['banco' => $banco, 'objeto' => $prox_chave]);
            $corpo = ['Location' => $location];
            return response(json_encode($corpo), 201)
                ->header('Location', $location);
        });
        
        Route::name('.objeto')->prefix('/{objeto}')->group(function() {
            Route::get('/', function (string $banco, string $chave) {
                if (Banco::where('nome', '=', $banco)->first() == NULL)
                    return new Response("Banco $banco não encontrado", 404);
                $reg = Objeto::where('chave', '=', $chave)
                    ->where('banco', '=', $banco)
                    ->first();
                if ($reg == NULL) {
                    return new Response("Objeto de chave $chave não encontrado no banco $banco", 404);
                }
                return response()->json($reg);
            });

            Route::put('/', function (string $banco, string $chave) {
                if (Banco::where('nome', '=', $banco)->first() == NULL)
                    return new Response("Banco $banco não encontrado.", 404);
                // Verifica se o recurso já existe
                $reg = Objeto::where('chave', '=', $chave)
                    ->where('banco', '=', $banco)
                    ->first();
                $existe = $reg != NULL;
                // Se não existe, cria
                if (!$existe) {
                    $reg = new Objeto;
                    $reg->chave = $chave;
                    $reg->usuario = request()->input('usuario');
                    $reg->banco = $banco;
                }
                $reg->valor = request()->input('valor');
                $reg->save();

                $codigo = $existe ? 200 : 201;
                return new Response("", $codigo);
            });
            
            Route::delete('/', function (string $banco, string $chave) {
                if (Banco::where('nome', '=', $banco)->first() == NULL)
                    return new Response("Banco $banco não encontrado", 404);
                $reg = Objeto::where('chave', '=', $chave)
                    ->where('banco', '=', $banco)
                    ->first();
                if ($reg == NULL) {
                    return new Response("Objeto de chave $chave não encontrado no banco $banco", 404);
                }
                $reg->delete();
                return new Response("", 200);
            });
        });
    });
});


function _url_replace(string $url, array $valores) {
    $replaced = $url;
    foreach ($valores as $nome => $valor) {
        $replaced = str_replace("{{$nome}}", $valor, $replaced);
    }
    return $replaced;
}
