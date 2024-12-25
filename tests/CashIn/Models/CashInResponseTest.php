<?php

namespace Samfelgar\Cartos\Tests\CashIn\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashIn\Models\CashInResponse;
use Samfelgar\Cartos\CashIn\Models\QrCode;

#[CoversClass(CashInResponse::class)]
#[UsesClass(QrCode::class)]
class CashInResponseTest extends TestCase
{
    #[Test]
    public function itCanParseAnArray(): void
    {
        $json = '{
  "sucesso": true,
  "mensagem": "Gerando QRCode com sucesso.",
  "txId": "sxe3i0qleeqaunhnnndmxno0kg59gh",
  "qrcode": {
    "Imagem": "asdf",
    "EMV": "fdsa"
  }
}';
        $data = \json_decode($json, true);
        $cashInResponse = CashInResponse::fromArray($data);
        $this->assertInstanceOf(CashInResponse::class, $cashInResponse);
        $this->assertEquals('sxe3i0qleeqaunhnnndmxno0kg59gh', $cashInResponse->txId);
    }

    #[Test]
    public function itCanParseAResponse(): void
    {
        $json = '{
  "sucesso": true,
  "mensagem": "Gerando QRCode com sucesso.",
  "txId": "sxe3i0qleeqaunhnnndmxno0kg59gh",
  "qrcode": {
    "Imagem": "asdf",
    "EMV": "fdsa"
  }
}';
        $response = new Response(body: $json);
        $cashInResponse = CashInResponse::fromResponse($response);
        $this->assertInstanceOf(CashInResponse::class, $cashInResponse);
        $this->assertEquals('sxe3i0qleeqaunhnnndmxno0kg59gh', $cashInResponse->txId);
    }
}
