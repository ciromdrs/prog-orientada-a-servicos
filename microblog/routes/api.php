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
            ]
        ]);
    }
);


Route::post('publicacoes',
    function(Request $request) {
        $request->validate([
            'autor' => 'required',
            'texto' => 'required|max:144',
        ]);
        # Apenas um exemplo de resposta. Os dados deveriam ser salvos no banco.
        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Publicação criada (mentira, falta implementar isso)."
            ],
            201
        );
    }
);


Route::get('publicacoes/{id}',
    function(Request $request, $id) {
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        if ($id == 1) {
            return response()->json([
                'id' => 1,
                'autor' => 'alice',
                'texto' => 'Minha primeira publicação.',
                'criacao' => '2023-10-07T15:30:00',
            ]);
        }

        if ($id == 2) {
            return response()->json([
                'id' => 2,
                'autor' => 'bob',
                'texto' => 'Publicação exemplo.',
                'criacao' => '2023-10-08T07:30:00',
            ]);
        }

        return response()->json(
            [
                'tipo' => 'erro',
                'conteudo' => 'Não encontrado.'
            ],
            404
        );
    }
);


Route::put('publicacoes/{id}',
    function(Request $request, $id) {
        $request->validate(['texto' => 'max:144']);
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        if (in_array($id, [1,2])) {
            return response()->json(
                [
                    'tipo' => 'info',
                    'conteudo' => "Publicação alterada (mentira, falta implementar isso).",
                ]
            );
        }

        return response()->json(
            [
                'tipo' => 'erro',
                'conteudo' => 'Não encontrado.'
            ], 404
        );
    }
);



Route::delete('publicacoes/{id}',
    function(Request $request, $id) {
        # Apenas um exemplo de resposta. Os dados deveriam vir do banco.
        if (in_array($id, [1,2])) {
            return response()->json(
                [
                    'tipo' => 'info',
                    'conteudo' => "Publicação apagada (mentira, falta implementar isso).",
                ]
            );
        }

        return response()->json(
            [
                'tipo' => 'erro',
                'conteudo' => 'Publicação não encontrada.'
            ],
            404
        );
    }
);