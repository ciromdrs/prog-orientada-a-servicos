<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PublicacaoController extends Controller
{
    public function index() {
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

    public function store(Request $request) {
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
}
