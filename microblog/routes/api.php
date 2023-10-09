<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('publicacoes',
    function(Request $request) {
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        return response()->json([
            [
                'id' => 1,
                'autor' => 'alice',
                'texto' => 'Minha primeira publicação.',
                'criacao' => '2023-10-07T15:30:00',
            ],
            [
                'id' => 2,
                'autor' => 'bob',
                'texto' => 'Publicação exemplo.',
                'criacao' => '2023-10-08T07:30:00',
            ],
        ]);
    }
);


