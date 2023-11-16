<?php

namespace Tests\Feature\Models;

use App\Models\Publicacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicacaoTest extends TestCase
{
    use RefreshDatabase;


    /**
     * Testar se cria uma Publicacao.
     *
     * @return void
     */
    public function test_criar()
    {
        Publicacao::create([
            'autor' => 'alice',
            'texto' => 'Publicação teste.'
        ]);

        $this->assertDatabaseHas('publicacoes',
            ['autor' => 'alice', 'texto' => 'Publicação teste.']
        );
    }


    /**
     * Testar se apaga uma Publicacao.
     *
     * @return void
     */
    public function test_apagar() {
        $p = Publicacao::create([
            'autor' => 'alice',
            'texto' => 'Publicação teste.'
        ]);

        $p->delete();

        $this->assertModelMissing($p);
    }
}
