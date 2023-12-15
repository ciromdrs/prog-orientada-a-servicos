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
        $v = new Vetor([4, 2, 1, 3]);

        $this->assertCount(4, $v->elementos);
        $this->assertContains(1, $v->elementos);
        $this->assertContains(2, $v->elementos);
        $this->assertContains(3, $v->elementos);
        $this->assertContains(4, $v->elementos);
    }

    public function test_insere_em_vetor_vazio(): void
    {
        $v = new Vetor();

        $v->inserir(10);

        $this->assertCount(1, $v->elementos);
    }

    public function test_insere_em_vetor_preenchido(): void
    {
        $v = new Vetor([1,2,3]);

        $v->inserir(4);

        $this->assertCount(4, $v->elementos);
        $this->assertContains(1, $v->elementos);
        $this->assertContains(2, $v->elementos);
        $this->assertContains(3, $v->elementos);
        $this->assertContains(4, $v->elementos);
    }

    public function test_excuir_existente(): void
    {
        $v = new Vetor([1,2,3]);

        $v->excluir(2);

        $this->assertEquals(2, $v->tamanho);
        $this->assertEquals([1,3], $v->elementos);
        }
}