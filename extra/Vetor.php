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

	/**
	 * Insere o $valor no vetor.
	 * 
	 * @param int $valor o valor a ser inserido.
	 */
	public function inserir(int $valor) {
		/* Estamos usando o array pronto do PHP, que cuida da alocação de
		memória automaticamente. Em outras linguagens de programação, teríamos
		que gerenciar a memória manualmente. 
		Isso dificulta nosso trabalho, mas dá mais controle sobre a memória. */
		$this->dados[] = $valor;
	}


	/**
	 * Exclui um valor do vetor.
	 * 
	 * @param int $valor o valor a ser excluído.
	 * 
	 * @return bool true se o valor foi encontrado e excluído ou false, caso
	 * contrário.
	 */
	public function excluir(int $valor): bool {
		$i = $this->buscar($valor);
		if ($i >= 0) {
			unset($this->dados[$i]);
			return true;
		}
		return false;
	}
}
