<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\Auth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Cartos\Auth\Models\AuthRequest;
use Samfelgar\Cartos\Auth\Models\AuthResponse;

class Authentication
{
    public function __construct(
        public readonly Client $client,
    ) {}

    /**
     * @throws GuzzleException
     */
    public function authenticate(AuthRequest $request): AuthResponse
    {
        $response = $this->client->post('/no-auth/autenticacao/v1/api/login', [
            'json' => $request,
        ]);
        return AuthResponse::fromResponse($response);
    }
}
