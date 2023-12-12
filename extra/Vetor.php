<?php


/**
 * Vetor (ou Array) capaz de armazenar e recuperar valores inteiros
 * não-negativos.
 */
class Vetor {

	/**
	 * Array que armazena os dados.
	 */
	public $dados = [];

	/**
	 * Busca um $valor no vetor.
	 * 
	 * @param int $valor o valor a buscar no vetor.
	 * @return int o índice do elemento no vetor ou -1, se o elemento não for
	 * encontrado.
	 */
	public function buscar(int $valor): int {
		for ($i = 0; $i<count($this->dados); $i++) {
			if ($this->dados[$i] == $valor) {
				return $i;
			}
		}
		return -1;
	}
}
