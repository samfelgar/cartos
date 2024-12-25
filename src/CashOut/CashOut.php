<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\CashOut;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Cartos\CashOut\Models\CashOutRequest;
use Samfelgar\Cartos\CashOut\Models\CashOutResponse;

class CashOut
{
    public function __construct(
        private readonly Client $client,
        private readonly string $accessToken,
        private readonly string $headerToken,
    ) {}

    /**
     * @throws GuzzleException
     */
    public function cashOut(CashOutRequest $request): CashOutResponse
    {
        $response = $this->client->post('/pix/v1/transferir', [
            'json' => $request,
            'headers' => [
                'authorization' => \sprintf('Bearer %s', $this->accessToken),
                'token' => $this->headerToken,
            ],
        ]);
        return CashOutResponse::fromResponse($response);
    }
}
