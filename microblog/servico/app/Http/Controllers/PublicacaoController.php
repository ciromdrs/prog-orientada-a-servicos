<?php

namespace App\Http\Controllers;


use App\Models\Publicacao;
use Illuminate\Http\Request;

class PublicacaoController extends Controller
{
    public function index() {
        return response()->json(Publicacao::all());
    }


    public function store(Request $request) {
        $request->validate([
            'autor' => 'required',
            'texto' => 'required|max:144',
        ]);

        Publicacao::create($request->all());
        
        return response()->json(
            [
                'tipo' => 'info',
                'conteudo' => "Publicação criada."
            ],
            201
        );
    }


    public function show(Request $request, $id) {
        $p = Publicacao::find($id);

        if (!$p) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Publicação $id não encontrada."
                ],
                404
            );
        }

        return response()->json($p);
    }


    public function update(Request $request, $id) {

        $p = Publicacao::find($id);

        if (!$p) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Publicação $id não encontrada."
                ],
                404
            );
        }

        $p->update($request->all());
    }

    public function destroy(Request $request, $id) {
        $p = Publicacao::find($id);
        
        if (!$p) {
            return response()->json(
                [
                    'tipo' => 'erro',
                    'conteudo' => "Publicação $id não encontrada."
                ],
                404
            );
        }

        $p->delete();
        return response()->json([
            'tipo' => 'info',
            'conteudo' => "Publicação apagada."
        ]);
    }
}
