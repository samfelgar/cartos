<?php

declare(strict_types=1);

namespace Samfelgar\Cartos\Auth\Models;

readonly class AuthRequest implements \JsonSerializable
{
    public function __construct(
        public string $clientId,
        public string $clientSecret,
    ) {}

    public function jsonSerialize(): array
    {
        return [
            'clientId' => $this->clientId,
            'clientSecret' => $this->clientSecret,
        ];
    }
}
