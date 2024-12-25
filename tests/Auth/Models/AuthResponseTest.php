<?php

namespace Samfelgar\Cartos\Tests\Auth\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\Auth\Models\AuthResponse;

#[CoversClass(AuthResponse::class)]
class AuthResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAnArray(): void
    {
        $data = [
            "sucesso" => true,
            "mensagem" => "Cliente de API autenticado com sucesso.",
            "accessToken" => "j8m17eqrxblf6s38upn969qoxvdzca",
            "tokenType" => "Bearer",
        ];

        $authResponse = AuthResponse::fromArray($data);
        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertSame('j8m17eqrxblf6s38upn969qoxvdzca', $authResponse->accessToken);
    }

    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = '{
  "sucesso": true,
  "mensagem": "Cliente de API autenticado com sucesso.",
  "accessToken": "j8m17eqrxblf6s38upn969qoxvdzca",
  "tokenType": "Bearer"
}';

        $response = new Response(200, body: $json);
        $authResponse = AuthResponse::fromResponse($response);
        $this->assertInstanceOf(AuthResponse::class, $authResponse);
        $this->assertEquals('j8m17eqrxblf6s38upn969qoxvdzca', $authResponse->accessToken);
    }
}
