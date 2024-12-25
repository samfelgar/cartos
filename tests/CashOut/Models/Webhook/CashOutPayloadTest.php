<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models\Webhook;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashOut\Models\Webhook\CashOutPayload;
use Samfelgar\Cartos\CashOut\Models\Webhook\Error;
use Samfelgar\Cartos\CashOut\Models\Webhook\Recipient;
use Samfelgar\Cartos\CashOut\Models\Webhook\Status;

#[CoversClass(CashOutPayload::class)]
#[CoversClass(Error::class)]
#[CoversClass(Recipient::class)]
class CashOutPayloadTest extends TestCase
{
    #[Test]
    public function itParsesProcessingRequests(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": "E5151512675793904351659710459689",
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Em processamento",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf_cnpj": "12345678912345"
              },
              "erro": null
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
    }

    #[Test]
    public function itParsesConfirmationRequests(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": "E5151512675793904351659710459689",
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Sucesso",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf": "12345678912"
              },
              "erro": null
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
    }

    #[Test]
    public function itParsesConfirmationErrorRequests(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": "E5151512675793904351659710459689",
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Erro",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf": "12345678912"
              },
              "erro":{
              "origem": "Origem interna",
              "motivo": "Erro de processamento, transação não executada. Reenvie a transação"
              }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }

    #[Test]
    public function itParsesFailRequestsWithAlreadySentId(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": null,
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Falha",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf_cnpj": "12345678912345"
              },
              "erro":{
              "mensagem": "Esse idEnvio não pode ser repetido para o mesmo token"
              }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals(Status::Fail, $payload->status);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }

    #[Test]
    public function itParsesFailRequestsWithNoFunds(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": null,
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Falha",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf_cnpj": "12345678912345"
              },
              "erro":{
              "mensagem": "Conta sem saldo"
              }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals(Status::Fail, $payload->status);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }

    #[Test]
    public function itParsesFailRequestsWithInvalidPixKey(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOut",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": null,
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Falha",
              "chavePix": "teste@hotmail.com",
              "valor": -1,
              "horario": "2024-11-04T14:47:12.221Z",
              "recebedor": {
                "nome": "Teste da Silva LTDA",
                "codigoBanco": "13140088",
                "cpf_cnpj": "12345678912345"
              },
              "erro":{
                    "origem": "Origem interna",
                    "motivo": "Chave PIX não cadastrada."
            }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals(Status::Fail, $payload->status);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 14:47:12', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }

    #[Test]
    public function itParsesFailRequestsWithInternalError(): void
    {
        $json = <<<JSON
            {
                "evento": "PixOut",
                "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
                "idEnvio": "hayjqnv754f6f",
                "endToEndId": null,
                "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
                "status": "Falha",
                "chavePix": "teste@hotmail.com",
                "valor": -1,
                "horario": "2024-11-22T11:16:54.787Z",
                "recebedor": null,
                "erro": {
                    "origem": "Origem interna",
                    "motivo": "Erro de processamento, transação não executada. Reenvie a transação"
                }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals(Status::Fail, $payload->status);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('22/11/2024 11:16:54', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }

    #[Test]
    public function itParsesReversalRequests(): void
    {
        $json = <<<JSON
            {
              "evento": "PixOutReversalExternal",
              "token": "lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8",
              "idEnvio": "hayjqnv754f6f",
              "endToEndId": "E5151512675793904351659710459689",
              "codigoTransacao": "nbyayb64-pws9-4jd6-v4yg-5v04ljwqlpto",
              "status": "Sucesso",
              "chavePix": null,
              "valor": 1,
              "horario": "2024-11-04T15:51:13.431Z",
              "recebedor": {
                "nome": null,
                "codigoBanco": "13140088",
                "cpf_cnpj": "***.456.789-**"
              },
              "erro": {
                "origem": "Origem externa",
                "motivo": "Pedido de reembolso"
              }
            }
            JSON;

        $request = new Request('post', '/', body: $json);
        $payload = CashOutPayload::fromRequest($request);
        $this->assertInstanceOf(CashOutPayload::class, $payload);
        $this->assertEquals(Status::Confirmed, $payload->status);
        $this->assertEquals('lerkh7r79nkdjda1yg9zxvuhvc64kbfethixv9k6vt7fxmx8', $payload->token);
        $this->assertEquals('04/11/2024 15:51:13', $payload->createdAt->format('d/m/Y H:i:s'));
        $this->assertInstanceOf(Error::class, $payload->error);
    }
}
