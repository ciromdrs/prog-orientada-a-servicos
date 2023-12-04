<?php

namespace App\Http\Middleware;


use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;


class VerificarTokenSUAP
{
    /**
     * Verifica se tem um token SUAP válido na seção.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        # Verifica se a sessão contém os dados do SUAP do usuário
        if (empty(session()->get('nome_usual', ''))) {
            # Se não contém, tenta buscar os dados do SUAP
            
            # Para isso, verifica se a requisição tem o token SUAP
            $suap_token = $request->bearerToken();
            # Se a requisição não tem o token, retorna erro
            if (empty($suap_token)) {
                return response()->json([
                    'tipo' => 'erro',
                    'conteudo' => 'Não autorizado'
                ], 401);
            }

            # Se a requisição tem o token, pega os dados do SUAP e coloca na
            # sessão
            $dados_SUAP = $this->getDadosUsuarioSUAP($suap_token);
            session($dados_SUAP);
        }

        # Passou na verificação e a sessão está criada
        return $next($request);
    }


    /**
     * Pega os dados do usuário no SUAP.
     * 
     * @param string $suap_token Token JWT gerado pelo SUAP no URI
     * https://suap.ifrn.edu.br/api/v2/autenticacao/token/.
     * 
     * @return array Os dados do usuário no SUAP.
     */
    private function getDadosUsuarioSUAP($suap_token): array {
        $res = json_decode(
            Http::withToken($suap_token)
                ->acceptJson()
                ->get('https://suap.ifrn.edu.br/api/v2/minhas-informacoes/meus-dados/')
                ->getBody()->getContents(),
            associative: true
        );

        $dados = [
            'suap_token' => $suap_token,
            'nome' => $res['nome_usual'],
            'matricula' => $res['matricula']
            # Poderia retornar mais dados aqui
        ];

        return $dados;
    }
}
