<?php


/**
 * Vetor (ou Array) capaz de armazenar e recuperar valores inteiros
 * não-negativos.
 */
class Vetor {
	/**
	 * Array que armazena os elementos e o espaço livre.
	 */
	private $dados = [];

	private $tamanho = 0;

	private $alocado = 0;

	public function __construct($elementos=[])
	{
		$this->dados = $elementos;
		$this->tamanho = count($elementos);
		# Poderíamos prealocar memória, mas optei por não fazer isso
		$this->alocado = $this->tamanho;
	}

	/**
	 * Extrai apenas o pedaço do array que possui elementos, desconsiderando
	 * espaços livres.
	 * 
	 * @return array Array de elementos.
	 */
	public function elementos()
	{
		return array_slice($this->dados, 0, $this->tamanho);
	}

	/**
	 * Busca um $elemento no vetor.
	 * 
	 * @param int $elemento o elemento a buscar no vetor.
	 * @return int o índice do elemento no vetor ou -1, se o elemento não for
	 * encontrado.
	 */
	public function buscar(int $elemento): int {
		for ($i = 0; $i<count($this->dados); $i++) {
			if ($this->dados[$i] == $elemento) {
				return $i;
			}
		}
		return -1;
	}

	/**
	 * Insere o $elemento no vetor.
	 * 
	 * @param int $elemento o elemento a ser inserido.
	 */
	public function inserir(int $elemento) {
		# Se não houver espaço livre
		if ($this->livre() <= 0) {
			# Cria um novo array com o dobro de tamanho alocado
			# Se o tamanho for 0, aloca 1
			$dobro = $this->alocado > 0 ? $this->alocado * 2 : 1;
			# Aloca o novo array na memória (preenchi com -1 porque é
			# obrigatório colocar algum valor)
			$novo = array_fill(0, $dobro, -1);
			# Copia os elementos do array antigo para o novo
			for ($i = 0; $i < $this->tamanho; $i++) {
				$novo[$i] = $this->dados[$i];
			}
			# Atribui o novo array
			$this->dados = $novo;
			# Atualiza o tamanho alocado
			$this->alocado = $dobro;

			/* Em linguagens de programação sem coletor de lixo, precisaríamos
			liberar a memória alocada para o vetor antigo aqui, mas o PHP faz
			isso pra nós. */
		}
		# Guarda o novo elemento no final
		$this->dados[$this->tamanho] = $elemento;
		# Incrementa o tamanho
		$this->tamanho++;
	}


	/**
	 * Exclui um elemento do vetor.
	 * 
	 * @param int $elemento o elemento a ser excluído.
	 * 
	 * @return bool true se o elemento foi encontrado e excluído ou false, caso
	 * contrário.
	 */
	public function excluir(int $elemento): bool {
		$i = $this->buscar($elemento);
		if (($i >= 0) && ($i < $this->tamanho)) {
			array_splice($this->dados, $i, length: 1);
			$this->tamanho--;
			return true;
		}
		return false;
	}

	/**
	 * @return int Retorna a quantidade de elementos salvos no vetor.
	 */
	public function tamanho(): int {
		return $this->tamanho;
	}

	/**
	 * @return int Retorna a quantidade de elementos que cabem no vetor sem a
	 * necessidade de redimensioná-lo, isto é, o espaço alocado livre.
	 */
	public function livre(): int {
		return $this->alocado - $this->tamanho;
	}

	/**
	 * @return int Retorna a quantidade de espaço alocado, em número de elementos.
	 */
	public function alocado(): int {
		return $this->alocado;
	}
}
