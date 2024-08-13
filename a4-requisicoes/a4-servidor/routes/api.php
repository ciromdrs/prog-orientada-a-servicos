<?php

use Illuminate\Http\Request;
use Illuminate\Http\Response;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('/q1', function () {
    return 'Hello, world!';
});


Route::get('/q2/{nome}', function ($nome) {
    return "Hello, $nome!";
});


Route::get('/q3', function (Request $request) {
    $nome = request()->query('nome');
    if ($nome == '') {
        return new Response(
            "Requisição inválida (faltou o ?nome=... na URL):\n$request", 400);
    }
    return "Hello, $nome!";
});


Route::get('/q4', function () {
    if (count(request()->query()) == 0) {
        return new Response(
            "Requisição inválida (faltou a string de consulta na URL):\n$request",
            400);
    }
    $resp = '';
    foreach (request()->query() as $var => $valor) {
        $resp .= "$var=$valor\n";
    }
    return $resp;
});


Route::post('/q9', function () {
    return new Response('', 201);
});


Route::post('/q10', function (Request $request) {
    if (!str_contains($request->header("Content-type"), 'multipart/form-data'))
        return new Response("Content-type inválido:" .
                            "\n{$request->header("Content-type")}", 400);
    $nome = $request->input('nome');
    if ($nome == NULL)
        return new Response("Requisição inválida:\n$request", 400);
    return new Response('', 201);

});


Route::post('/q11', function (Request $request) {
    if (!str_contains($request->header("Content-type"), 'multipart/form-data'))
        return new Response("Content-type inválido:" .
                            "\n{$request->header("Content-type")}", 400);
    $dados_form = $request->all();
    if ($dados_form == NULL)
        return new Response("Requisição inválida:\n$request", 400);
    return new Response('', 201);
});


Route::post('/q12', function (Request $request) {
    if (!$request->isJson())
        return new Response("Content-type inválido:\n$request", 400);
    $dados_form = $request->all();
    if ($dados_form == NULL)
        return new Response(
            "Requisição inválida (faltaram os valores via JSON no corpo):\n$request",
            400);
    return new Response('', 201);
});


Route::put('/q13', function () {
    return '';
});


Route::put('/q14', function (Request $request) {
    if (!$request->isJson())
        return new Response("Content-type inválido:\n$request", 400);
    $dados = $request->all();
    if (count($dados) == 0)
        return new Response(
            "Requisição inválida (faltaram os valores via JSON no corpo):\n$request",
            400);
    return '';
});


Route::delete('/q15', function () {
    return '';
});


Route::delete('/q16/{nome}', function ($nome) {
    return '';
});


Route::delete('/q17', function () {
    $nome = request()->input('nome');
    if ($nome == NULL)
        return new Response(
            "Requisição inválida (faltou o ?nome=... na URL):\n$request", 400);
    return '';
});


Route::delete('/q18', function () {
    if (count(request()->query()) == 0) {
        return new Response(
            "Requisição inválida (faltou a string de consulta na URL):\n$request",
            400);
    }
    return '';
});
