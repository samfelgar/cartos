<?php

namespace Samfelgar\Cartos\Tests\CashOut\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use Samfelgar\Cartos\CashOut\Models\CashOutResponse;
use PHPUnit\Framework\TestCase;

#[CoversClass(CashOutResponse::class)]
class CashOutResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAnArray(): void
    {
        $json = '{
  "sucesso": true,
  "mensagem": "Transacão salva com Sucesso",
  "codigoTransacao": "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
  "dataHoraTransacao": "2024-10-30T13:24:57+00:00"
}';
        $data = \json_decode($json, true);
        $cashOutResponse = CashOutResponse::fromArray($data);
        $this->assertInstanceOf(CashOutResponse::class, $cashOutResponse);
        $this->assertEquals('30/10/2024 13:24:57', $cashOutResponse->createdAt->format('d/m/Y H:i:s'));
    }

    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = '{
  "sucesso": true,
  "mensagem": "Transacão salva com Sucesso",
  "codigoTransacao": "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
  "dataHoraTransacao": "2024-10-30T13:24:57+00:00"
}';
        $response = new Response(body: $json);
        $cashOutResponse = CashOutResponse::fromResponse($response);
        $this->assertInstanceOf(CashOutResponse::class, $cashOutResponse);
        $this->assertEquals('30/10/2024 13:24:57', $cashOutResponse->createdAt->format('d/m/Y H:i:s'));
    }
}
