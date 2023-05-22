<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use \App\Models\Balde;

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
$url['chave']  = "{$url['balde']}/{chave}";

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
        return new Response('Não encontrado', 404);
    }
    if ($request->method == 'GET') { //, listar dados'
        $teste = "listar objetos do balde $balde";
    }
    return $teste;
});

Route::put($url['balde'], function (Request $request, string $balde) {
    $reg = Balde::where('nome', '=', $balde)->first();
    if ($reg == NULL)
        $reg = new Balde;
    $reg->nome = $balde;
    $reg->usuario = $request->input('usuario');
    $reg->save(); // Esta linha está estourando a memória
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
