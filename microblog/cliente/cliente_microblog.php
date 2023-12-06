<?php

require 'vendor/autoload.php';

use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Exception\GuzzleException;


/**
 * Cliente da API do Microblog.
 */
class ClienteMicroblog {
    # Cliente HTTP para a API do SUAP
    private GuzzleClient $suap;

    # Cliente HTTP para a API do Microblog
    private GuzzleClient $microblog;


    /**
     * @param string $uri_microblog URI da API do Microblog.
     * @param string $uri_suap URI da API do SUAP.
     * @param string $suap_token Token JWT de acesso ao SUAP.
     */
    public function __construct(
            string $uri_microblog,
            string $uri_suap,
            private string $suap_token = ''
        ) {
        
        $this->microblog = new GuzzleClient([
            'base_uri' => $uri_microblog,
            'http_errors' => false,
        ]);

        $this->suap = new GuzzleClient([
            'base_uri' => $uri_suap,
        ]);
    }


    /**
     * Retorna a lista de publicações.
     */
    public function getPublicacoes(): array {
        $resposta = $this->microblog->request("GET", 'publicacoes');
        $publicacoes = json_decode($resposta->getBody());
        return $publicacoes;
    }


    /**
     * Cria uma publicação.
     * 
     * @param array $p array contendo os dados da publicação.
     */
    public function criarPublicacao($p) {
        $resposta = $this->microblog->post(
            'publicacoes',
            [
                'json' => $p,
                'headers' => ['Authorization' => "Bearer $this->suap_token"]
            ]
        );
        return $resposta;
    }

    
    /**
     * Excui uma publicação.
     * 
     * @param int $id ID da publicação a ser excluída.
     */
    public function exluirPublicacao($id) {
        $resposta = $this->microblog->delete(
            "publicacoes/$id",
            ['headers' => ['Authorization' => "Bearer $this->suap_token"]]
        );
        return $resposta;
    }

    /**
     * Envia uma requisição de login ao SUAP para gerar um token de acesso e 
     * retorna os dados do usuário logado.
     * 
     * @param string $matricula A matrícula SUAP do usuário.
     * @param string $senha A senha do SUAP do usuário.
     * 
     * @return array Dados do usuário logado e token de acesso.
     */
    public function login($matricula, $senha): array {
        $this->suap_token = $this->criarTokenSUAP($matricula, $senha);

        $usuario = $this->getDadosUsuarioSUAP();
        $usuario['suap_token'] = $this->suap_token;
        
        return $usuario;
    }


    /**
     * Cria o token de acesso ao SUAP.
     * 
     * @param string $matricula A matrícula SUAP do usuário.
     * @param string $senha A senha SUAP do usuário.
     * 
     * @return string O token de acesso gerado pelo SUAP.
     */
    private function criarTokenSUAP($matricula, $senha): string {
        # Envia matrícula e senha no corpo da requisição
        $params = [
            'form_params' => [
                'username' => $matricula,
                'password' => $senha
            ]
        ];

        # Envia requisição ao SUAP para gerar o token de acesso
        $resp = $this->suap->post(
            '/api/v2/autenticacao/token/',
            $params
        );

        # Decodifica os dados da resposta JSON
        $resp_json = json_decode($resp->getBody());
        # Pega o token de acesso
        $token = $resp_json->access;

        return $token;
    }


    /**
     * Pega os dados do usuário no SUAP.
     * 
     * @return array Os dados do usuário no SUAP.
     */
    private function getDadosUsuarioSUAP(): array {
        $res = json_decode(
            $this->suap->get(
                'minhas-informacoes/meus-dados/',
                ['headers' => ['Authorization' => "Bearer $this->suap_token"]]
            )->getBody()->getContents(),
            associative: true
        );

        $dados = [
            'nome' => $res['nome_usual'],
            'matricula' => $res['matricula']
            # Poderia retornar mais dados aqui
        ];

        return $dados;
    }
}