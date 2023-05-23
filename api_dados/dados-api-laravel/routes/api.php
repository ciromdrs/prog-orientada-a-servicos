<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
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


Route::get('/', function () use ($url) {
    return response()->json($url);
});


Route::get($url['baldes'], function () {
    $baldes = Balde::all();
    return response()->json($baldes);
});


Route::get($url['balde'], function (string $balde) {
    if (Balde::where('nome', '=', $balde)->first() == NULL) {
        return new Response("Balde $balde não encontrado", 404);
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
    $reg = Balde::where('nome', '=', $balde)->first();
    if ($reg == NULL)
        $reg = new Balde;
    $reg->nome = $balde;
    $reg->usuario = $request->input('usuario');
    $reg->save();
    return new Response('', 201);
});

Route::delete($url['balde'], function (string $balde) {
    $balde = Balde::where('nome', '=', $balde)->first();
    if ($balde == NULL) {
        return new Response('', 404);
    }
    $balde->delete();
    return new Response('', 200);
});


Route::put($url['objeto'], function (string $balde, string $chave) {
    if (Balde::where('nome', '=', $balde)->first() == NULL)
        return new Response("Balde $balde não encontrado", 404);
    $reg = Objeto::where('chave', '=', $chave)
        ->where('balde', '=', $balde)
        ->first();
    if ($reg == NULL) {
        $reg = new Objeto;
        $reg->chave = $chave;
        $reg->usuario = request()->input('usuario');
        $reg->balde = $balde;
    }
    $reg->valor = request()->input('valor');
    $reg->save();
    return new Response("", 201);
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
    return new Response("", 201);
});
