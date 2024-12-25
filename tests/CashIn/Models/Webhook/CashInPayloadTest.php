<?php

namespace Samfelgar\Cartos\Tests\CashIn\Models\Webhook;

use GuzzleHttp\Psr7\Request;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\UsesClass;
use PHPUnit\Framework\TestCase;
use Samfelgar\Cartos\CashIn\Models\Webhook\CashInPayload;
use Samfelgar\Cartos\CashIn\Models\Webhook\Payer;

#[CoversClass(CashInPayload::class)]
#[UsesClass(Payer::class)]
class CashInPayloadTest extends TestCase
{
    #[Test]
    public function itCanCreateAPayloadFromAnArray(): void
    {
        $payload = CashInPayload::fromArray([
            "evento" => "PixIn",
            "token" => "j8m17eqrxblf6s38upn969qoxvdzca",
            "endToEndId" => "E22ZDINC1P1NLTKLF16YJU0FTWS4",
            "txid" => "qzzcp3e8srj383fy2kudy3jial",
            "codigoTransacao" => "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
            "chavePix" => "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
            "valor" => 100,
            "horario" => "2020-12-21T13:40:34.000Z",
            "infoPagador" => "pagando o pix",
            "pagador" => [
                "nome" => "TESTE PAGADOR",
                "cpf_cnpj" => "***.123.456-**",
                "codigoBanco" => "00123456",
            ],
        ]);

        $this->assertInstanceOf(CashInPayload::class, $payload);
        $this->assertEquals(1, $payload->amount);
    }

    #[Test]
    public function itCanCreateAPayloadFromARequest(): void
    {
        $request = new Request('POST', '/', body: \json_encode([
            "evento" => "PixIn",
            "token" => "j8m17eqrxblf6s38upn969qoxvdzca",
            "endToEndId" => "E22ZDINC1P1NLTKLF16YJU0FTWS4",
            "txid" => "qzzcp3e8srj383fy2kudy3jial",
            "codigoTransacao" => "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
            "chavePix" => "8ayu4nku-6dyr-umji-v9qz-illzklc4ex33",
            "valor" => 10,
            "horario" => "2020-12-21T13:40:34.000Z",
            "infoPagador" => "pagando o pix",
            "pagador" => [
                "nome" => "TESTE PAGADOR",
                "cpf_cnpj" => "***.123.456-**",
                "codigoBanco" => "00123456",
            ],
        ]));

        $cashInPayload = CashInPayload::fromRequest($request);
        $this->assertInstanceOf(CashInPayload::class, $cashInPayload);
        $this->assertEquals(0.1, $cashInPayload->amount);
    }
}
