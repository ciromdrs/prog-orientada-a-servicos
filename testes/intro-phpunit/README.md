# Testes de Unidade com PHPUnit
Tutorial sobre testes de unidade com PHPUnit.
Este tutorial é baseado em [Getting Started with PHPUnit 10](https://phpunit.de/getting-started/phpunit-10.html) e na [Documentação Oficial](https://docs.phpunit.de/en/10.3/).

Notas:
1. Os comandos apresentados neste tutorial são para o terminal Linux.
Adapte-os ao seu sistema operacional.

1. Até o momento da escrita deste tutorial (set/2023), os laboratórios não
tinham o driver de cobertura de testes instalado.
Você pode pular a seção correspondente a este assunto.

1. Seções <mark>destacadas</mark> indicam alterações feitas no tutorial.

## Preparação
1. Instale o PHPUnit via Composer:
    ```bash
    composer require --dev phpunit/phpunit
    ```
    *Nota:* lembre-se de incluir a opção `--dev` para instalar o PHPUnit apenas no ambiente de desenvolvimento.
    O PHPUnit NÃO deve ser instalado num ambiente de produção.

1. Configure o PHPUnit (basta aceitar os valores padrão):
    ```bash
    ./vendor/bin/phpunit --generate-configuration
    ```

1. No elemento `phpunit`:
    1. adicione `colors="true"` para deixar a saída do PHPUnit mais bonita no terminal;
    1. altere `requireCoverageMetadata` para `"false"` para ignorar alguns warnings por enquanto.

1. Crie os diretórios `/tests` e `/src`.

1. Teste o comando phpunit:
    ```bash
    ./vendor/bin/phpunit
    ```

## Primeiros Testes de Unidade
1. Crie o arquivo `src/Email.php` com o conteúdo abaixo:
    ```php
    <?php declare(strict_types=1);

    final class Email
    {
        private string $endereco;

        public function __construct(string $endereco)
        {
            $this->endereco = $endereco;
        }

        public function getEndereco(): string
        {
            return $this->endereco;
        }
    }
    ```
    Este código declara a classe Email com seu construtor.

1. Crie uma função para verificar se o endereço passado como parâmetro para o construtor é válido:
    ```php
    public function __construct(string $endereco)
    {
        self::validar($endereco);
        $this->endereco = $endereco;
    }


    private static function validar(string $endereco)
    {
        // Validação burra, apenas verifica se a srting contém um @
        if (!str_contains($endereco, '@')) {
            throw new InvalidArgumentException(
                "Endereço de e-mail inválido: '$endereco'."
            );
        }
    }
    ```

1. Escreva um teste para esta função passando um e-mail válido. Crie o arquivo `tests/EmailTest.php`:
    ```php
    <?php declare(strict_types=1);

    const SRC_DIR = __DIR__ . '/../src/';

    require SRC_DIR . 'Email.php';

    use PHPUnit\Framework\TestCase;


    final class EmailTest extends TestCase
    {
        public function testCriarComEnderecoValido(): void
        {
            $endereco = 'teste@exemplo.com';
            $email = new Email($endereco);
            
            $this->assertSame($endereco, $email->getEndereco());
        }
    }
    ```
    Veja a [lista completa de assertions](https://docs.phpunit.de/en/10.3/assertions.html).

    Nota: Ignore os warnings relacionados a coverage por enquanto.


1. Execute os testes:
    ```bash
    ./vendor/bin/phpunit
    ```


1. Escreva um teste para esta função passando um e-mail inválido. Altere o arquivo `tests/EmailTest.php`:
    ```php
    public function testNaoCriarComEnderecoInvalido(): void
    {
        $endereco = 'endereço inválido';

        $this->expectException(InvalidArgumentException::class);

        $email = new Email($endereco);
    }
    ```
    <mark>Perceba que, diferentemente do teste anterior, aqui estamos esperando que o código lance uma exceção (`InvalidArgumentException`).
    Para isto, antes de chamar o código que lança a exceção, avisamos ao PHPUnit que estamos esperando esta exceção (usando `$this->expectException`...).
    Execute os testes novamente.</mark>

## DataProviders
1. Um [DataProvider](https://docs.phpunit.de/en/10.3/attributes.html#data-provider) é uma função que fornece dados para um teste.
    Crie um DataProvider de e-mails válidos.
    ```php
    ...
    use PHPUnit\Framework\Attributes\DataProvider;


    final class EmailTest extends TestCase
    {
        public function enderecosValidosProvider(): array
        {
            return [
                ['alice@exemplo.com'],
                ['bruno@exemplo.net'],
                ['clara@seila.org']
            ];
        }

        #[DataProvider('enderecosValidosProvider')]
        public function testCriarComEnderecoValido($endereco): void
        {
            $email = new Email($endereco);
            
            $this->assertSame($endereco, $email->getEndereco());
        }

        ...
    }
    ```

    Execute novamente os testes.


## Cobertura de Testes
<mark>
    Nota: Até o momento da escrita deste tutorial (set/2023), os laboratórios não
    tinham o driver de cobertura de testes instalado.
    Você pode pular esta seção.
</mark>

1. Execute os testes com um [relatório de cobertura](https://docs.phpunit.de/en/10.3/textui.html#code-coverage).
    ```bash
    ./vendor/bin/phpunit --coverage-text
    ```

    Nota: Se o PHP reclamou da variável `XDEBUG_MODE`, execute:
    ```bash
    export XDEBUG_MODE=coverage
    ```

1. Adicione o atributo `CoversClass` à classe `EmailTest`.
    ```php
    ...
    use PHPUnit\Framework\Attributes\CoversClass;


    #[CoversClass('Email')]
    final class EmailTest extends TestCase
    ...
    ```
    Execute os testes com o relaório de cobertura novamente.

1. Adicione ao arquivo `phpunit.xml` o relatório de cobertura em HTML.
    ```xml
    <coverage>
        <report>
            <html outputDirectory="tests/coverage/"/>
        </report>
    </coverage>
    ```
    Execute os testes e veja o relatório HTML no diretório mencionado na configuração.

## Exercícios
1. Adicione os seguintes métodos à classe `Email`:
    1. `getUsuario`: Retorna a parte à esquerda do `"@"` de um endereço.

    1. `getDominio`: Retorna a parte à direita do `"@"` de um endereço.

    Crie os testes <mark>`testGetUsuario` e `testGetDominio` na classe `EmailTest`</mark> para verificar a correção desses novos métodos.


1. Atualmente, a função de validação de endereços de e-mail apenas verifica se a string contém um `"@"`.
    Essa verificação, porém, é muito ingênua.
    Vamos melhorá-la gradativamente e, como consequência, perceberemos como testes automáticos nos ajudam nesse processo.
    Considere as seguintes possibilidades:

    | Exemplo de E-mail Inválido | Motivo                     |
    |-|-|
    | `usuario@`                 | Sem domínio |
    | `@dominio.com`             | Sem usuário |
    | `@`                        | Sem usuário nem domínio. |
    
    1. Crie um DataProvider <mark>`enderecosInvalidosProvider`</mark> de e-mails inválidos com os valores acima.

    1. Altere a função de validação de endereços de e-mail para considerar os novos casos:
        1. Verifique se o endereço contém um `"@"`.
        1. Verifique se há um usuário à esquerda do `"@"`.
        1. Verifique se há um domínio à direita do `"@"`.

    1. Considere agora as seguintes possibilidades <mark>(adicione-as ao data provider criado no ponto anterior)</mark>:
        
        | E-mail nválido | Motivo |
        |-|-|
        | `usuario@dominio`             | Sem extensão de domínio |
        | `usuario@.com`                | Ponto antes da extensão de domínio |
        | `usu@exemplo.`               | Termina com ponto. |
        | `dois@arrobas@exemplo.com`   | Dois arrobas no endereço. |
        | `usuario@dominio..com`        | Dois pontos consecutivos no domínio    |
        | `usuario@-dominio.com`        | Hífen no início do domínio             |
        | `usuario@domínio.com-`        | Hífen no final do domínio              |
        | `usuario@domínio!.com`        | Caracteres especiais no domínio        |

        <mark>
            Neste momento, seu código deve estar falhando nos testes.
            Isso acontece porque, como podemos ver, validação de e-mail é uma tarefa árdua.
        </mark>
        Felizmente, o PHP tem uma função pronta para isso.
        Altere a condição de validação para:
        
        ```php
        !filter_var($endereco, FILTER_VALIDATE_EMAIL)
        ```


1. Substitua o atributo `endereco` da classe `Email` por dois atributos `usuario` e `dominio`.
Os métodos `getEndereco`, `getUsuario` e `getDominio` devem ser alterados para passar nos testes.
O construtor da classe `Email` e todos os testes de unidade <mark>na classe `EmailTest`</mark> devem permanecer intactos.

