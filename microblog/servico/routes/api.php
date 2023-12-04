<?php

use App\Http\Controllers\PublicacaoController;
use App\Http\Middleware\VerificarTokenSUAP;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::name('publicacoes.')
    ->prefix('/publicacoes')
    ->controller(PublicacaoController::class)
    ->group(function() {
        # Operações públicas
        Route::name('index')->get('', 'index');
        Route::name('show')->get('/{id}', 'show');
    })
    ->group(function () {
        # Operações restritas
        Route::middleware(VerificarTokenSUAP::class)
            ->group(function () {
                Route::name('store')->post('', 'store');
                Route::name('update')->put('/{id}', 'update');
                Route::name('destroy')->delete('/{id}', 'destroy');
            });
    });