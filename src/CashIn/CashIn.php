<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashIn;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Cartos\CashIn\Models\CashInRequest;
use Samfelgar\Cartos\CashIn\Models\CashInResponse;

class CashIn
{
    public function __construct(
        private readonly Client $client,
        private readonly string $accessToken,
        private readonly string $headerToken,
    ) {}

    /**
     * @throws GuzzleException
     */
    public function generateQrCode(CashInRequest $request): CashInResponse
    {
        $response = $this->client->post('/qrcode/v1/gerar', [
            'json' => $request,
            'headers' => [
                'authorization' => \sprintf('Bearer %s', $this->accessToken),
                'token' => $this->headerToken,
            ],
        ]);
        return CashInResponse::fromResponse($response);
    }
}
