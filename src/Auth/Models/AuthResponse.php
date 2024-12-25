<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\Auth\Models;

use Psr\Http\Message\ResponseInterface;

readonly class AuthResponse
{
    public function __construct(
        public bool $success,
        public string $message,
        public string $accessToken,
        public string $tokenType,
    ) {}

    public static function fromArray(array $data): AuthResponse
    {
        return new AuthResponse(
            (bool)$data['sucesso'],
            $data['mensagem'],
            $data['accessToken'],
            $data['tokenType'],
        );
    }

    public static function fromResponse(ResponseInterface $response): AuthResponse
    {
        $body = \json_decode((string)$response->getBody(), true);
        return self::fromArray($body);
    }
}
