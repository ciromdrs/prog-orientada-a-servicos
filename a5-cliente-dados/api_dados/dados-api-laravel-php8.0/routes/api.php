<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use \App\Models\Balde;
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

$url = [];
$url['root'] = '/';
$url['baldes'] = "{$url['root']}baldes";
$url['balde']  = "{$url['baldes']}/{balde}";
$url['objeto']  = "{$url['balde']}/{chave}";

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get($url['root'], function () use ($url) {
    return response()->json($url);
});


Route::get($url['baldes'], function () {
    $baldes = Balde::all();
    return response()->json($baldes);
});


Route::get($url['balde'], function (string $balde) {
    if (Balde::where('nome', '=', $balde)->first() == NULL) {
        return new Response("Balde $balde não encontrado.", 404);
    }
    // TODO: Ao definir uma rota com GET, o Laravel define automaticamente a mesma
    // rota com o método HEAD. Por isso, podemos assumir que é um HEAD e deixar
    // o conteúdo da resposta vazio. Depois, verificamos se é um GET e enviamos
    // o conteúdo da resposta se for o caso.
    // TODO: O método HTTP da request() está chegando vazio aqui. Descobrir por
    // quê.
    $objetos = Objeto::where('balde', '=', $balde)->get();
    $resp = response()->json($objetos);
    return $resp;
});

Route::put($url['balde'], function (Request $request, string $balde) {
    if (Balde::where('nome', '=', $balde)->first() != NULL)
        return new Response(
            "Requisiçao inválida: o balde $balde já existe.",
            400
        );
    $reg = new Balde;
    $reg->nome = $balde;
    $reg->usuario = $request->input('usuario');
    $reg->save();
    return new Response('', 201);
});

Route::delete($url['balde'], function (string $balde) {
    $reg = Balde::where('nome', '=', $balde)->first();
    if ($reg == NULL)
        return new Response("Balde $balde não encontrado.", 404);
    // Se o balde não estiver vazio, retorna erro
    $objetos = Objeto::where('balde', '=', $balde)->get();
    if (count($objetos) > 0)
        return new Response(
            "Conflito: Impossível remover balde $balde. Ele não está vazio.",
            409
        );
    // Balde vazio, pode apagar
    $reg->delete();
    return new Response('', 200);
});


Route::post($url['balde'], function (string $balde) use ($url) {
    if (Balde::where('nome', '=', $balde)->first() == NULL)
        return new Response("Balde $balde não encontrado.", 404);
    // Pega a próxima chave da sequência
    $seq_obj = DB::table('sqlite_sequence')
        ->select('seq')
        ->where('name', '=', 'objetos')
        ->first();
    $prox_chave = 1;
    if ($seq_obj != NULL)
        $prox_chave = $seq_obj->seq + 1;
    $reg = new Objeto;
    $reg->chave = $prox_chave;
    $reg->usuario = request()->input('usuario');
    $reg->balde = $balde;
    $reg->valor = request()->input('valor');
    $reg->save();
    return response("", 201)->header(
        'Location',
        _url_replace(
            $url['objeto'],
            ['balde' => $balde, 'chave' => $prox_chave]
        )
    );
});


Route::get($url['objeto'], function (string $balde, string $chave) {
    if (Balde::where('nome', '=', $balde)->first() == NULL)
        return new Response("Balde $balde não encontrado", 404);
    $reg = Objeto::where('chave', '=', $chave)
        ->where('balde', '=', $balde)
        ->first();
    if ($reg == NULL) {
        return new Response("Objeto de chave $chave não encontrado no balde $balde", 404);
    }
    return response()->json($reg);
});

Route::put($url['objeto'], function (string $balde, string $chave) {
    if (Balde::where('nome', '=', $balde)->first() == NULL)
        return new Response("Balde $balde não encontrado.", 404);
    // Verifica se o recurso já existe
    $reg = Objeto::where('chave', '=', $chave)
        ->where('balde', '=', $balde)
        ->first();
    $existe = $reg != NULL;
    // Se não existe, cria
    if (!$existe) {
        $reg = new Objeto;
        $reg->chave = $chave;
        $reg->usuario = request()->input('usuario');
        $reg->balde = $balde;
    }
    $reg->valor = request()->input('valor');
    $reg->save();

    $codigo = $existe ? 200 : 201;
    return new Response("", $codigo);
});

Route::delete($url['objeto'], function (string $balde, string $chave) {
    if (Balde::where('nome', '=', $balde)->first() == NULL)
        return new Response("Balde $balde não encontrado", 404);
    $reg = Objeto::where('chave', '=', $chave)
        ->where('balde', '=', $balde)
        ->first();
    if ($reg == NULL) {
        return new Response("Objeto de chave $chave não encontrado no balde $balde", 404);
    }
    $reg->delete();
    return new Response("", 200);
});


function _url_replace(string $url, array $valores) {
    $replaced = $url;
    foreach ($valores as $nome => $valor) {
        $replaced = str_replace("{{$nome}}", $valor, $replaced);
    }
    return $replaced;
}
