<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Publicacao;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class PublicacaoControllerTest extends TestCase
{
    use RefreshDatabase;

    public function setUp(): void {
        parent::setUp();

        $this->artisan('db:seed --class=PublicacaoSeeder');
    }


    /**
     * Testar listar publicações.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get(route('publicacoes.index'));
        $publicacoes = json_decode($response->getContent(), associative: true);

        $this->assertGreaterThan(0, count($publicacoes));
    }


    /**
     * Testar criar publicação.
     *
     * @return void
     */
    public function test_store()
    {
        $response = $this->json(
            'POST',
            route('publicacoes.index'),
            ['autor' => 'bob', 'texto' => 'exemplo']
        );
        
        $response->assertStatus(201);
    }


    /**
     * Testar exibir publicação.
     *
     * @return void
     */
    public function test_show()
    {
        $p = Publicacao::first();

        $response = $this->get(route('publicacoes.show', [$p->id]));
        
        $response->assertJsonFragment($p->toArray());
    }

    /**
     * Testar apagar publicação.
     *
     * @return void
     */
    public function test_destroy()
    {
        $p = Publicacao::first();

        $this->delete(route('publicacoes.destroy', [$p->id]));
        
        $this->assertModelMissing($p);
    }

    /**
     * Testar alterar publicação.
     *
     * @return void
     */
    public function test_update()
    {
        $p = Publicacao::first();

        $this->json(
            'PUT',
            route('publicacoes.update', [$p->id]),
            ['texto' => 'Texto alterado']
        );

        $this->assertDatabaseHas('publicacoes',
            ['id' => $p->id, 'texto' => 'Texto alterado']
        );
    }
}
