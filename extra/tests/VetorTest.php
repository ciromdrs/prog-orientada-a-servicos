<?php

const SRC_DIR = __DIR__ . '/../src/';

require SRC_DIR . 'Vetor.php';

use PHPUnit\Framework\TestCase;


/**
 * @covers Vetor
 */
final class VetorTest extends TestCase
{
    public function test_cria_vetor_preenchido(): void
    {   
        $elementos = [4, 2, 1, 3];
        $v = new Vetor($elementos);

        $this->assertEquals($v->elementos(), $elementos);
        $this->assertEquals(4, $v->tamanho());
        # O assertGreaterThanOrEqual deixa em aberto a possibilidade de
        # prealocar memória
        $this->assertGreaterThanOrEqual(4, $v->alocado());
        $this->assertGreaterThanOrEqual($v->alocado() - $v->tamanho(), $v->livre());
    }

    public function test_insere_em_vetor_vazio(): void
    {
        $v = new Vetor();
        $x = 10;

        $v->inserir($x);

        $this->assertEquals([$x], $v->elementos());
        $this->assertEquals(1, $v->tamanho());
        # O assertGreaterThanOrEqual deixa em aberto a possibilidade de alocar
        # memória
        $this->assertGreaterThan(0, $v->alocado());
        $this->assertEquals(0, $v->livre());
    }

    public function test_insere_em_vetor_preenchido(): void
    {
        $elementos = [1,2,3];
        $v = new Vetor($elementos);

        $v->inserir(4);

        $this->assertEquals(array_merge($elementos, [4]), $v->elementos());
    }

    public function test_excluir_existente(): void
    {
        $v = new Vetor([1,2,3]);

        $excluiu = $v->excluir(2);

        $this->assertEquals($excluiu, true);
        $this->assertEquals([1,3], $v->elementos());
    }

    public function test_excluir_inexistente(): void
    {
        $v = new Vetor([1,2,3]);

        $excluiu = $v->excluir(50);

        $this->assertEquals($excluiu, false);
        $this->assertEquals([1,2,3], $v->elementos());
    }

    public function test_elementos(): void
    {
        $v = new Vetor();
        $v->inserir(1);
        $v->inserir(2);
        $v->inserir(3);

        $this->assertEquals([1, 2, 3], $v->elementos());
    }
}